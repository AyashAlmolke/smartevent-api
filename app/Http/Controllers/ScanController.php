<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\Attendance;
use App\Models\Log;
use App\Models\BehaviorData;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'device_info' => 'nullable|string',
            'ip_address' => 'nullable|ip',
        ]);

        try {
            // فك تشفير QR
            $decrypted = Crypt::decryptString($request->qr_code);
            $qrData = json_decode($decrypted, true);
            
            if (!isset($qrData['invitation_id'])) {
                return $this->errorResponse('QR غير صالح');
            }
            
            // البحث عن الدعوة
            $invitation = Invitation::with(['user', 'event'])->find($qrData['invitation_id']);
            
            if (!$invitation) {
                return $this->errorResponse('دعوة غير موجودة');
            }
            
            // ✅ 1. التحقق من صلاحية الدعوة
            if ($invitation->status !== 'active' || $invitation->used_at !== null) {
                return $this->errorResponse('هذه الدعوة مستخدمة بالفعل');
            }
            
            // ✅ 2. التحقق من حالة المستخدم
            if ($invitation->user->is_blacklisted) {
                $this->logAttempt($invitation, $request, 'blacklisted', 100);
                return $this->errorResponse('هذا المستخدم محظور');
            }
            
            // ✅ 3. التحقق من الفعالية
            $event = $invitation->event;
            
            // 3.1 هل الفعالية نشطة؟
            if ($event->status !== 'active') {
                return $this->errorResponse('الفعالية غير متاحة حالياً');
            }
            
            // ✅ 3.2 التحقق من وقت الانتهاء فقط (لا نمنع قبل البدء)
            $now = now();
            $endTime = $event->end_time;
            
            // هل انتهت الفعالية؟
            if ($endTime && $now->gt($endTime)) {
                return $this->errorResponse('انتهت فعالية، لا يمكن الدخول الآن');
            }
            
            // تحليل المخاطر
            $riskScore = $this->calculateRiskScore($invitation, $request);
            
            if ($riskScore > 70) {
                $this->logAttempt($invitation, $request, 'suspicious', $riskScore);
                return $this->errorResponse('تم اكتشاف سلوك مشبوه، يرجى التواصل مع الإدارة', $riskScore);
            }
            
            // تسجيل الدخول
            DB::beginTransaction();
            
            try {
                $invitation->update([
                    'status' => 'used',
                    'used_at' => now(),
                    'scan_attempts' => $invitation->scan_attempts + 1
                ]);
                
                Attendance::create([
                    'invitation_id' => $invitation->id,
                    //'check_in_time' => now()->setTimezone('Asia/Aden')->format('H:i:s'),
                    'check_in_time' => now(),
                    'status' => 'allowed',
                    'device_info' => $request->device_info ?? $request->userAgent(),
                    'ip_address' => $request->ip_address ?? $request->ip(),
                    'is_valid' => true,
                ]);
                
                BehaviorData::updateOrCreate(
                    ['user_id' => $invitation->user_id, 'event_id' => $invitation->event_id],
                    [
                        'attempts_count' => DB::raw('attempts_count + 1'),
                        'risk_score' => $riskScore,
                        'behavior_type' => $riskScore > 50 ? 'suspicious' : 'normal'
                    ]
                );
                
                Log::create([
                    'action_type' => 'success',
                    'invitation_id' => $invitation->id,
                    'user_id' => $invitation->user_id,
                    'device_info' => $request->device_info ?? $request->userAgent(),
                    'ip_address' => $request->ip_address ?? $request->ip(),
                    'message' => 'تم الدخول بنجاح',
                    'risk_score' => $riskScore,
                ]);
                
                DB::commit();
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'تم الدخول بنجاح',
                    'data' => [
                        'user_name' => $invitation->user->name,
                        'event_name' => $invitation->event->event_name,
                        'check_in_time' => now()->setTimezone('Asia/Aden')->format('H:i:s'),
                        //'check_in_time' => now()->format('H:i:s'),
                        'risk_score' => $riskScore,
                    ]
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            \Log::error('Scan error: ' . $e->getMessage());
            return $this->errorResponse('حدث خطأ في عملية المسح');
        }
    }
    
    private function calculateRiskScore($invitation, $request)
    {
        $score = 0;
        
        $failedAttempts = Log::where('invitation_id', $invitation->id)
            ->where('action_type', 'fail')
            ->count();
        $score += min($failedAttempts * 15, 40);
        
        $hour = now()->hour;
        if ($hour < 5 || $hour > 23) $score += 25;
        
        $lastDevice = Attendance::where('invitation_id', $invitation->id)
            ->latest()->value('device_info');
        if ($lastDevice && $lastDevice !== ($request->device_info ?? $request->userAgent())) {
            $score += 20;
        }
        
        $recentAttempts = Log::where('invitation_id', $invitation->id)
            ->where('created_at', '>=', now()->subMinutes(1))
            ->count();
        if ($recentAttempts > 3) $score += 30;
        
        return min($score, 100);
    }
    
    private function logAttempt($invitation, $request, $type, $riskScore)
    {
        Log::create([
            'action_type' => $type,
            'invitation_id' => $invitation->id,
            'user_id' => $invitation->user_id,
            'device_info' => $request->device_info ?? $request->userAgent(),
            'ip_address' => $request->ip_address ?? $request->ip(),
            'risk_score' => $riskScore,
        ]);
        
        $invitation->increment('scan_attempts');
    }
    
    private function errorResponse($message, $riskScore = 0)
    {
        return response()->json([
            'status' => 'denied',
            'message' => $message,
            'risk_score' => $riskScore,
        ], 403);
    }
}

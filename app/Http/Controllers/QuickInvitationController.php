<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class QuickInvitationController extends Controller
{
    // عرض صفحة الإنشاء السريع
    public function create()
    {
        $events = Event::where('status', 'active')->get();
        return view('invitations.quick', compact('events'));
    }

    // معالجة الإنشاء السريع
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
        ]);

        try {
            // 1. البحث عن مستخدم موجود
            $user = null;
            
            if ($request->phone) {
                $user = User::where('phone', $request->phone)->first();
            }
            
            if (!$user && $request->email) {
                $user = User::where('email', $request->email)->first();
            }

            // 2. إنشاء مستخدم جديد إذا لم يوجد
            if (!$user) {
                $user = User::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'is_blacklisted' => false,
                    'is_verified' => true,
                ]);
            }

            // 3. التحقق من عدم وجود دعوة مسبقة
            $existingInvitation = Invitation::where('user_id', $user->id)
                ->where('event_id', $request->event_id)
                ->exists();

            if ($existingInvitation) {
                return back()->with('error', 'هذا المستخدم لديه دعوة مسبقة لهذه الفعالية')->withInput();
            }

            // 4. إنشاء الدعوة
            $invitation = new Invitation();
            $invitation->user_id = $user->id;
            $invitation->event_id = $request->event_id;
            $invitation->status = 'active';
            $invitation->scan_attempts = 0;
            $invitation->qr_code = '';
            $invitation->save();

            // 5. توليد QR مشفر
            $qrPayload = json_encode([
                'invitation_id' => $invitation->id,
                'user_id' => $invitation->user_id,
                'event_id' => $invitation->event_id,
                'created_at' => now()->timestamp,
            ]);

            $invitation->qr_code = Crypt::encryptString($qrPayload);
            $invitation->save();

            return redirect()->route('admin.invitations.quick')
                ->with('success', 'تم إنشاء الدعوة بنجاح!')
                ->with('last_invitation', $invitation)
                ->with('last_user', $user);

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage())->withInput();
        }
    }
}

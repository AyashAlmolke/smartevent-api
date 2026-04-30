<?php

namespace App\Http\Controllers;

use App\Models\BehaviorData;
use App\Models\User;
use App\Models\Event;
use App\Models\Log;
use Illuminate\Http\Request;

class BehaviorController extends Controller
{
    public function index()
    {
        $behaviorData = BehaviorData::with(['user', 'event'])
            ->orderBy('risk_score', 'desc')
            ->paginate(20);
        
        $stats = [
            'normal' => BehaviorData::where('behavior_type', 'normal')->count(),
            'suspicious' => BehaviorData::where('behavior_type', 'suspicious')->count(),
            'abnormal' => BehaviorData::where('behavior_type', 'abnormal')->count(),
            'avg_risk' => BehaviorData::avg('risk_score'),
        ];
        
        $topRiskyUsers = User::withSum('behaviorData', 'risk_score')
            ->orderBy('behavior_data_sum_risk_score', 'desc')
            ->take(10)
            ->get();
        
        return view('behavior.index', compact('behaviorData', 'stats', 'topRiskyUsers'));
    }
    
    public function fraudDetection()
    {
        // كشف المحاولات المشبوهة
        $suspiciousLogs = Log::where('risk_score', '>', 70)
            ->with(['invitation.user', 'invitation.event'])
            ->latest()
            ->take(50)
            ->get();
        
        // إحصائيات الاحتيال
        $fraudStats = [
            'total_suspicious' => Log::where('risk_score', '>', 70)->count(),
            'blacklisted_users' => User::where('is_blacklisted', true)->count(),
            'duplicate_attempts' => Log::where('action_type', 'duplicate_attempt')->count(),
            'failed_attempts' => Log::where('action_type', 'fail')->count(),
        ];
        
        // أكثر المستخدمين خطورة
        $highRiskUsers = User::whereHas('behaviorData', function($q) {
            $q->where('risk_score', '>', 70);
        })->with('behaviorData')->get();
        
        return view('behavior.fraud', compact('suspiciousLogs', 'fraudStats', 'highRiskUsers'));
    }
}
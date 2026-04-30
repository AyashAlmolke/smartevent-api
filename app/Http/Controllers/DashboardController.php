<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Invitation;
use App\Models\Attendance;
use App\Models\Log;
use App\Models\BehaviorData;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // الإحصائيات الأساسية
        $stats = [
            'users' => User::count(),
            'events' => Event::count(),
            'invitations' => Invitation::count(),
            'attendances' => Attendance::count(),
            'active_events' => Event::where('status', 'active')->count(),
            'blacklisted' => User::where('is_blacklisted', true)->count(),
        ];
        
        // الحضور اليومي (آخر 7 أيام)
        $dailyAttendance = Attendance::select(
                DB::raw('DATE(check_in_time) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('check_in_time', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // المستخدمين عاليي المخاطر
        $highRiskUsers = BehaviorData::where('risk_score', '>', 70)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();
        
        // آخر المحاولات
        $recentLogs = Log::with('invitation.user')
            ->latest()
            ->take(10)
            ->get();
        
        // أكثر الفعاليات حضوراً
        $topEvents = Event::withCount('attendances')
            ->orderBy('attendances_count', 'desc')
            ->take(5)
            ->get();
        
        // نسبة النجاح
        $successCount = Log::where('action_type', 'success')->count();
        $failCount = Log::where('action_type', 'fail')->count();
        $totalAttempts = $successCount + $failCount;
        $successRate = $totalAttempts > 0 ? round(($successCount / $totalAttempts) * 100, 1) : 0;
        
        return view('dashboard', compact(
            'stats', 'dailyAttendance', 'highRiskUsers', 
            'recentLogs', 'topEvents', 'successRate'
        ));
    }
}
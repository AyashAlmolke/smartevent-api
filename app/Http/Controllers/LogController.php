<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // ✅ تعديل: إزالة 'user' لأنها غير موجودة
        $query = Log::with(['invitation.user']);
        
        // فلترة حسب النوع
        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }
        
        // فلترة حسب المخاطر
        if ($request->filled('risk_level')) {
            if ($request->risk_level == 'high') {
                $query->where('risk_score', '>', 70);
            } elseif ($request->risk_level == 'medium') {
                $query->whereBetween('risk_score', [40, 70]);
            } elseif ($request->risk_level == 'low') {
                $query->where('risk_score', '<', 40);
            }
        }
        
        $logs = $query->latest()->paginate(20);
        
        $stats = [
            'total' => Log::count(),
            'success' => Log::where('action_type', 'success')->count(),
            'fail' => Log::where('action_type', 'fail')->count(),
            'suspicious' => Log::where('action_type', 'suspicious')->count(),
            'high_risk' => Log::where('risk_score', '>', 70)->count(),
        ];
        
        return view('logs.index', compact('logs', 'stats'));
    }
    
    public function show(Log $log)
    {
        // ✅ تعديل: إزالة 'user' لأنها غير موجودة
        $log->load(['invitation.user', 'invitation.event']);
        return view('logs.show', compact('log'));
    }
}

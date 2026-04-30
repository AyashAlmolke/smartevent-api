<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من تسجيل دخول المشرف
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        // التحقق من صلاحية الوصول (نشط وليس محظور)
        if (!Auth::guard('admin')->user()->canAccessDashboard()) {
            abort(403, 'غير مصرح لك بالدخول إلى لوحة التحكم');
        }

        return $next($request);
    }
}

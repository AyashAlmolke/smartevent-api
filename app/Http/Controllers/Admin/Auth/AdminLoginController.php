<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller  // ✅ غير الاسم هنا
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
            $admin = Auth::guard('admin')->user();
            $admin->last_login_at = now();
            $admin->save();
            
            // ✅ إذا كان الطلب من API (Flutter)
            if ($request->expectsJson() || $request->is('api/*')) {
                $token = $admin->createToken('mobile_app')->plainTextToken;
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم تسجيل الدخول بنجاح',
                    'token' => $token,
                    'admin' => [
                        'id' => $admin->id,
                        'name' => $admin->name,
                        'email' => $admin->email,
                        'username' => $admin->username,
                    ]
                ]);
            }
            
            // ✅ إذا كان الطلب من الويب (Dashboard)
            return redirect()->intended(route('admin.dashboard'))->with('success', 'تم تسجيل الدخول بنجاح');
        }

        // ❌ فشل تسجيل الدخول
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'
            ], 401);
        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // ✅ إذا كان الطلب من API
        if ($request->expectsJson() || $request->is('api/*')) {
            if ($request->user('admin')) {
                $request->user('admin')->currentAccessToken()->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الخروج بنجاح'
            ]);
        }
        
        // ✅ إذا كان الطلب من الويب
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}

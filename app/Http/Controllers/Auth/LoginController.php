<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // منع الوصول المباشر للمشرفين المسجلين
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    // ============================================================
    // 📱 عرض صفحة تسجيل الدخول (للويب)
    // ============================================================
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // ============================================================
    // 🔐 معالجة طلب تسجيل الدخول (للويب + API)
    // ============================================================
    public function login(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        // محاولة تسجيل الدخول
        if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
            
            $admin = Auth::guard('admin')->user();
            
            // تحديث آخر وقت تسجيل دخول
            $admin->last_login_at = now();
            $admin->save();
            
            // ====================================================
            // 📝 إذا كان الطلب من API (Flutter App)
            // ====================================================
            if ($request->expectsJson() || $request->is('api/*')) {
                // إنشاء توكن للموبايل باستخدام Sanctum
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
            
            // ====================================================
            // 🌐 إذا كان الطلب من الويب (Dashboard)
            // ====================================================
            return redirect()->intended(route('admin.dashboard'))->with('success', 'تم تسجيل الدخول بنجاح');
        }

        // ====================================================
        // ❌ فشل تسجيل الدخول
        // ====================================================
        
        // إذا كان الطلب من API
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'
            ], 401);
        }
        
        // إذا كان الطلب من الويب
        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ])->onlyInput('email');
    }

    // ============================================================
    // 🚪 تسجيل الخروج
    // ============================================================
    public function logout(Request $request)
    {
        // إذا كان الطلب من API
        if ($request->expectsJson() || $request->is('api/*')) {
            // حذف التوكن إذا كان موجود
            if ($request->user('admin') && method_exists($request->user('admin'), 'tokens')) {
                $request->user('admin')->currentAccessToken()->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الخروج بنجاح'
            ]);
        }
        
        // إذا كان الطلب من الويب
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    // عرض صفحة الدخول
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    // معالجة عملية الدخول
    public function login(Request $request)
    {
        // 1. التحقق من البيانات المدخلة
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. محاولة تسجيل الدخول مع خاصية "تذكرني"
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // 3. إعادة توليد الجلسة لمنع هجمات Fixation
            $request->session()->regenerate();

            // 4. التوجيه إلى لوحة التحكم
            return redirect()->intended(route('admin.dashboard'));
        }

        // 5. في حال فشل الدخول
        throw ValidationException::withMessages([
            'email' => __('بيانات الاعتماد هذه لا تطابق سجلاتنا.'),
        ]);
    }

    // تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

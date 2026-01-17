<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class CustomerAuthController extends Controller
{
    // عرض صفحة التسجيل
    public function showRegister() {
        return view('frontend.auth.register'); // تأكد أن المسار يطابق مكان ملفك
    }

    public function register(Request $request) {
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name'  => 'required|string|max:255',
        'email'      => 'required|email|unique:users,email',
        'password'   => 'required|confirmed|min:6', // جعلناها أسهل للتجربة
    ]);

    $user = User::create([
        'first_name' => $request->first_name,
        'last_name'  => $request->last_name,
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'role'       => 'customer',
    ]);

    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->route('shop.index')->with('success', 'تم التسجيل بنجاح!');
}

    // عرض صفحة تسجيل الدخول
    public function showLogin() {
        return view('frontend.auth.login');
    }

    // تنفيذ عملية تسجيل الدخول
    public function login(Request $request) {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // محاولة الدخول والتأكد أن الحساب زبون وليس آدمن
        if (Auth::attempt($credentials + ['role' => 'customer'])) {
            $request->session()->regenerate();
            return redirect()->intended(route('shop.index'))->with('success', 'أهلاً بك مجدداً');
        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة السر غير صحيحة.',
        ])->onlyInput('email');
    }

    // تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('shop.index');
    }
}

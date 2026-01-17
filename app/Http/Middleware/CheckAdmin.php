<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // التحقق: إذا كان المستخدم مسجل دخول وهو مسؤول فعلاً
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return $next($request);
        }

        // إذا كان مسجل دخول كزبون عادي ويحاول دخول روابط الإدارة
        if (Auth::check()) {
            return redirect('/')->with('error', 'غير مسموح لك بالوصول للوحة التحكم.');
        }

        // إذا لم يكن مسجلاً أصلاً، يوجه لصفحة دخول الأدمن
        return redirect()->route('admin.login');
    }
}

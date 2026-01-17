<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
{
    public function handle(Request $request, Closure $next)
    {
        // التحقق: إذا كان المستخدم مسجل دخول ودوره هو 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // إذا كان زبوناً، يتم منعه وإرساله لصفحة المتجر مع رسالة تحذير
        return redirect('/')->with('error', 'عذراً، لا تملك صلاحيات الوصول لهذه الصفحة.');
    }
}
}

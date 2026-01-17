<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Order;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::useBootstrapFive();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       // مشاركة عدد الطلبات الجديدة مع السايدبار فقط
    View::composer('layouts.admin-sidebar', function ($view) {
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $view->with('pendingOrdersCount', $pendingOrdersCount);
    });

    Paginator::useBootstrapFive();
    }

    // إجبار الموقع على استخدام HTTPS في الاستضافة
    if (config('app.env') === 'production' || env('FORCE_HTTPS', true)) {
        URL::forceScheme('https');
    }
}

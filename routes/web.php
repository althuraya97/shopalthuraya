<?php

use Illuminate\Support\Facades\Route;

// استدعاء المتحكمات (Controllers)
use App\Http\Controllers\ProductController as AdminProductController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\CustomerAuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Frontend\CartController;

/*
|--------------------------------------------------------------------------
| واجهة المستخدم (Frontend - للزبائن والزوار)
|--------------------------------------------------------------------------
*/

// مسارات المتجر العامة
Route::get('/', [FrontendProductController::class, 'index'])->name('shop.index');
Route::get('/product/{id}', [FrontendProductController::class, 'show'])->name('shop.show');

// مسارات السلة (Cart)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
});

// مسارات الزوار (Guest) - التسجيل والدخول
Route::middleware('guest')->group(function () {
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('customer.register.post');
    
    // تم التحديث هنا ليطابق طلبك
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login.post');
});
// مسارات الزبائن المسجلين (Auth) - الطلبات وإتمام الشراء
Route::middleware('auth')->group(function () {
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

    // صفحة تفاصيل الشحن (Checkout)
    Route::get('/checkout', function () {
        return view('frontend.cart.checkout');
    })->name('checkout.index');

    // إدارة طلبات الزبون
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index'); // قائمة طلباتي
        Route::post('/store', [OrderController::class, 'store'])->name('store'); // حفظ الطلب
        Route::get('/{order}', [OrderController::class, 'show'])->name('show'); // تفاصيل طلب معين
        Route::get('/success/{id}', function($id) {
            return view('frontend.orders.success', ['order_id' => $id]);
        })->name('success');
    });
});
// مسار تحديث المخزون فقط
Route::patch('/admin/products/{product}/update-stock', [AdminProductController::class, 'updateStock'])
     ->name('admin.products.updateStock');
/*
|--------------------------------------------------------------------------
| لوحة تحكم الآدمن (Admin Dashboard)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| لوحة تحكم الآدمن (Admin Dashboard)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // 1. مسارات الدخول (متاحة للجميع ليتمكن المسؤول من تسجيل دخوله)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
Route::get('/orders/{order}/invoice', [OrderController::class, 'generateInvoice'])->name('orders.invoice');
    // 2. المسارات المحمية (تحتاج تسجيل دخول + صلاحية مسؤول)
    // نستخدم 'auth' للتأكد من تسجيل الدخول، و 'admin' (الذي سجلته في Kernel) للتأكد من أنه مسؤول
    Route::middleware(['auth'])->group(function () {

        // الصفحة الرئيسية للوحة التحكم
        Route::get('/dashboard', [OrderController::class, 'adminIndex'])->name('dashboard');

        // إدارة المنتجات
        Route::resource('products', AdminProductController::class);

        // مسارات خاصة بالمنتجات (تحديث سريع للمخزون وتبديل القسم)
        Route::patch('products/{product}/update-stock', [AdminProductController::class, 'updateStock'])
             ->name('products.updateStock');

        Route::post('products/{product}/toggle-category', [AdminProductController::class, 'toggleCategory'])
             ->name('products.toggleCategory');

        // إدارة التصنيفات (الفئات)
        Route::resource('categories', CategoryController::class);
        Route::post('/categories/main', [CategoryController::class, 'storeCategory'])->name('categories.main.store');
        Route::post('/categories/sub', [CategoryController::class, 'storeSubCategory'])->name('categories.sub.store');

        // إدارة طلبات الزبائن للآدمن
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'adminIndex'])->name('index');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('status.update');
        });
    });
});

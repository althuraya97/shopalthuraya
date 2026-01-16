<?php

use Illuminate\Support\Facades\Route;

// استدعاء المتحكمات بأسماء مستعارة لتجنب التضارب (Alias)
use App\Http\Controllers\ProductController as AdminProductController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\CustomerAuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Frontend\CartController;


/*use App\Http\Controllers\Frontend\CartController; // تأكد من وجود هذا السطر

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
|--------------------------------------------------------------------------
| واجهة المستخدم (Frontend - للزبائن)
|--------------------------------------------------------------------------
*/
// مسارات السلة (متاحة للجميع: زوار ومسجلين)
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index'); // عرض السلة
    Route::post('/add', [CartController::class, 'add'])->name('add'); // إضافة منتج
    Route::post('/update/{id}', [CartController::class, 'update'])->name('update'); // تحديث كمية
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove'); // حذف منتج
});
// مسارات عامة (المتجر وتفاصيل المنتج)
Route::get('/', [FrontendProductController::class, 'index'])->name('shop.index');
Route::get('/product/{id}', [FrontendProductController::class, 'show'])->name('shop.show');

// مسارات الزوار فقط (تسجيل جديد ودخول)
Route::middleware('guest')->group(function () {
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('customer.register.post');
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login.post');
});
// مسارات الزبائن
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
Route::get('/', [FrontendProductController::class, 'index'])->name('shop.index');
// مسارات الزبائن المسجلين فقط
Route::middleware('auth')->group(function () {
  Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

    // أضف هذا السطر هنا ليكون متاحاً للزبون
    Route::post('/order/store', [OrderController::class, 'store'])->name('orders.store');

    // مسار عرض تفاصيل الطلب بعد النجاح
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');});

/*
|--------------------------------------------------------------------------
| لوحة تحكم الآدمن (Admin Dashboard)
|--------------------------------------------------------------------------
*/

// 1. مسارات دخول الآدمن (خارج الحماية)
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// 2. مسارات لوحة التحكم المحمية (للمديرين فقط)
// ملاحظة: تأكد من أن اسم الـ Middleware في ملف Kernel هو 'CheckAdmin' أو 'admin'
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // الداشبورد والطلبات
    Route::get('/dashboard', [OrderController::class, 'index'])->name('dashboard');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'printInvoice'])->name('orders.invoice');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status.update');

    // إدارة الأقسام
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'index'])->name('categories.create');
    Route::post('/categories/main', [CategoryController::class, 'storeCategory'])->name('categories.main.store');
    Route::post('/categories/sub', [CategoryController::class, 'storeSubCategory'])->name('categories.sub.store');
    Route::put('/categories/{id}/{type}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}/{type}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // إدارة المنتجات
    Route::post('/products/{product}/toggle-category', [AdminProductController::class, 'toggleCategory'])->name('products.toggleCategory');
    Route::resource('products', AdminProductController::class);
    Route::post('/order/store', [OrderController::class, 'store'])->name('orders.store');
});

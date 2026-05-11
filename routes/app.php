<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Auth\ForgotPasswordController;
use App\Http\Controllers\App\Auth\LoginController;
use App\Http\Controllers\App\Auth\RegisterController;
use App\Http\Controllers\App\Auth\ResetPasswordController;
use App\Http\Controllers\App\CartController;
use App\Http\Controllers\App\ContactController;
use App\Http\Controllers\App\CouponController;
use App\Http\Controllers\App\HomeController;
use App\Http\Controllers\App\ShopController;
use App\Http\Controllers\App\StockNotificationController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.product');
Route::view('/about', 'app.pages.about')->name('about');
Route::view('/services', 'app.pages.services')->name('services');
Route::view('/blog', 'app.pages.blog')->name('blog');
Route::get('/contact', fn () => view('app.pages.contact'))->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{product:slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{cartKey}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{cartKey}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/stock-notify', [StockNotificationController::class, 'store'])->name('stock.notify');
Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
Route::delete('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');
Route::view('/checkout', 'app.pages.checkout')->name('checkout');
Route::post('/checkout', function () {
    return redirect('/thank-you');
});
Route::view('/thank-you', 'app.pages.thank-you')->name('thankyou');

// ── Kullanıcı Auth (/account/*) ────────────────────────────────
Route::prefix('account')->name('app.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login',    [LoginController::class, 'create'])->name('login');
        Route::post('/login',   [LoginController::class, 'store'])->name('login.store');

        Route::get('/register',  [RegisterController::class, 'create'])->name('register');
        Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

        Route::get('/forgot-password',  [ForgotPasswordController::class, 'create'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

        Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
        Route::post('/reset-password',        [ResetPasswordController::class, 'store'])->name('password.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    });
});

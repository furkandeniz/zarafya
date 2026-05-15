<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Auth\ForgotPasswordController;
use App\Http\Controllers\App\Auth\LoginController;
use App\Http\Controllers\App\Auth\RegisterController;
use App\Http\Controllers\App\Auth\ResetPasswordController;
use App\Http\Controllers\App\CartController;
use App\Http\Controllers\App\CheckoutController;
use App\Http\Controllers\App\ContactController;
use App\Http\Controllers\App\CouponController;
use App\Http\Controllers\App\BlogController;
use App\Http\Controllers\App\BlogCommentController;
use App\Http\Controllers\App\SitemapController;
use App\Http\Controllers\App\HomeController;
use App\Http\Controllers\App\ShopController;
use App\Http\Controllers\App\AccountController;
use App\Http\Controllers\App\StoreController;
use App\Http\Controllers\App\StockNotificationController;
use App\Http\Controllers\App\ReturnRequestController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/urunler', [ShopController::class, 'index'])->name('shop');
Route::get('/urunler/{product:slug}', [ShopController::class, 'show'])->name('shop.product');
Route::view('/hakkimizda', 'app.pages.about')->name('about');
Route::view('/hizmetlerimiz', 'app.pages.services')->name('services');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{blog:slug}/yorum', [BlogCommentController::class, 'store'])->name('blog.comment.store');
Route::get('/iletisim', fn () => view('app.pages.contact'))->name('contact');
Route::post('/iletisim', [ContactController::class, 'store'])->name('contact.store');
Route::get('/sepet', [CartController::class, 'index'])->name('cart');
Route::post('/sepet/ekle/{product:slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/sepet/guncelle/{cartKey}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/sepet/kaldir/{cartKey}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/sepet/temizle', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/stok-bildirimi', [StockNotificationController::class, 'store'])->name('stock.notify');
Route::post('/kupon/uygula', [CouponController::class, 'apply'])->name('coupon.apply');
Route::delete('/kupon/kaldir', [CouponController::class, 'remove'])->name('coupon.remove');
Route::get('/odeme/secim', [CheckoutController::class, 'gate'])->name('checkout.gate');
Route::post('/odeme/misafir', [CheckoutController::class, 'continueAsGuest'])->name('checkout.guest');
Route::get('/odeme', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/odeme', [CheckoutController::class, 'store'])->name('checkout.store');
Route::view('/tesekkurler', 'app.pages.thank-you')->name('thankyou');
Route::get('/magaza/{store:slug}', [StoreController::class, 'show'])->name('store.show');

// ── Kullanıcı Auth (/hesap/*) ────────────────────────────────
Route::prefix('hesap')->name('app.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/giris',    [LoginController::class, 'create'])->name('login');
        Route::post('/giris',   [LoginController::class, 'store'])->name('login.store');

        Route::get('/kayit',    [RegisterController::class, 'create'])->name('register');
        Route::post('/kayit',   [RegisterController::class, 'store'])->name('register.store');

        Route::get('/sifremi-unuttum',  [ForgotPasswordController::class, 'create'])->name('password.request');
        Route::post('/sifremi-unuttum', [ForgotPasswordController::class, 'store'])->name('password.email');

        Route::get('/sifre-sifirla/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
        Route::post('/sifre-sifirla',        [ResetPasswordController::class, 'store'])->name('password.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/cikis', [LoginController::class, 'destroy'])->name('logout');

        Route::get('/profilim',              [AccountController::class, 'dashboard'])->name('app.account.dashboard');
        Route::get('/siparislerim',          [AccountController::class, 'orders'])->name('app.account.orders');
        Route::get('/siparislerim/{order}',  [AccountController::class, 'orderDetail'])->name('app.account.order.detail');
        Route::get('/siparislerim/{order}/iade-talebi',  [ReturnRequestController::class, 'create'])->name('app.return-request.create');
        Route::post('/siparislerim/{order}/iade-talebi', [ReturnRequestController::class, 'store'])->name('app.return-request.store');
        Route::get('/bilgilerim',            [AccountController::class, 'profile'])->name('app.account.profile');
        Route::patch('/bilgilerim',          [AccountController::class, 'updateProfile'])->name('app.account.profile.update');
        Route::get('/sifre',                 [AccountController::class, 'password'])->name('app.account.password');
        Route::patch('/sifre',               [AccountController::class, 'updatePassword'])->name('app.account.password.update');
    });
});

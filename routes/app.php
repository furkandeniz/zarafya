<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', fn () => view('app.pages.shop'))->name('shop');
Route::view('/about', 'app.pages.about')->name('about');
Route::view('/services', 'app.pages.services')->name('services');
Route::view('/blog', 'app.pages.blog')->name('blog');
Route::view('/contact', 'app.pages.contact')->name('contact');
Route::view('/cart', 'app.pages.cart')->name('cart');
Route::view('/checkout', 'app.pages.checkout')->name('checkout');
Route::post('/checkout', function () {
    return redirect('/thank-you');
});
Route::view('/thank-you', 'app.pages.thank-you')->name('thankyou');

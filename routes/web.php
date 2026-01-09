<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/login', function () {
    return view('admin.auth.login');
})->name('login');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});


Route::get('/forgot-password', function () {
    return view('admin.auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function () {
    // normalde mail gönderilir
    return back()->with('status', 'Password reset link sent!');
})->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('admin.auth.reset-password', [
        'request' => request(),
        'token' => $token,
    ]);
})->name('password.reset');

Route::post('/reset-password', function () {
    // normalde password update edilir
    return redirect('/login')->with('status', 'Password reset successful!');
})->name('password.store');


require __DIR__.'/auth.php';

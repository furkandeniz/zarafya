<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('admin.pages.dashboard'))->name('dashboard');
    });

Route::get('/dashboard', fn() => redirect()->route('admin.dashboard'))
    ->middleware(['auth'])
    ->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::resource('products', ProductController::class);
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('orders', OrderController::class)->only(['index', 'update']);

});
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/balance', fn () => view('admin.pages.balance'))->name('balance.index');
});

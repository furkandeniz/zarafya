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

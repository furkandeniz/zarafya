<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

// routes/admin.php
Route::get('/dashboard', fn () => view('admin.pages.dashboard'))
    ->name('admin.dashboard');


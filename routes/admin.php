<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCommentController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\BalanceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StockNotificationController;
use App\Http\Controllers\Admin\ReturnRequestController;

// ✅ Admin panel route'ları (tek yerde)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', SearchController::class)->name('search');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('coupons', CouponController::class)->except(['show']);
    Route::resource('stores', StoreController::class)->except(['show']);
    Route::resource('products', ProductController::class);
    Route::delete('products/{product}/images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::middleware('admin.only')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/resend-verification', [UserController::class, 'resendVerification'])->name('users.resend-verification');

        Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
        Route::get('contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
        Route::patch('contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
        Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    });

    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);

    Route::get('return-requests', [ReturnRequestController::class, 'index'])->name('return-requests.index');
    Route::get('return-requests/{returnRequest}', [ReturnRequestController::class, 'show'])->name('return-requests.show');
    Route::patch('return-requests/{returnRequest}/question', [ReturnRequestController::class, 'question'])->name('return-requests.question');
    Route::patch('return-requests/{returnRequest}/approve', [ReturnRequestController::class, 'approve'])->name('return-requests.approve');
    Route::patch('return-requests/{returnRequest}/reject', [ReturnRequestController::class, 'reject'])->name('return-requests.reject');

    Route::get('/balance', [BalanceController::class, 'index'])->name('balance.index');

    Route::get('stock-notifications', [StockNotificationController::class, 'index'])->name('stock-notifications.index');
    Route::delete('stock-notifications/{stockNotification}', [StockNotificationController::class, 'destroy'])->name('stock-notifications.destroy');

    Route::middleware('admin.only')->group(function () {
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::patch('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::patch('settings/blog-visit-count', [SettingController::class, 'updateBlogVisitCount'])->name('settings.blog-visit-count');

        Route::resource('blogs', BlogController::class)->except(['show']);
        Route::patch('blogs/{blog}/toggle-publish', [BlogController::class, 'togglePublish'])->name('blogs.toggle-publish');

        Route::get('blog-comments', [BlogCommentController::class, 'index'])->name('blog-comments.index');
        Route::patch('blog-comments/{comment}/approve', [BlogCommentController::class, 'approve'])->name('blog-comments.approve');
        Route::patch('blog-comments/{comment}/toggle-visibility', [BlogCommentController::class, 'toggleVisibility'])->name('blog-comments.toggle-visibility');
        Route::delete('blog-comments/{comment}', [BlogCommentController::class, 'destroy'])->name('blog-comments.destroy');
    });
});

// ✅ Root /dashboard -> admin dashboard
Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))
    ->middleware(['auth'])
    ->name('app.dashboard.redirect');


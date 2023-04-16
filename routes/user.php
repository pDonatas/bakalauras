<?php

declare(strict_types=1);

/** Shops */

use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\User\MarkController;
use App\Http\Controllers\User\Shop\BookmarkController;
use App\Http\Controllers\User\Shop\CompareController;
use App\Http\Controllers\User\Shop\ShopController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('shop/{id}', [ShopController::class, 'show'])->name('shop.show');
Route::get('shops/compare', [CompareController::class, 'index'])->name('shops.compare.index');
Route::middleware('auth')->group(function () {
    Route::post('shop/vote/{shop}', [MarkController::class, 'store'])->name('vote');
    Route::get('orders/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('orders/fail', [OrderController::class, 'fail'])->name('orders.fail');
    Route::get('orders/callback', [OrderController::class, 'callback'])->name('orders.callback');
    Route::get('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('orders/{service}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders/{service}', [OrderController::class, 'store'])->name('orders.store');

    Route::get('user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/user/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/user/profile/edit', [UserController::class, 'update'])->name('profile.update');
    Route::get('user/orders', [UserController::class, 'orders'])->name('user.orders');

    Route::get('shop/bookmark/{shop}', [BookmarkController::class, 'create'])->name('bookmark.create');
    Route::get('shop/unbookmark/{shop}', [BookmarkController::class, 'destroy'])->name('bookmark.destroy');
});

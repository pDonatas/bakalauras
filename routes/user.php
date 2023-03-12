<?php

/** Shops */

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\User\MarkController;
use App\Http\Controllers\User\Shop\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('shop/{id}', [ShopController::class, 'show'])->name('shop.show');
Route::middleware('auth')->group(function() {
    Route::post('shop/vote/{shop}', [MarkController::class, 'store'])->name('vote');
    Route::get('orders/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('orders/fail', [OrderController::class, 'fail'])->name('orders.fail');
    Route::get('orders/callback', [OrderController::class, 'callback'])->name('orders.callback');
    Route::get('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('orders/{service}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders/{service}', [OrderController::class, 'store'])->name('orders.store');

    Route::get('user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('user/orders', [UserController::class, 'orders'])->name('user.orders');
});

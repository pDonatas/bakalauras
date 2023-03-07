<?php

/** Shops */

use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\User\MarkController;
use App\Http\Controllers\User\Shop\ShopController;
use Illuminate\Support\Facades\Route;

Route::resource('shop', ShopController::class);
Route::post('shop/vote/{shop}', [MarkController::class, 'store'])->name('vote');
Route::get('orders/{service}', [OrderController::class, 'create'])->name('orders.create');
Route::post('orders/{service}', [OrderController::class, 'store'])->name('orders.store');

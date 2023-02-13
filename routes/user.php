<?php

/** Shops */

use App\Http\Controllers\User\MarkController;
use App\Http\Controllers\User\Shop\ShopController;

Route::resource('shop', ShopController::class);
Route::post('vote', [MarkController::class, 'store'])->name('vote');

<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', "role:".User::ROLE_BARBER])->group(function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::resource('/', AdminController::class);
        Route::resource('users', UserController::class)->names('users');
        Route::resource('shops', ShopController::class)->names('shops');
    });
});

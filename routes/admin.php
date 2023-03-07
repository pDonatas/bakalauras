<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () { // TODO: create middleware for barber and admin
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::resource('/', AdminController::class);
        Route::resource('users', UserController::class)->names('users');
        Route::resource('shops', ShopController::class)->names('shops');
        Route::resource('shops.services', ServiceController::class)->names('services');
        Route::resource('shops.pages', PageController::class)->names('pages');
    });
});

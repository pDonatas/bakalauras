<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrdersController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:1'])->group(function () { // TODO: create middleware for barber and admin
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::resource('/', AdminController::class);
        Route::resource('users', UserController::class)->names('users');
        Route::resource('shops', ShopController::class)->names('shops');
        Route::resource('shops.services', ServiceController::class)->names('services');
        Route::resource('shops.pages', PageController::class)->names('pages');
        Route::resource('orders', OrdersController::class)->names('orders');
        Route::resource('calendar', CalendarController::class)->names('calendar');
    });
});

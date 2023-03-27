<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PhotoController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::middleware(['role:1'])->group(function () {
            Route::resource('/', AdminController::class);
            Route::resource('shops', ShopController::class)->names('shops');
            Route::resource('shops.services', ServiceController::class)->names('services');
            Route::resource('shops.pages', PageController::class)->names('pages');
            Route::resource('calendar', CalendarController::class)->names('calendar');
            Route::resource('orders', OrdersController::class)->names('orders');
            Route::resource('shops.services.photos', PhotoController::class)->names('photos');
        });

        Route::middleware(['role:2'])->group(function () {
            Route::resource('users', UserController::class)->names('users');
            Route::resource('categories', CategoryController::class)->names('categories');
        });
    });
});

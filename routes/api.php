<?php

declare(strict_types=1);

use App\Http\Controllers\AIController;
use App\Http\Controllers\User\Shop\CompareController;
use App\Http\Controllers\User\Shop\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('services/{service}/photos', [ServiceController::class, 'photos'])->name('shop.photos');
Route::post('shops/compare', [CompareController::class, 'compare'])->name('shops.compare');
Route::post('services/{service}/time', [ServiceController::class, 'time'])->name('services.time');
Route::post('ai', [AIController::class, 'generateImage'])->name('ai');

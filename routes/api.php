<?php

declare(strict_types=1);

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

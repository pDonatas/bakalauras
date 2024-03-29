<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Main\MainController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [MainController::class, 'index'])->name('index');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';

Route::post('appointments_ajax_update', [CalendarController::class, 'updateAjax'])->name('appointments_ajax_update');
Route::post('ai', [AIController::class, 'generateImage'])->name('ai');
Route::get('locale/{locale}', [LocaleController::class, 'index'])->name('locale');

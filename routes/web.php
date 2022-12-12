<?php

use App\Http\Controllers\LuckyPageController;
use App\Http\Controllers\RegisterController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/register', [RegisterController::class, 'index']);


Route::group(['middleware' => 'check_user'], function () {
    Route::get('/new_unique_link/{token}', [LuckyPageController::class, 'new_link']);
    Route::get('/deactivate/{token}', [LuckyPageController::class, 'deactivate']);

    Route::group(['prefix' => '/lucky_page'], function () {
        Route::get('/{token}', [LuckyPageController::class, 'index'])->name('lucky_page');
        Route::get('/random/{token}/{luckyNumber}', [LuckyPageController::class, 'random']);
        Route::get('/history/{token}', [LuckyPageController::class, 'history']);
    });
});

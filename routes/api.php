<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ContactPageController;
use App\Http\Controllers\GlobalSettingController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('panel')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    #contact
    Route::get('/contact', [ContactPageController::class, 'index']);
    Route::post('/sendmail', [ContactPageController::class, 'sendMail']);

    #global settings
    Route::get('/setting', [GlobalSettingController::class, 'index']);

    #Home
    Route::get('/home', [HomePageController::class, 'index']);

    Route::middleware(['permission:ViewPanel'])->group(function () {
        Route::prefix('panel')->group(function () {
            #contact
            Route::post('/contact', [ContactPageController::class, 'save']);

            #global settings
            Route::post('/setting', [GlobalSettingController::class, 'save']);

            #home
            Route::post('/home', [HomePageController::class, 'save']);
        });
    });
});

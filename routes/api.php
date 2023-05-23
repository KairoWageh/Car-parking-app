<?php

use App\Http\Controllers\Api\V1\{
    VehicleController,
    ZoneController
};

use App\Http\Controllers\Api\V1\Auth\{LoginController,
    LogoutController,
    PasswordUpdateController,
    ProfileController,
    RegisterController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', RegisterController::class);
    Route::post('login', LoginController::class);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::put('password', PasswordUpdateController::class);
    Route::post('logout', LogoutController::class);
    Route::apiResource('vehicles', VehicleController::class);
});

Route::get('zones', [ZoneController::class, 'index']);

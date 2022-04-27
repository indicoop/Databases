<?php

use App\Http\Controllers\CooperativeController;
use App\Http\Controllers\UserController;
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

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

// cooperatives public
Route::get('all-coperatives', [CooperativeController::class, 'fetchActiveCooperatives']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('update', [UserController::class, 'update']);

    // cooperative
    Route::get('cooperatives', [CooperativeController::class, 'fetch']);
    Route::post('update-cooperative', [CooperativeController::class, 'update']);
});
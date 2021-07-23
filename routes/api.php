<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BallotController;
use App\Http\Controllers\LotteryController;
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

// Common routes
Route::post("/login", [AuthController::class, "login"]);
Route::middleware('auth:sanctum')->get("/logout", [AuthController::class, "logout"]);
Route::post("/register", [AuthController::class, "register"]);

// Admin Routes

Route::group(['middleware' => ['auth:sanctum', 'auth.admin'], 'prefix' => 'resource-management'], function () {
    Route::resource('lotteries', LotteryController::class);
//    Route::resource('profiles', ProfileController::class);
//    Route::resource('users', UserController::class);
    Route::resource('ballots', BallotController::class);
});

// Participant Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('lotteries/{lottery}/register', [LotteryController::class, 'register']);
});

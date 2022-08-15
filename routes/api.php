<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransferController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('user')->group(function() {
    Route::post('/register', [UserController::class, 'register']);
});

Route::prefix('auth')->group(function() {
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});


Route::prefix('transfer')->group(function() {
    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('/make', [TransferController::class, 'initTransfer']);
    });
});

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('teste', function () {
        return 'api is working';
    });
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CountController;

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/user/name', [UserController::class, 'name']);
    Route::post('/user/password', [UserController::class, 'password']);
    Route::post('/user/delete', [UserController::class, 'delete']);

    //カウント
    Route::post('/count/create', [CountController::class, 'create']);
    Route::post('/count/update', [CountController::class, 'update']);
    Route::post('/count/delete', [CountController::class, 'delete']);
    Route::get('/count/list', [CountController::class, 'list']);
    Route::get('/count/{id}', [CountController::class, 'view']);

    //順番
    Route::post('/order/update', [CountController::class, 'order']);
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

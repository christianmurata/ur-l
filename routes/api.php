<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UserController, UrlController, AuthController};

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

/* Unprotected routes*/
Route::get('/', function () { return 'API ur-l'; });

Route::post('/register', [UserController::class, 'store']);
Route::post('/auth/login', [AuthController::class, 'login']);

/* Protected routes*/
Route::group(['middleware' => ['auth.jwt']], function () {
    Route::get('/urls', [UrlController::class, 'index']);
    Route::get('/urls/{url}', [UrlController::class, 'show']);
    Route::post('/urls', [UrlController::class, 'store']);
    Route::patch('/urls/{url}', [UrlController::class, 'update']);
    Route::delete('/urls/{url}', [UrlController::class, 'destroy']);

    Route::get('/users', [UserController::class, 'index']);

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
});

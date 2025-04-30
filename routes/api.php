<?php

use App\Http\Controllers\PassportAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

/*
|--------------------------------------------------------------------------
| Authenticación
|--------------------------------------------------------------------------
*/

Route::get('/register', [PassportAuthController::class, 'register']);

Route::post('login', [PassportAuthController::class, 'login']);

Route::post('/password/email', [PassportAuthController::class, 'sendResetLink']);

Route::post('/password/reset', [PassportAuthController::class, 'passwordReset']);

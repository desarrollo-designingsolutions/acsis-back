<?php

use App\Http\Controllers\CacheController;
use App\Models\User;
use App\Notifications\BellNotification;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $user = User::first();

    // Enviar notificación
    $user->notify(new BellNotification([
        'title' => "hola",
        'subtitle' => "chao",
    ]));

    return view('welcome');
});

Route::get('/cache-keys', [CacheController::class, 'listCacheKeys']);
Route::get('/cache-clear', [CacheController::class, 'clearAllCache']);

<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas

/*
|--------------------------------------------------------------------------
| Service
|--------------------------------------------------------------------------
*/

Route::get('/service/paginate', [ServiceController::class, 'paginate']);

Route::get('/service/create', [ServiceController::class, 'create']);

Route::post('/service/store', [ServiceController::class, 'store']);

Route::get('/service/{id}/edit', [ServiceController::class, 'edit']);

Route::post('/service/update/{id}', [ServiceController::class, 'update']);

Route::delete('/service/delete/{id}', [ServiceController::class, 'delete']);

Route::post('/service/changeStatus', [ServiceController::class, 'changeStatus']);

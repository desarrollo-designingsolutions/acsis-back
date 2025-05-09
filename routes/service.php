<?php

use App\Http\Controllers\OtherServiceController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas
Route::middleware(['check.permission:menu.invoice'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Service
    |--------------------------------------------------------------------------
    */

    Route::get('/service/paginate', [ServiceController::class, 'paginate']);

    Route::delete('/service/delete/{id}', [ServiceController::class, 'delete']);

    Route::get('/service/loadBtnCreate', [ServiceController::class, 'loadBtnCreate']);

    /*
    |--------------------------------------------------------------------------
    | OtherService
    |--------------------------------------------------------------------------
    */

    Route::get('/service/otherService/create', [OtherServiceController::class, 'create']);

    Route::post('/service/otherService/store', [OtherServiceController::class, 'store']);

    Route::get('/service/otherService/{service_id}/edit', [OtherServiceController::class, 'edit']);

    Route::post('/service/otherService/update/{id}', [OtherServiceController::class, 'update']);
});

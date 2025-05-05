<?php

use App\Http\Controllers\EntityController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas
Route::middleware(['check.permission:menu.entity'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Entity
    |--------------------------------------------------------------------------
    */

    Route::get('/entity/paginate', [EntityController::class, 'paginate']);

    Route::get('/entity/create', [EntityController::class, 'create']);

    Route::post('/entity/store', [EntityController::class, 'store']);

    Route::get('/entity/{id}/edit', [EntityController::class, 'edit']);

    Route::post('/entity/update/{id}', [EntityController::class, 'update']);

    Route::delete('/entity/delete/{id}', [EntityController::class, 'delete']);

    Route::post('/entity/changeStatus', [EntityController::class, 'changeStatus']);

    Route::get('/entity/excelExport', [EntityController::class, 'excelExport']);

    Route::get('/entity/getNit/{id}', [EntityController::class, 'getNit']);
});

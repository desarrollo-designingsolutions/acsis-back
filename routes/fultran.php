<?php

use App\Http\Controllers\FultranController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas
Route::middleware(['check.permission:menu.invoice'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Fultran
    |--------------------------------------------------------------------------
    */

    Route::get('/fultran/paginate', [FultranController::class, 'paginate']);

    Route::get('/fultran/create/{invoice_id}', [FultranController::class, 'create']);

    Route::post('/fultran/store', [FultranController::class, 'store']);

    Route::get('/fultran/{id}/edit', [FultranController::class, 'edit']);

    Route::post('/fultran/update/{id}', [FultranController::class, 'update']);

    Route::delete('/fultran/delete/{id}', [FultranController::class, 'delete']);
});

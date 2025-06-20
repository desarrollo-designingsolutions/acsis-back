<?php

use App\Http\Controllers\FurtranController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas
Route::middleware(['check.permission:menu.invoice'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Furtran
    |--------------------------------------------------------------------------
    */

    Route::get('/furtran/paginate', [FurtranController::class, 'paginate']);

    Route::get('/furtran/create/{invoice_id}', [FurtranController::class, 'create']);

    Route::post('/furtran/store', [FurtranController::class, 'store']);

    Route::get('/furtran/{id}/edit', [FurtranController::class, 'edit']);

    Route::post('/furtran/update/{id}', [FurtranController::class, 'update']);

    Route::delete('/furtran/delete/{id}', [FurtranController::class, 'delete']);

    Route::get('/furtran/{invoice_id}/pdf', [FurtranController::class, 'pdf']);

    Route::get('/furtran/downloadTxt/{id}', [FurtranController::class, 'downloadTxt']);
});

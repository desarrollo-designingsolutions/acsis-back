<?php

use App\Http\Controllers\GlosaController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas
Route::middleware(['check.permission:menu.invoice'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Glosa
    |--------------------------------------------------------------------------
    */

    Route::get('/glosa/paginate', [GlosaController::class, 'paginate']);

    Route::get('/glosa/create', [GlosaController::class, 'create']);

    Route::post('/glosa/store', [GlosaController::class, 'store']);

    Route::get('/glosa/{id}/edit', [GlosaController::class, 'edit']);

    Route::get('/glosa/{id}/show', [GlosaController::class, 'show']);

    Route::post('/glosa/update/{id}', [GlosaController::class, 'update']);

    Route::delete('/glosa/delete/{id}', [GlosaController::class, 'delete']);

    Route::get('/glosa/createMasive', [GlosaController::class, 'createMasive']);

    Route::post('/glosa/storeMasive', [GlosaController::class, 'storeMasive']);
});

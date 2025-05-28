<?php

use App\Http\Controllers\GlosaAnswerController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas
Route::middleware(['check.permission:menu.invoice'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | GlosaAnswer
    |--------------------------------------------------------------------------
    */

    Route::get('/glosaAnswer/paginate', [GlosaAnswerController::class, 'paginate']);

    Route::get('/glosaAnswer/create', [GlosaAnswerController::class, 'create']);

    Route::post('/glosaAnswer/store', [GlosaAnswerController::class, 'store']);

    Route::get('/glosaAnswer/{id}/edit', [GlosaAnswerController::class, 'edit']);

    Route::get('/glosaAnswer/{id}/show', [GlosaAnswerController::class, 'show']);

    Route::post('/glosaAnswer/update/{id}', [GlosaAnswerController::class, 'update']);

    Route::delete('/glosaAnswer/delete/{id}', [GlosaAnswerController::class, 'delete']);

    Route::get('/glosaAnswer/createMasive', [GlosaAnswerController::class, 'createMasive']);

    Route::post('/glosaAnswer/storeMasive', [GlosaAnswerController::class, 'storeMasive']);
});

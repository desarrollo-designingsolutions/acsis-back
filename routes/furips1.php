<?php

use App\Http\Controllers\Furips1Controller;
use Illuminate\Support\Facades\Route;

// Rutas protegidas
Route::middleware(['check.permission:menu.invoice'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Furips1
    |--------------------------------------------------------------------------
    */

    Route::get('/furips1/paginate', [Furips1Controller::class, 'paginate']);

    Route::get('/furips1/create/{invoice_id}', [Furips1Controller::class, 'create']);

    Route::post('/furips1/store', [Furips1Controller::class, 'store']);

    Route::get('/furips1/{id}/edit', [Furips1Controller::class, 'edit']);

    Route::post('/furips1/update/{id}', [Furips1Controller::class, 'update']);

    Route::delete('/furips1/delete/{id}', [Furips1Controller::class, 'delete']);

    Route::get('/furips1/pdf/{id}', [Furips1Controller::class, 'pdf']);
});

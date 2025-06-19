<?php

use App\Http\Controllers\Furips2Controller;
use Illuminate\Support\Facades\Route;

// Rutas protegidas
Route::middleware(['check.permission:menu.invoice'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Furips2
    |--------------------------------------------------------------------------
    */

    Route::get('/furips2/paginate', [Furips2Controller::class, 'paginate']);

    Route::get('/furips2/create/{invoice_id}', [Furips2Controller::class, 'create']);

    Route::post('/furips2/store', [Furips2Controller::class, 'store']);

    Route::get('/furips2/{id}/edit', [Furips2Controller::class, 'edit']);

    Route::post('/furips2/update/{id}', [Furips2Controller::class, 'update']);

    Route::delete('/furips2/delete/{id}', [Furips2Controller::class, 'delete']);

    Route::get('/furips2/downloadTxt/{id}', [Furips2Controller::class, 'downloadTxt']);
});

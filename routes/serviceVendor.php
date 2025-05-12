<?php

use App\Http\Controllers\ServiceVendorController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas
Route::middleware(['check.permission:serviceVendor.list'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ServiceVendor
    |--------------------------------------------------------------------------
    */

    Route::get('/serviceVendor/paginate', [ServiceVendorController::class, 'paginate']);

    Route::get('/serviceVendor/create', [ServiceVendorController::class, 'create']);

    Route::post('/serviceVendor/store', [ServiceVendorController::class, 'store']);

    Route::get('/serviceVendor/{id}/edit', [ServiceVendorController::class, 'edit']);

    Route::post('/serviceVendor/update/{id}', [ServiceVendorController::class, 'update']);

    Route::delete('/serviceVendor/delete/{id}', [ServiceVendorController::class, 'delete']);

    Route::post('/serviceVendor/changeStatus', [ServiceVendorController::class, 'changeStatus']);

    Route::get('/serviceVendor/excelExport', [ServiceVendorController::class, 'excelExport']);
});

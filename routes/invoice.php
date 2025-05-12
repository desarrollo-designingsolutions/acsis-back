<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas
Route::middleware(['check.permission:menu.invoice'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Invoice
    |--------------------------------------------------------------------------
    */

    Route::get('/invoice/paginate', [InvoiceController::class, 'paginate']);

    Route::get('/invoice/create', [InvoiceController::class, 'create']);

    Route::post('/invoice/store', [InvoiceController::class, 'store']);

    Route::get('/invoice/{id}/edit', [InvoiceController::class, 'edit']);

    Route::post('/invoice/update/{id}', [InvoiceController::class, 'update']);

    Route::delete('/invoice/delete/{id}', [InvoiceController::class, 'delete']);

    Route::get('/invoice/excelExport', [InvoiceController::class, 'excelExport']);

    Route::get('/invoice/loadBtnCreate', [InvoiceController::class, 'loadBtnCreate']);

    Route::post('/invoice/validateInvoiceNumber', [InvoiceController::class, 'validateInvoiceNumber']);

    Route::post('/invoice/getInfoJson', [InvoiceController::class, 'getInfoJson']);
});

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

    Route::delete('/invoice/delete/{id}', [InvoiceController::class, 'delete']);

    Route::post('/invoice/changeStatus', [InvoiceController::class, 'changeStatus']);

    Route::get('/invoice/excelExport', [InvoiceController::class, 'excelExport']);

    Route::get('/invoice/loadBtnCreate', [InvoiceController::class, 'loadBtnCreate']);

    Route::post('/invoice/validateInvoiceNumber', [InvoiceController::class, 'validateInvoiceNumber']);

    Route::post('/invoice/getInfoJson', [InvoiceController::class, 'getInfoJson']);

    /*
    |--------------------------------------------------------------------------
    | INVOICE_TYPE_001 - Evento
    |--------------------------------------------------------------------------
    */

    Route::get('/invoice/INVOICE_TYPE_001/create', [InvoiceController::class, 'createType001']);

    Route::post('/invoice/INVOICE_TYPE_001/store', [InvoiceController::class, 'storeType001']);

    Route::get('/invoice/INVOICE_TYPE_001/{id}/edit', [InvoiceController::class, 'editType001']);

    Route::post('/invoice/INVOICE_TYPE_001/update/{id}', [InvoiceController::class, 'updateType001']);

    /*
    |--------------------------------------------------------------------------
    | INVOICE_TYPE_002 - Soat
    |--------------------------------------------------------------------------
    */

    Route::get('/invoice/INVOICE_TYPE_002/create', [InvoiceController::class, 'createType002']);

    Route::post('/invoice/INVOICE_TYPE_002/store', [InvoiceController::class, 'storeType002']);

    Route::get('/invoice/INVOICE_TYPE_002/{id}/edit', [InvoiceController::class, 'editType002']);

    Route::post('/invoice/INVOICE_TYPE_002/update/{id}', [InvoiceController::class, 'updateType002']);
});

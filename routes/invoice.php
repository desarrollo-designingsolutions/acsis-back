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

    Route::post('/invoice/validateInvoiceNumber', [InvoiceController::class, 'validateInvoiceNumber']);

    Route::get('/invoice/downloadJson/{id}', [InvoiceController::class, 'downloadJson']);

    Route::post('/invoice/uploadXml', [InvoiceController::class, 'uploadXml']);

    Route::get('/invoice/showErrorsValidationXml/{id}', [InvoiceController::class, 'showErrorsValidationXml']);

    Route::get('/invoice/excelErrorsValidationXml/{id}', [InvoiceController::class, 'excelErrorsValidation']);

    Route::get('/invoice/dataUrgeHosBorn/{id}', [InvoiceController::class, 'dataUrgeHosBorn']);

    Route::get('/invoice/downloadZip/{id}', [InvoiceController::class, 'downloadZip']);

    Route::post('/invoice/uploadJson', [InvoiceController::class, 'uploadJson']);

    Route::post('/invoice/jsonToForm', [InvoiceController::class, 'jsonToForm']);
});

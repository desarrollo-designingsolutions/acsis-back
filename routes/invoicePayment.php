<?php

use App\Http\Controllers\InvoicePaymentController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas
Route::middleware(['check.permission:menu.invoice'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | InvoicePayment
    |--------------------------------------------------------------------------
    */

    Route::get('/invoicePayment/paginate', [InvoicePaymentController::class, 'paginate']);

    Route::get('/invoicePayment/create/{invoice_id}', [InvoicePaymentController::class, 'create']);

    Route::post('/invoicePayment/store', [InvoicePaymentController::class, 'store']);

    Route::get('/invoicePayment/{id}/edit', [InvoicePaymentController::class, 'edit']);

    Route::post('/invoicePayment/update/{id}', [InvoicePaymentController::class, 'update']);

    Route::delete('/invoicePayment/delete/{id}', [InvoicePaymentController::class, 'delete']);
});

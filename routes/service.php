<?php

use App\Http\Controllers\MedicalConsultationController;
use App\Http\Controllers\OtherServiceController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas
Route::middleware(['check.permission:menu.invoice'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Service
    |--------------------------------------------------------------------------
    */

    Route::get('/service/paginate', [ServiceController::class, 'paginate']);

    Route::delete('/service/delete/{id}', [ServiceController::class, 'delete']);

    Route::get('/service/loadBtnCreate', [ServiceController::class, 'loadBtnCreate']);

    /*
    |--------------------------------------------------------------------------
    | OtherService
    |--------------------------------------------------------------------------
    */

    Route::get('/service/otherService/create', [OtherServiceController::class, 'create']);

    Route::post('/service/otherService/store', [OtherServiceController::class, 'store']);

    Route::get('/service/otherService/{service_id}/edit', [OtherServiceController::class, 'edit']);

    Route::post('/service/otherService/update/{id}', [OtherServiceController::class, 'update']);

    /*
    |--------------------------------------------------------------------------
    | MedicalConsultation
    |--------------------------------------------------------------------------
    */

    Route::get('/service/medicalConsultation/create', [MedicalConsultationController::class, 'create']);

    Route::post('/service/medicalConsultation/store', [MedicalConsultationController::class, 'store']);

    Route::get('/service/medicalConsultation/{service_id}/edit', [MedicalConsultationController::class, 'edit']);

    Route::post('/service/medicalConsultation/update/{id}', [MedicalConsultationController::class, 'update']);

    /*
    |--------------------------------------------------------------------------
    | Procedure
    |--------------------------------------------------------------------------
    */

    Route::get('/service/procedure/create', [ProcedureController::class, 'create']);

    Route::post('/service/procedure/store', [ProcedureController::class, 'store']);

    Route::get('/service/procedure/{service_id}/edit', [ProcedureController::class, 'edit']);

    Route::post('/service/procedure/update/{id}', [ProcedureController::class, 'update']);
});

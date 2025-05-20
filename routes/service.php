<?php

use App\Http\Controllers\HospitalizationController;
use App\Http\Controllers\MedicalConsultationController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\NewlyBornController;
use App\Http\Controllers\OtherServiceController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UrgencyController;
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

    /*
    |--------------------------------------------------------------------------
    | Urgency
    |--------------------------------------------------------------------------
    */

    Route::get('/service/urgency/create', [UrgencyController::class, 'create']);

    Route::post('/service/urgency/store', [UrgencyController::class, 'store']);

    Route::get('/service/urgency/{service_id}/edit', [UrgencyController::class, 'edit']);

    Route::post('/service/urgency/update/{id}', [UrgencyController::class, 'update']);

    /*
    |--------------------------------------------------------------------------
    | Medicine
    |--------------------------------------------------------------------------
    */

    Route::get('/service/medicine/create', [MedicineController::class, 'create']);

    Route::post('/service/medicine/store', [MedicineController::class, 'store']);

    Route::get('/service/medicine/{service_id}/edit', [MedicineController::class, 'edit']);

    Route::post('/service/medicine/update/{id}', [MedicineController::class, 'update']);

    /*
    |--------------------------------------------------------------------------
    | Hospitalization
    |--------------------------------------------------------------------------
    */

    Route::get('/service/hospitalization/create', [HospitalizationController::class, 'create']);

    Route::post('/service/hospitalization/store', [HospitalizationController::class, 'store']);

    Route::get('/service/hospitalization/{service_id}/edit', [HospitalizationController::class, 'edit']);

    Route::post('/service/hospitalization/update/{id}', [HospitalizationController::class, 'update']);

    /*
    |--------------------------------------------------------------------------
    | NewlyBorn
    |--------------------------------------------------------------------------
    */

    Route::get('/service/newlyBorn/create', [NewlyBornController::class, 'create']);

    Route::post('/service/newlyBorn/store', [NewlyBornController::class, 'store']);

    Route::get('/service/newlyBorn/{service_id}/edit', [NewlyBornController::class, 'edit']);

    Route::post('/service/newlyBorn/update/{id}', [NewlyBornController::class, 'update']);
});

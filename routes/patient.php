<?php

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['check.permission:menu.patient'])->group(function () {

/*
|--------------------------------------------------------------------------
| Patient
|--------------------------------------------------------------------------
*/

    Route::get('/patient/paginate', [PatientController::class, 'paginate']);

    Route::get('/patient/create', [PatientController::class, 'create']);

    Route::post('/patient/store', [PatientController::class, 'store']);

    Route::get('/patient/{id}/edit', [PatientController::class, 'edit']);

    Route::post('/patient/update/{id}', [PatientController::class, 'update']);

    Route::delete('/patient/delete/{id}', [PatientController::class, 'delete']);

    Route::get('/patient/excelExport', [PatientController::class, 'excelExport']);
    
    Route::post('/patient/exportDataToPatientImportExcel', [PatientController::class, 'exportDataToPatientImportExcel']);
    
    Route::post('/patient/exportFormatPatientImportExcel', [PatientController::class, 'exportFormatPatientImportExcel']);
    
    Route::post('/patient/uploadXlsxPatient', [PatientController::class, 'uploadXlsxPatient']);
    
    Route::post('/patient/getContentJson', [PatientController::class, 'getContentJson']);

    Route::get('/patient/excelErrorsValidation', [PatientController::class, 'excelErrorsValidation']);

    Route::get('/patient/exportExcelErrorsValidation', [PatientController::class, 'exportExcelErrorsValidation']);
});

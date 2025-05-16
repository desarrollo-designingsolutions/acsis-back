<?php

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

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

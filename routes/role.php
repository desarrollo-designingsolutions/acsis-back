<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas
Route::middleware(['check.permission:menu.role'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Role
    |--------------------------------------------------------------------------
    */

    Route::get('/role/list', [RoleController::class, 'index']);

    Route::get('/role/create', [RoleController::class, 'create']);

    Route::post('/role', [RoleController::class, 'store']);

    Route::get('role/{role}/edit', [RoleController::class, 'edit']);

    Route::delete('role/{role}', [RoleController::class, 'destroy']);
});

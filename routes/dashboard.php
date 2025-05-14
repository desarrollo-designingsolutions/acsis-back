<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard/countAllData', [DashboardController::class, 'countAllData']);

Route::get('/dashboard/trend', [DashboardController::class, 'getInvoiceTrend']);

Route::get('/dashboard/status-distribution', [DashboardController::class, 'getStatusDistribution']);

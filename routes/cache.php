<?php

use App\Http\Controllers\CacheMetricsController;
use Illuminate\Support\Facades\Route;

Route::get('/cache/hitRate', [CacheMetricsController::class, 'hitRate']);

Route::get('/cache/responseTime', [CacheMetricsController::class, 'responseTime']);

Route::get('/cache/requestVolume', [CacheMetricsController::class, 'requestVolume']);

Route::get('/cache/redisStats', [CacheMetricsController::class, 'redisStats']);

Route::get('/cache/getTables', [CacheMetricsController::class, 'getTables']);

Route::post('/cache/synchronizeTables', [CacheMetricsController::class, 'synchronizeTables']);

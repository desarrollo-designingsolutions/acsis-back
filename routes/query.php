<?php

use App\Http\Controllers\QueryController;
use Illuminate\Support\Facades\Route;

// Lista de Pais, Departamentos y Ciudades
Route::post('/selectInfiniteCountries', [QueryController::class, 'selectInfiniteCountries']);
Route::get('/selectStates/{country_id}', [QueryController::class, 'selectStates']);
Route::get('/selectCities/{state_id}', [QueryController::class, 'selectCities']);
Route::get('/selectCities/country/{country_id}', [QueryController::class, 'selectCitiesCountry']);
Route::get('/selectTypeEntity', [QueryController::class, 'selectTypeEntity']);
Route::post('/selectInfiniteEntities', [QueryController::class, 'selectInfiniteEntities']);
Route::post('/selectInfiniteServiceVendor', [QueryController::class, 'selectInfiniteServiceVendor']);
// Lista de Pais, Departamentos y Ciudades

// Route::post('/selectInifiniteInsurance', [QueryController::class, 'selectInifiniteInsurance']);
Route::post('/selectInfiniteCodeGlosa', [QueryController::class, 'selectInfiniteCodeGlosa']);


Route::get('/searchClient', [QueryController::class, 'searchClient']);

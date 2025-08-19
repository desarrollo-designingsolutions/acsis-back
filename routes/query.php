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
Route::post('/selectInfiniteTypeDocument', [QueryController::class, 'selectInfiniteTypeDocument']);
// Lista de Pais, Departamentos y Ciudades

// Route::post('/selectInifiniteInsurance', [QueryController::class, 'selectInifiniteInsurance']);

Route::get('/autoCompleteDataPatients', [QueryController::class, 'autoCompleteDataPatients']);
Route::post('/selectInfiniteCodeGlosa', [QueryController::class, 'selectInfiniteCodeGlosa']);
Route::post('/selectInfiniteCupsRips', [QueryController::class, 'selectInfiniteCupsRips']);
Route::post('/selectInfinitePatients', [QueryController::class, 'selectInfinitePatients']);
Route::post('/selectInfinitetipoNota', [QueryController::class, 'selectInfinitetipoNota']);

Route::post('/selectInfiniteTipoIdPisis', [QueryController::class, 'selectInfiniteTipoIdPisis']);
Route::post('/selectInfiniteTipoUsuario', [QueryController::class, 'selectInfiniteTipoUsuario']);
Route::post('/selectInfiniteSexo', [QueryController::class, 'selectInfiniteSexo']);
Route::post('/selectInfinitePais', [QueryController::class, 'selectInfinitePais']);
Route::post('/selectInfiniteMunicipio', [QueryController::class, 'selectInfiniteMunicipio']);
Route::post('/selectInfiniteDepartamento', [QueryController::class, 'selectInfiniteDepartamento']);
Route::post('/selectInfiniteZonaVersion2', [QueryController::class, 'selectInfiniteZonaVersion2']);

Route::post('/selectInfiniteTipoOtrosServicios', [QueryController::class, 'selectInfiniteTipoOtrosServicios']);
Route::post('/selectInfiniteConceptoRecaudo', [QueryController::class, 'selectInfiniteConceptoRecaudo']);

Route::post('/selectStatusInvoiceEnum', [QueryController::class, 'selectStatusInvoiceEnum']);

Route::post('/selectInfiniteRipsTipoDiagnosticoPrincipalVersion2', [QueryController::class, 'selectInfiniteRipsTipoDiagnosticoPrincipalVersion2']);
Route::post('/selectInfiniteRipsCausaExternaVersion2', [QueryController::class, 'selectInfiniteRipsCausaExternaVersion2']);
Route::post('/selectInfiniteCie10', [QueryController::class, 'selectInfiniteCie10']);
Route::post('/selectInfiniteRipsFinalidadConsultaVersion2', [QueryController::class, 'selectInfiniteRipsFinalidadConsultaVersion2']);
Route::post('/selectInfiniteServicio', [QueryController::class, 'selectInfiniteServicio']);
Route::post('/selectInfiniteGrupoServicio', [QueryController::class, 'selectInfiniteGrupoServicio']);
Route::post('/selectInfiniteModalidadAtencion', [QueryController::class, 'selectInfiniteModalidadAtencion']);
Route::post('/selectInfiniteViaIngresoUsuario', [QueryController::class, 'selectInfiniteViaIngresoUsuario']);
Route::post('/selectInfiniteTipoMedicamentoPosVersion2', [QueryController::class, 'selectInfiniteTipoMedicamentoPosVersion2']);
Route::post('/selectInfiniteUmm', [QueryController::class, 'selectInfiniteUmm']);
Route::post('/selectInfiniteCondicionyDestinoUsuarioEgreso', [QueryController::class, 'selectInfiniteCondicionyDestinoUsuarioEgreso']);
Route::post('/selectInfiniteIpsNoReps', [QueryController::class, 'selectInfiniteIpsNoReps']);

Route::post('/selectInfiniteIpsCodHabilitacion', [QueryController::class, 'selectInfiniteIpsCodHabilitacion']);

Route::post('/selectInfiniteInsuranceStatus', [QueryController::class, 'selectInfiniteInsuranceStatus']);

Route::post('/selectInfiniteTypeCodeGlosa', [QueryController::class, 'selectInfiniteTypeCodeGlosa']);

Route::post('/selectInfiniteCatalogoCum', [QueryController::class, 'selectInfiniteCatalogoCum']);

Route::post('/selectInfiniteUpr', [QueryController::class, 'selectInfiniteUpr']);
Route::post('/selectInfiniteFfm', [QueryController::class, 'selectInfiniteFfm']);
Route::post('/selectInfiniteDci', [QueryController::class, 'selectInfiniteDci']);
Route::post('/selectInfiniteIum', [QueryController::class, 'selectInfiniteIum']);

Route::post('/selectRgoResponseEnum', [QueryController::class, 'selectRgoResponseEnum']);
Route::post('/selectVictimConditionEnum', [QueryController::class, 'selectVictimConditionEnum']);
Route::post('/selectEventNatureEnum', [QueryController::class, 'selectEventNatureEnum']);
Route::post('/selectZoneEnum', [QueryController::class, 'selectZoneEnum']);
Route::post('/selectReferenceTypeEnum', [QueryController::class, 'selectReferenceTypeEnum']);
Route::post('/selectVehicleTypeEnum', [QueryController::class, 'selectVehicleTypeEnum']);
Route::post('/selectYesNoEnum', [QueryController::class, 'selectYesNoEnum']);
Route::post('/selectSurgicalComplexityEnum', [QueryController::class, 'selectSurgicalComplexityEnum']);
Route::post('/selectTransportServiceTypeEnum', [QueryController::class, 'selectTransportServiceTypeEnum']);
Route::post('/selectServiceTypeEnum', [QueryController::class, 'selectServiceTypeEnum']);
Route::post('/selectInfiniteDecreto780de2026', [QueryController::class, 'selectInfiniteDecreto780de2026']);

Route::post('/selectRgResponseEnum', [QueryController::class, 'selectRgResponseEnum']);
Route::post('/selectVehicleServiceTypeEnum', [QueryController::class, 'selectVehicleServiceTypeEnum']);
Route::post('/selectGenderEnum', [QueryController::class, 'selectGenderEnum']);
Route::post('/selectEventTypeEnum', [QueryController::class, 'selectEventTypeEnum']);
Route::post('/selectCodeGlosaAnswer', [QueryController::class, 'selectCodeGlosaAnswer']);

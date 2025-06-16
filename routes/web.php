<?php

use App\Enums\Fultran\VehicleServiceTypeEnum;
use App\Enums\Furips1\EventZoneEnum;
use App\Enums\Furips1\PickupZoneEnum;
use App\Enums\Furips1\VictimConditionEnum;
use App\Enums\ZoneEnum;
use App\Helpers\Constants;
use App\Http\Controllers\CacheController;
use App\Models\Invoice;
use App\Models\Sexo;
use App\Models\User;
use App\Notifications\BellNotification;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $user = User::first();

    // Enviar notificación
    $user->notify(new BellNotification([
        'title' => 'hola',
        'subtitle' => 'chao',
    ]));

    return view('welcome');
});

Route::get('/cache-keys', [CacheController::class, 'listCacheKeys']);
Route::get('/cache-clear', [CacheController::class, 'clearAllCache']);

// Route::get('/pdf1', function () {

//     $select = Sexo::select('codigo')->get();

//     $select_sexo = $select->pluck('codigo')->toArray();

//     $id = '01971870-edd8-736e-b5b4-191eddc275ab';
//     $invoice = Invoice::find($id);

//     // return $invoice->fultran;

//     $eventZones = collect(ZoneEnum::cases())->map(function ($case) {
//         return [
//             'value' => $case,
//             'label' => $case->Value(),
//         ];
//     })->toArray();

//     $claimanid_documents = Constants::CODS_SELECT_FORM_FULTRAN_CLAIMANIDTYPE;

//     $victim_documents = Constants::CODS_PDF_FURIPS1_VICTIMDOCUMENTTYPE;

//     // return $invoice->furips1;
//     // return $invoice->patient?->tipo_id_pisi?->codigo;

//     $data = [
//         'radication_date' => formatDateToArray($invoice->radication_date),
//         'radication_number_previous' => $invoice->furips1?->victimPhone,
//         'radication_number' => $invoice->radication_number,
//         'tipo_nota_id' => $invoice->tipo_nota_id,
//         'note_number' => $invoice->note_number,
//         'invoice_number' => $invoice->invoice_number,
//         'firstLastNameClaimant' => $invoice->fultran?->firstLastNameClaimant,
//         'secondLastNameClaimant' => $invoice->fultran?->secondLastNameClaimant,
//         'firstNameClaimant' => $invoice->fultran?->firstNameClaimant,
//         'secondNameClaimant' => $invoice->fultran?->secondNameClaimant,
//         'claimanid_documents' => $claimanid_documents,
//         'claimanid_document' => $invoice->fultran?->claimantIdType?->codigo,
//         'claimantIdNumber' => $invoice->fultran?->claimantIdNumber,
//         'vehicleServiceType' => $invoice->fultran?->vehicleServiceType,
//         'vehiclePlate' => $invoice->fultran?->vehiclePlate,
//         'claimantDepartment_name' => $invoice->fultran?->claimantDepartmentCode?->nombre,
//         'claimantDepartment_code' => $invoice->fultran?->claimantDepartmentCode?->codigo,
//         'claimantPhone' => $invoice->fultran?->claimantPhone,
//         'claimantMunicipality_name' => $invoice->fultran?->claimantMunicipalityCode?->nombre,
//         'claimantMunicipality_code' => $invoice->fultran?->claimantMunicipalityCode?->codigo,
//         'patient_first_surname' => $invoice->patient?->first_surname,
//         'patient_second_surname' => $invoice->patient?->second_surname,
//         'patient_first_name' => $invoice->patient?->first_name,
//         'patient_second_name' => $invoice->patient?->second_name,
//         'victim_documents' => $victim_documents,
//         'victim_document' => $invoice->patient?->tipo_id_pisi?->codigo,
//         'patient_document' => $invoice->patient?->document,
//         'patient_birth_date' => formatDateToArray($invoice->patient?->birth_date),
//         'select_sexo' => $select_sexo,
//         'sexo_code' => $invoice->patient?->sexo?->codigo,
//         'eventType' => $invoice->fultran?->eventType,
//         'pickupAddress' => $invoice->fultran?->pickupAddress,
//         'pickupDepartment_name' => $invoice->fultran?->pickupDepartmentCode?->nombre,
//         'pickupDepartment_code' => $invoice->fultran?->pickupDepartmentCode?->codigo,
//         'pickupZone' => $invoice->fultran?->pickupZone,
//         'pickupMunicipality_name' => $invoice->fultran?->pickupMunicipalityCode?->nombre,
//         'pickupMunicipality_code' => $invoice->fultran?->pickupMunicipalityCode?->codigo,
//         'eventZones' => $eventZones,
//         'transferDate' => formatDateToArray($invoice->fultran?->transferDate),
//         'transferTime' => formatTimeToArray($invoice->fultran?->transferTime),
//         'transferPickupDepartment_name' => $invoice->fultran?->transferPickupDepartmentCode?->nombre,
//         'transferPickupDepartment_code' => $invoice->fultran?->transferPickupDepartmentCode?->codigo,
//         'transferPickupMunicipality_name' => $invoice->fultran?->transferPickupMunicipalityCode?->nombre,
//         'transferPickupMunicipality_code' => $invoice->fultran?->transferPickupMunicipalityCode?->codigo,
//         'victimCondition' => $invoice->fultran?->victimCondition,
//         'involvedVehiclePlate' => $invoice->fultran?->involvedVehiclePlate,
//         'insurerCode' => $invoice->fultran?->insurerCode,
//         'involvedVehicleType' => $invoice->fultran?->involvedVehicleType,
//         'sirasRecordNumber' => $invoice->fultran?->sirasRecordNumber,
//         'billedValue' => $invoice->fultran?->billedValue,
//         'claimedValue' => $invoice->fultran?->claimedValue,
//         'serviceEnabledIndication' => $invoice->fultran?->serviceEnabledIndication,
//     ];
//     // return $data;

//     $pdf = \PDF::loadView('Exports/FurTran/FurTranExportPdf', compact('data'));

//     return $pdf->stream();
// });

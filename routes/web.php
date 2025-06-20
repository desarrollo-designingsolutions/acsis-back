<?php

use App\Enums\Furtran\VehicleServiceTypeEnum;
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

//     // return $invoice->furtran;

//     $eventZones = collect(ZoneEnum::cases())->map(function ($case) {
//         return [
//             'value' => $case,
//             'label' => $case->Value(),
//         ];
//     })->toArray();

//     $claimanid_documents = Constants::CODS_SELECT_FORM_FURTRAN_CLAIMANIDTYPE;

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
//         'firstLastNameClaimant' => $invoice->furtran?->firstLastNameClaimant,
//         'secondLastNameClaimant' => $invoice->furtran?->secondLastNameClaimant,
//         'firstNameClaimant' => $invoice->furtran?->firstNameClaimant,
//         'secondNameClaimant' => $invoice->furtran?->secondNameClaimant,
//         'claimanid_documents' => $claimanid_documents,
//         'claimanid_document' => $invoice->furtran?->claimantIdType?->codigo,
//         'claimantIdNumber' => $invoice->furtran?->claimantIdNumber,
//         'vehicleServiceType' => $invoice->furtran?->vehicleServiceType,
//         'vehiclePlate' => $invoice->furtran?->vehiclePlate,
//         'claimantDepartment_name' => $invoice->furtran?->claimantDepartmentCode?->nombre,
//         'claimantDepartment_code' => $invoice->furtran?->claimantDepartmentCode?->codigo,
//         'claimantPhone' => $invoice->furtran?->claimantPhone,
//         'claimantMunicipality_name' => $invoice->furtran?->claimantMunicipalityCode?->nombre,
//         'claimantMunicipality_code' => $invoice->furtran?->claimantMunicipalityCode?->codigo,
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
//         'eventType' => $invoice->furtran?->eventType,
//         'pickupAddress' => $invoice->furtran?->pickupAddress,
//         'pickupDepartment_name' => $invoice->furtran?->pickupDepartmentCode?->nombre,
//         'pickupDepartment_code' => $invoice->furtran?->pickupDepartmentCode?->codigo,
//         'pickupZone' => $invoice->furtran?->pickupZone,
//         'pickupMunicipality_name' => $invoice->furtran?->pickupMunicipalityCode?->nombre,
//         'pickupMunicipality_code' => $invoice->furtran?->pickupMunicipalityCode?->codigo,
//         'eventZones' => $eventZones,
//         'transferDate' => formatDateToArray($invoice->furtran?->transferDate),
//         'transferTime' => formatTimeToArray($invoice->furtran?->transferTime),
//         'transferPickupDepartment_name' => $invoice->furtran?->transferPickupDepartmentCode?->nombre,
//         'transferPickupDepartment_code' => $invoice->furtran?->transferPickupDepartmentCode?->codigo,
//         'transferPickupMunicipality_name' => $invoice->furtran?->transferPickupMunicipalityCode?->nombre,
//         'transferPickupMunicipality_code' => $invoice->furtran?->transferPickupMunicipalityCode?->codigo,
//         'victimCondition' => $invoice->furtran?->victimCondition,
//         'involvedVehiclePlate' => $invoice->furtran?->involvedVehiclePlate,
//         'insurerCode' => $invoice->furtran?->insurerCode,
//         'involvedVehicleType' => $invoice->furtran?->involvedVehicleType,
//         'sirasRecordNumber' => $invoice->furtran?->sirasRecordNumber,
//         'billedValue' => $invoice->furtran?->billedValue,
//         'claimedValue' => $invoice->furtran?->claimedValue,
//         'serviceEnabledIndication' => $invoice->furtran?->serviceEnabledIndication,
//     ];
//     // return $data;

//     $pdf = \PDF::loadView('Exports/FurTran/FurTranExportPdf', compact('data'));

//     return $pdf->stream();
// });

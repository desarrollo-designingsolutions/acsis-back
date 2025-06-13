<?php

use App\Enums\Furips1\EventNatureEnum;
use App\Enums\Furips1\EventZoneEnum;
use App\Enums\Furips1\PickupZoneEnum;
use App\Enums\Furips1\VictimConditionEnum;
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

Route::get('/pdf1', function () {

    // $select = Sexo::select('codigo')->get();

    // $select_sexo = $select->pluck('codigo')->toArray();

    // $id = '01971870-edd8-736e-b5b4-191eddc275ab';
    // $invoice = Invoice::find($id);
    
    // $victimConditions = collect(VictimConditionEnum::cases())->map(function ($case) {
    //     return [
    //         'value' => $case,
    //         'label' => $case->description(),
    //     ];
    // })->toArray();

    // $EventZones = collect(EventZoneEnum::cases())->map(function ($case) {
    //     return [
    //         'value' => $case,
    //         'label' => $case->Value(),
    //     ];
    // })->toArray();

    // $pickupZones = collect(PickupZoneEnum::cases())->map(function ($case) {
    //     return [
    //         'value' => $case,
    //         'label' => $case->Value(),
    //     ];
    // })->toArray();

    // $victim_documents = Constants::CODS_PDF_FURIPS1_VICTIMDOCUMENTTYPE;

    // $owner_documents = Constants::CODS_PDF_FURIPS1_OWNERDOCUMENTTYPE;

    // $driver_documents = Constants::CODS_PDF_FURIPS1_DRIVERDOCUMENTTYPE;
    
    // // return $invoice->furips1;

    // $data = [
    //     'radication_date' => formatDateToArray($invoice->radication_date),
    //     'radication_number_previous' => $invoice->furips1->victimPhone,
    //     'radication_number' => $invoice->radication_number,
    //     'tipo_nota_id' => $invoice->tipo_nota_id,
    //     'note_number' => $invoice->note_number,
    //     'invoice_number' => $invoice->invoice_number,
    //     'service_vendor_name' => $invoice->serviceVendor->name,
    //     'service_vendor_nit' => $invoice->serviceVendor->nit,
    //     'service_vendor_ipsable' => $invoice->serviceVendor->ipsable->codigo,
    //     'patient_first_surname' => $invoice->patient->first_surname,
    //     'patient_second_surname' => $invoice->patient->second_surname,
    //     'patient_first_name' => $invoice->patient->first_name,
    //     'patient_second_name' => $invoice->patient->second_name,
    //     'patient_document' => $invoice->patient->document,
    //     'patient_birth_date' => formatDateToArray($invoice->patient->birth_date),
    //     'select_sexo' => $select_sexo,
    //     'sexo_code' => $invoice->patient->sexo->codigo,
    //     'patient_recidence_address' => $invoice->furips1->victimResidenceAddress,
    //     'patient_phone' => $invoice->furips1->victimPhone,
    //     'victim_conditions' => $victimConditions,
    //     'victim_documents' => $victim_documents,
    //     'victim_document' => $invoice->patient->tipo_id_pisi->codigo,
    //     'patient_condition' => $invoice->furips1->victimCondition,
    //     'patient_department_name' => $invoice->patient->pais_residency->nombre,
    //     'patient_department_code' => $invoice->patient->pais_residency->codigo,
    //     'patient_municipio_name' => $invoice->patient->municipio_residency->nombre,
    //     'patient_municipio_code' => $invoice->patient->municipio_residency->codigo,
    //     'otherEventDescription' => $invoice->furips1->otherEventDescription,
    //     'eventOccurrenceAddress' => $invoice->furips1->eventOccurrenceAddress,
    //     'eventOccurrenceDate' => formatDateToArray($invoice->furips1->eventOccurrenceDate),
    //     'eventOccurrenceTime' => formatTimeToArray($invoice->furips1->eventOccurrenceTime),
    //     'eventDepartment_name' => $invoice->furips1->eventDepartmentCode->nombre,
    //     'eventDepartment_code' => $invoice->furips1->eventDepartmentCode->codigo,
    //     'eventMunicipalityCode_name' => $invoice->furips1->eventMunicipalityCode->nombre,
    //     'eventMunicipalityCode_code' => $invoice->furips1->eventMunicipalityCode->codigo,
    //     'eventZones' => $EventZones,
    //     'eventZone' => $invoice->furips1->eventZone,
    //     'eventNature' => $invoice->furips1->eventNature,
    //     'vehicleBrand' => $invoice->furips1->vehicleBrand,
    //     'vehiclePlate' => $invoice->furips1->vehiclePlate,
    //     'owner_documents' => $owner_documents,
    //     'owner_document' => $invoice->furips1->ownerDocumentType->codigo,
    //     'ownerFirstLastName' => $invoice->furips1->ownerFirstLastName,
    //     'ownerSecondLastName' => $invoice->furips1->ownerSecondLastName,
    //     'ownerFirstName' => $invoice->furips1->ownerFirstName,
    //     'ownerSecondName' => $invoice->furips1->ownerSecondName,
    //     'ownerDocumentNumber' => $invoice->furips1->ownerDocumentNumber,
    //     'ownerResidenceAddress' => $invoice->furips1->ownerResidenceAddress,
    //     'ownerResidenceDepartment_name' => $invoice->furips1->ownerResidenceDepartmentCode->nombre,
    //     'ownerResidenceDepartment_code' => $invoice->furips1->ownerResidenceDepartmentCode->codigo,
    //     'ownerResidencePhone' => $invoice->furips1->ownerResidencePhone,
    //     'ownerResidenceMunicipality_name' => $invoice->furips1->ownerResidenceMunicipalityCode->nombre,
    //     'ownerResidenceMunicipality_code' => $invoice->furips1->ownerResidenceMunicipalityCode->codigo,
    //     'driver_documents' => $driver_documents,
    //     'driver_document' => $invoice->furips1->driverDocumentType->codigo,
    //     'driverFirstLastName' => $invoice->furips1->driverFirstLastName,
    //     'driverSecondLastName' => $invoice->furips1->driverSecondLastName,
    //     'driverFirstName' => $invoice->furips1->driverFirstName,
    //     'driverSecondName' => $invoice->furips1->driverSecondName,
    //     'driverDocumentNumber' => $invoice->furips1->driverDocumentNumber,
    //     'driverResidenceAddress' => $invoice->furips1->driverResidenceAddress,
    //     'driverResidenceDepartment_name' => $invoice->furips1->driverResidenceDepartmentCode->nombre,
    //     'driverResidenceDepartment_code' => $invoice->furips1->driverResidenceDepartmentCode->codigo,
    //     'driverResidencePhone' => $invoice->furips1->driverResidencePhone,
    //     'driverResidenceMunicipality_name' => $invoice->furips1->driverResidenceMunicipalityCode->nombre,
    //     'driverResidenceMunicipality_code' => $invoice->furips1->driverResidenceMunicipalityCode->codigo,
    //     'referenceType' => $invoice->furips1->referenceType,
    //     'referralDate' => formatDateToArray($invoice->furips1->referralDate),
    //     'departureTime' => formatTimeToArray($invoice->furips1->departureTime),
    //     'referringHealthProviderCode_name' => $invoice->furips1->referringHealthProviderCode->nombre,
    //     'referringHealthProviderCode_code' => $invoice->furips1->referringHealthProviderCode->codigo,
    //     'referringProfessional' => $invoice->furips1->referringProfessional,
    //     'referringPersonPosition' => $invoice->furips1->referringPersonPosition,
    //     'admissionDate' => formatDateToArray($invoice->furips1->admissionDate),
    //     'admissionTime' => formatTimeToArray($invoice->furips1->admissionTime),
    //     'receivingProfessional' => $invoice->furips1->receivingProfessional,
    //     'receivingHealthProviderCode_name' => $invoice->furips1->receivingHealthProviderCode->nombre,
    //     'receivingHealthProviderCode_code' => $invoice->furips1->receivingHealthProviderCode->codigo,
    //     'interinstitutionalTransferAmbulancePlate' => $invoice->furips1->interinstitutionalTransferAmbulancePlate,
    //     'totalBilledMedicalSurgical' => $invoice->furips1->totalBilledMedicalSurgical,
    //     'totalClaimedMedicalSurgical' => $invoice->furips1->totalClaimedMedicalSurgical,
    //     'totalBilledTransport' => $invoice->furips1->totalBilledTransport,
    //     'totalClaimedTransport' => $invoice->furips1->totalClaimedTransport,
    //     'victimTransportFromEventSite' => $invoice->furips1->victimTransportFromEventSite,
    //     'victimTransportToEnd' => $invoice->furips1->victimTransportToEnd,
    //     'transportServiceType' => $invoice->furips1->transportServiceType,
    //     'pickupZones' => $pickupZones,
    //     'victimPickupZone' => $invoice->furips1->victimPickupZone,
    //     'insurance_statuse' => $invoice?->typeable?->insurance_statuse?->code,
    //     'policy_number' => $invoice?->typeable?->policy_number,
    //     'incident_start_date' => formatDateToArray($invoice?->typeable?->start_date),
    //     'incident_end_date' => formatDateToArray($invoice?->typeable?->end_date),
    // ];
    // // return $data;

    $data = [];

    $pdf = \PDF::loadView('Exports/FurTran/FurTranExportPdf', compact('data'));

    return $pdf->stream();
});

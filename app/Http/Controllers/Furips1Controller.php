<?php

namespace App\Http\Controllers;

use App\Enums\Furips1\VictimConditionEnum;
use App\Enums\ZoneEnum;
use App\Helpers\Constants;
use App\Http\Requests\Furips1\Furips1StoreRequest;
use App\Http\Resources\Furips1\Furips1FormResource;
use App\Http\Resources\Furips1\Furips1PaginateResource;
use App\Repositories\Furips1Repository;
use App\Repositories\InvoiceRepository;
use App\Repositories\SexoRepository;
use App\Services\CacheService;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;

class Furips1Controller extends Controller
{
    use HttpResponseTrait;

    private $key_redis_project;

    public function __construct(
        protected InvoiceRepository $invoiceRepository,
        protected Furips1Repository $furips1Repository,
        protected QueryController $queryController,
        protected CacheService $cacheService,
        protected SexoRepository $sexoRepository,
    ) {

        $this->key_redis_project = env('KEY_REDIS_PROJECT');
    }

    public function paginate(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->furips1Repository->paginate($request->all());
            $tableData = Furips1PaginateResource::collection($data);

            return [
                'code' => 200,
                'tableData' => $tableData,
                'lastPage' => $data->lastPage(),
                'totalData' => $data->total(),
                'totalPage' => $data->perPage(),
                'currentPage' => $data->currentPage(),
            ];
        });
    }

    public function create($invoice_id)
    {
        return $this->execute(function () use ($invoice_id) {

            $invoice = $this->invoiceRepository->find($invoice_id, with: ['typeable:id,insurance_statuse_id', 'typeable.insurance_statuse:id,code'], select: ['id', 'type', 'typeable_type', 'typeable_id', 'invoice_date']);
            $invoice = [
                'id' => $invoice->id,
                'invoice_date' => $invoice->invoice_date,
                'insurance_statuse_code' => $invoice->typeable?->insurance_statuse?->code,
            ];

            $rgoResponseEnum = $this->queryController->selectRgoResponseEnum(request());
            $victimConditionEnum = $this->queryController->selectVictimConditionEnum(request());
            $eventNatureEnum = $this->queryController->selectEventNatureEnum(request());
            $zoneEnum = $this->queryController->selectZoneEnum(request());
            $referenceTypeEnum = $this->queryController->selectReferenceTypeEnum(request());
            $ipsCodHabilitacion = $this->queryController->selectInfiniteIpsCodHabilitacion(request());
            $vehicleTypeEnum = $this->queryController->selectVehicleTypeEnum(request());
            $yesNoEnum = $this->queryController->selectYesNoEnum(request());
            $surgicalComplexityEnum = $this->queryController->selectSurgicalComplexityEnum(request());
            $transportServiceTypeEnum = $this->queryController->selectTransportServiceTypeEnum(request());
            $pais = $this->queryController->selectInfinitePais(request());
            $municipio = $this->queryController->selectInfiniteMunicipio(request());
            $departamento = $this->queryController->selectInfiniteDepartamento(request());

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_FURIPS1_OWNERDOCUMENTTYPE]);
            $tipoIdPisis1 = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_FURIPS1_DRIVERDOCUMENTTYPE]);
            $tipoIdPisis2 = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_FURIPS1_DOCTORIDTYPE]);
            $tipoIdPisis3 = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $tipoIdPisis = [
                'ownerDocumentType_arrayInfo' => $tipoIdPisis1['tipoIdPisis_arrayInfo'],
                'driverDocumentType_arrayInfo' => $tipoIdPisis2['tipoIdPisis_arrayInfo'],
                'doctorIdType_arrayInfo' => $tipoIdPisis3['tipoIdPisis_arrayInfo'],
            ];

            return [
                'code' => 200,
                'invoice' => $invoice,
                ...$rgoResponseEnum,
                ...$victimConditionEnum,
                ...$eventNatureEnum,
                ...$zoneEnum,
                ...$referenceTypeEnum,
                ...$ipsCodHabilitacion,
                ...$vehicleTypeEnum,
                ...$yesNoEnum,
                ...$surgicalComplexityEnum,
                ...$tipoIdPisis,
                ...$transportServiceTypeEnum,
                ...$pais,
                ...$municipio,
                ...$departamento,
            ];
        });
    }

    public function store(Furips1StoreRequest $request)
    {

        return $this->runTransaction(function () use ($request) {

            $post = $request->except([]);
            $furips1 = $this->furips1Repository->store($post);

            $this->cacheService->clearByPrefix($this->key_redis_project.'string:invoices_paginate*');

            return [
                'code' => 200,
                'message' => 'Furips1 agregado correctamente',
                'furips1' => $furips1,
            ];
        });
    }

    public function edit($id)
    {
        return $this->execute(function () use ($id) {

            $furips1 = $this->furips1Repository->find($id);
            $form = new Furips1FormResource($furips1);

            $invoice = $this->invoiceRepository->find($furips1->invoice_id, with: ['typeable:id,insurance_statuse_id', 'typeable.insurance_statuse:id,code'], select: ['id', 'type', 'typeable_type', 'typeable_id', 'invoice_date']);
            $invoice = [
                'id' => $invoice->id,
                'invoice_date' => $invoice->invoice_date,
                'insurance_statuse_code' => $invoice->typeable?->insurance_statuse?->code,
            ];

            $rgoResponseEnum = $this->queryController->selectRgoResponseEnum(request());
            $victimConditionEnum = $this->queryController->selectVictimConditionEnum(request());
            $eventNatureEnum = $this->queryController->selectEventNatureEnum(request());
            $zoneEnum = $this->queryController->selectZoneEnum(request());
            $referenceTypeEnum = $this->queryController->selectReferenceTypeEnum(request());
            $ipsCodHabilitacion = $this->queryController->selectInfiniteIpsCodHabilitacion(request());
            $vehicleTypeEnum = $this->queryController->selectVehicleTypeEnum(request());
            $yesNoEnum = $this->queryController->selectYesNoEnum(request());
            $surgicalComplexityEnum = $this->queryController->selectSurgicalComplexityEnum(request());
            $transportServiceTypeEnum = $this->queryController->selectTransportServiceTypeEnum(request());
            $pais = $this->queryController->selectInfinitePais(request());
            $municipio = $this->queryController->selectInfiniteMunicipio(request());
            $departamento = $this->queryController->selectInfiniteDepartamento(request());
            $cie10s = $this->queryController->selectInfiniteCie10(request());

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_FURIPS1_OWNERDOCUMENTTYPE]);
            $tipoIdPisis1 = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_FURIPS1_DRIVERDOCUMENTTYPE]);
            $tipoIdPisis2 = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_FURIPS1_DOCTORIDTYPE]);
            $tipoIdPisis3 = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $tipoIdPisis = [
                'ownerDocumentType_arrayInfo' => $tipoIdPisis1['tipoIdPisis_arrayInfo'],
                'driverDocumentType_arrayInfo' => $tipoIdPisis2['tipoIdPisis_arrayInfo'],
                'doctorIdType_arrayInfo' => $tipoIdPisis3['tipoIdPisis_arrayInfo'],
            ];

            return [
                'code' => 200,
                'form' => $form,
                'invoice' => $invoice,
                ...$rgoResponseEnum,
                ...$victimConditionEnum,
                ...$eventNatureEnum,
                ...$zoneEnum,
                ...$referenceTypeEnum,
                ...$ipsCodHabilitacion,
                ...$vehicleTypeEnum,
                ...$yesNoEnum,
                ...$surgicalComplexityEnum,
                ...$tipoIdPisis,
                ...$transportServiceTypeEnum,
                ...$pais,
                ...$municipio,
                ...$departamento,
                ...$cie10s,
            ];
        });
    }

    public function update(Furips1StoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except([]);
            $furips1 = $this->furips1Repository->store($post);

            return [
                'code' => 200,
                'message' => 'Furips1 modificada correctamente',
            ];
        });
    }

    public function delete($id)
    {
        return $this->runTransaction(function () use ($id) {
            $furips1 = $this->furips1Repository->find($id);
            if ($furips1) {

                $furips1->delete();

                $msg = 'Registro eliminado correctamente';
            } else {
                $msg = 'El registro no existe';
            }

            return [
                'code' => 200,
                'message' => $msg,
            ];
        }, 200);
    }

    public function pdf($invoice_id)
    {
        return $this->execute(function () use ($invoice_id) {

            $invoice = $this->invoiceRepository->find($invoice_id);

            $select = $this->sexoRepository->get()->select('codigo');
            $select_sexo = $select->pluck('codigo')->toArray();

            $victimConditions = collect(VictimConditionEnum::cases())->map(function ($case) {
                return [
                    'value' => $case,
                    'label' => $case->description(),
                ];
            })->toArray();

            $EventZones = collect(ZoneEnum::cases())->map(function ($case) {
                return [
                    'value' => $case,
                    'label' => $case->Value(),
                ];
            })->toArray();

            $pickupZones = collect(ZoneEnum::cases())->map(function ($case) {
                return [
                    'value' => $case,
                    'label' => $case->Value(),
                ];
            })->toArray();

            $victim_documents = Constants::CODS_PDF_FURIPS1_VICTIMDOCUMENTTYPE;

            $owner_documents = Constants::CODS_PDF_FURIPS1_OWNERDOCUMENTTYPE;

            $driver_documents = Constants::CODS_PDF_FURIPS1_DRIVERDOCUMENTTYPE;

            $data = [
                'radication_date' => formatDateToArray($invoice->radication_date),
                'radication_number_previous' => $invoice->furips1?->victimPhone,
                'radication_number' => $invoice->radication_number,
                'tipo_nota_id' => $invoice->tipo_nota_id,
                'note_number' => $invoice->note_number,
                'invoice_number' => $invoice->invoice_number,
                'service_vendor_name' => $invoice->serviceVendor?->name,
                'service_vendor_nit' => $invoice->serviceVendor?->nit,
                'service_vendor_ipsable' => $invoice->serviceVendor?->ipsable?->codigo,
                'patient_first_surname' => $invoice->patient?->first_surname,
                'patient_second_surname' => $invoice->patient?->second_surname,
                'patient_first_name' => $invoice->patient?->first_name,
                'patient_second_name' => $invoice->patient?->second_name,
                'patient_document' => $invoice->patient?->document,
                'patient_birth_date' => formatDateToArray($invoice->patient?->birth_date),
                'select_sexo' => $select_sexo,
                'sexo_code' => $invoice->patient?->sexo?->codigo,
                'patient_recidence_address' => $invoice->furips1?->victimResidenceAddress,
                'patient_phone' => $invoice->furips1?->victimPhone,
                'victim_conditions' => $victimConditions,
                'victim_documents' => $victim_documents,
                'victim_document' => $invoice->patient?->tipo_id_pisi?->codigo,
                'patient_condition' => $invoice->furips1?->victimCondition,
                'patient_department_name' => $invoice->patient?->pais_residency?->nombre,
                'patient_department_code' => $invoice->patient?->pais_residency?->codigo,
                'patient_municipio_name' => $invoice->patient?->municipio_residency?->nombre,
                'patient_municipio_code' => $invoice->patient?->municipio_residency?->codigo,
                'otherEventDescription' => $invoice->furips1?->otherEventDescription,
                'eventOccurrenceAddress' => $invoice->furips1?->eventOccurrenceAddress,
                'eventOccurrenceDate' => formatDateToArray($invoice->furips1?->eventOccurrenceDate),
                'eventOccurrenceTime' => formatTimeToArray($invoice->furips1?->eventOccurrenceTime),
                'eventDepartment_name' => $invoice->furips1?->eventDepartmentCode?->nombre,
                'eventDepartment_code' => $invoice->furips1?->eventDepartmentCode?->codigo,
                'eventMunicipalityCode_name' => $invoice->furips1?->eventMunicipalityCode?->nombre,
                'eventMunicipalityCode_code' => $invoice->furips1?->eventMunicipalityCode?->codigo,
                'eventZones' => $EventZones,
                'eventZone' => $invoice->furips1?->eventZone,
                'eventNature' => $invoice->furips1?->eventNature,
                'vehicleBrand' => $invoice->furips1?->vehicleBrand,
                'vehiclePlate' => $invoice->furips1?->vehiclePlate,
                'owner_documents' => $owner_documents,
                'owner_document' => $invoice->furips1?->ownerDocumentType?->codigo,
                'ownerFirstLastName' => $invoice->furips1?->ownerFirstLastName,
                'ownerSecondLastName' => $invoice->furips1?->ownerSecondLastName,
                'ownerFirstName' => $invoice->furips1?->ownerFirstName,
                'ownerSecondName' => $invoice->furips1?->ownerSecondName,
                'ownerDocumentNumber' => $invoice->furips1?->ownerDocumentNumber,
                'ownerResidenceAddress' => $invoice->furips1?->ownerResidenceAddress,
                'ownerResidenceDepartment_name' => $invoice->furips1?->ownerResidenceDepartmentCode?->nombre,
                'ownerResidenceDepartment_code' => $invoice->furips1?->ownerResidenceDepartmentCode?->codigo,
                'ownerResidencePhone' => $invoice->furips1?->ownerResidencePhone,
                'ownerResidenceMunicipality_name' => $invoice->furips1?->ownerResidenceMunicipalityCode?->nombre,
                'ownerResidenceMunicipality_code' => $invoice->furips1?->ownerResidenceMunicipalityCode?->codigo,
                'driver_documents' => $driver_documents,
                'driver_document' => $invoice->furips1?->driverDocumentType?->codigo,
                'driverFirstLastName' => $invoice->furips1?->driverFirstLastName,
                'driverSecondLastName' => $invoice->furips1?->driverSecondLastName,
                'driverFirstName' => $invoice->furips1?->driverFirstName,
                'driverSecondName' => $invoice->furips1?->driverSecondName,
                'driverDocumentNumber' => $invoice->furips1?->driverDocumentNumber,
                'driverResidenceAddress' => $invoice->furips1?->driverResidenceAddress,
                'driverResidenceDepartment_name' => $invoice->furips1?->driverResidenceDepartmentCode?->nombre,
                'driverResidenceDepartment_code' => $invoice->furips1?->driverResidenceDepartmentCode?->codigo,
                'driverResidencePhone' => $invoice->furips1?->driverResidencePhone,
                'driverResidenceMunicipality_name' => $invoice->furips1?->driverResidenceMunicipalityCode?->nombre,
                'driverResidenceMunicipality_code' => $invoice->furips1?->driverResidenceMunicipalityCode?->codigo,
                'referenceType' => $invoice->furips1?->referenceType,
                'referralDate' => formatDateToArray($invoice->furips1?->referralDate),
                'departureTime' => formatTimeToArray($invoice->furips1?->departureTime),
                'referringHealthProviderCode_name' => $invoice->furips1?->referringHealthProviderCode?->nombre,
                'referringHealthProviderCode_code' => $invoice->furips1?->referringHealthProviderCode?->codigo,
                'referringProfessional' => $invoice->furips1?->referringProfessional,
                'referringPersonPosition' => $invoice->furips1?->referringPersonPosition,
                'admissionDate' => formatDateToArray($invoice->furips1?->admissionDate),
                'admissionTime' => formatTimeToArray($invoice->furips1?->admissionTime),
                'receivingProfessional' => $invoice->furips1?->receivingProfessional,
                'receivingHealthProviderCode_name' => $invoice->furips1?->receivingHealthProviderCode?->nombre,
                'receivingHealthProviderCode_code' => $invoice->furips1?->receivingHealthProviderCode?->codigo,
                'interinstitutionalTransferAmbulancePlate' => $invoice->furips1?->interinstitutionalTransferAmbulancePlate,
                'totalBilledMedicalSurgical' => $invoice->furips1?->totalBilledMedicalSurgical,
                'totalClaimedMedicalSurgical' => $invoice->furips1?->totalClaimedMedicalSurgical,
                'totalBilledTransport' => $invoice->furips1?->totalBilledTransport,
                'totalClaimedTransport' => $invoice->furips1?->totalClaimedTransport,
                'victimTransportFromEventSite' => $invoice->furips1?->victimTransportFromEventSite,
                'victimTransportToEnd' => $invoice->furips1?->victimTransportToEnd,
                'transportServiceType' => $invoice->furips1?->transportServiceType,
                'pickupZones' => $pickupZones,
                'victimPickupZone' => $invoice->furips1?->victimPickupZone,
                'insurance_statuse' => $invoice?->typeable?->insurance_statuse?->code,
                'policy_number' => $invoice?->typeable?->policy_number,
                'incident_start_date' => formatDateToArray($invoice?->typeable?->start_date),
                'incident_end_date' => formatDateToArray($invoice?->typeable?->end_date),
                
                'medicalAdmissionDate' => formatDateToArray($invoice?->furips1?->medicalAdmissionDate),
                'medicalAdmissionTime' => formatTimeToArray($invoice?->furips1?->medicalAdmissionTime),
                'medicalDischargeDate' => formatDateToArray($invoice?->furips1?->medicalDischargeDate),
                'medicalDischargeTime' => formatTimeToArray($invoice?->furips1?->medicalDischargeTime),
                'primaryAdmissionDiagnosis_code' => $invoice->furips1?->primaryAdmissionDiagnosisCode?->codigo,
                'associatedAdmissionDiagnosisCode1_code' => $invoice->furips1?->associatedAdmissionDiagnosisCode1?->codigo,
                'associatedAdmissionDiagnosisCode2_code' => $invoice->furips1?->associatedAdmissionDiagnosisCode2?->codigo,
                'primaryDischargeDiagnosisCode_code' => $invoice->furips1?->primaryDischargeDiagnosisCode?->codigo,
                'associatedDischargeDiagnosisCode1_code' => $invoice->furips1?->associatedDischargeDiagnosisCode1?->codigo,
                'associatedDischargeDiagnosisCode2_code' => $invoice->furips1?->associatedDischargeDiagnosisCode2?->codigo,
                'authorityIntervention_code' => $invoice->furips1?->authorityIntervention,
                'policyExcessCharge' => $invoice->furips1?->policyExcessCharge,
                'referralRecipientCharge' => $invoice->furips1?->referralRecipientCharge,

            ];

            $pdf = $this->invoiceRepository
                ->pdf('Exports.Furips1.Furips1ExportPdf', $data, is_stream: true);

            if (empty($pdf)) {
                throw new \Exception('Error al generar el PDF');
            }

            $path = base64_encode($pdf);

            return [
                'code' => 200,
                'path' => $path,
            ];
        });
    }
}

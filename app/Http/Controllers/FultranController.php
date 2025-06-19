<?php

namespace App\Http\Controllers;

use App\Enums\ZoneEnum;
use App\Helpers\Constants;
use App\Http\Requests\Fultran\FultranStoreRequest;
use App\Http\Resources\Fultran\FultranFormResource;
use App\Http\Resources\Fultran\FultranPaginateResource;
use App\Repositories\FultranRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\SexoRepository;
use App\Services\CacheService;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;

class FultranController extends Controller
{
    use HttpResponseTrait;

    private $key_redis_project;

    public function __construct(
        protected InvoiceRepository $invoiceRepository,
        protected FultranRepository $fultranRepository,
        protected QueryController $queryController,
        protected CacheService $cacheService,
        protected SexoRepository $sexoRepository,
    ) {
        $this->key_redis_project = env('KEY_REDIS_PROJECT');
    }

    public function paginate(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->fultranRepository->paginate($request->all());
            $tableData = FultranPaginateResource::collection($data);

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

            $invoice = $this->invoiceRepository->find($invoice_id, with: ['typeable:id,insurance_statuse_id', 'typeable.insurance_statuse:id,code'], select: ['id', 'type', 'typeable_type', 'typeable_id']);
            $invoice = [
                'id' => $invoice->id,
                'insurance_statuse_code' => $invoice->typeable?->insurance_statuse?->code,
            ];

            $rgResponseEnum = $this->queryController->selectRgResponseEnum(request());
            $genderEnum = $this->queryController->selectGenderEnum(request());
            $zoneEnum = $this->queryController->selectZoneEnum(request());
            $victimConditionEnum = $this->queryController->selectVictimConditionEnum(request());
            $vehicleServiceTypeEnum = $this->queryController->selectVehicleServiceTypeEnum(request());
            $vehicleTypeEnum = $this->queryController->selectVehicleTypeEnum(request());
            $eventTypeEnum = $this->queryController->selectEventTypeEnum(request());
            $yesNoEnum = $this->queryController->selectYesNoEnum(request());
            $municipio = $this->queryController->selectInfiniteMunicipio(request());
            $departamento = $this->queryController->selectInfiniteDepartamento(request());
            $ipsCodHabilitacion = $this->queryController->selectInfiniteIpsCodHabilitacion(request());

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_FULTRAN_CLAIMANIDTYPE]);
            $tipoIdPisis = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            return [
                'code' => 200,
                'invoice' => $invoice,
                ...$rgResponseEnum,
                ...$vehicleServiceTypeEnum,
                ...$victimConditionEnum,
                ...$vehicleTypeEnum,
                ...$zoneEnum,
                ...$yesNoEnum,
                ...$eventTypeEnum,
                ...$genderEnum,
                ...$tipoIdPisis,
                ...$municipio,
                ...$departamento,
                ...$ipsCodHabilitacion,
            ];
        });
    }

    public function store(FultranStoreRequest $request)
    {

        return $this->runTransaction(function () use ($request) {

            $post = $request->except([]);
            $fultran = $this->fultranRepository->store($post);

            $this->cacheService->clearByPrefix($this->key_redis_project . 'string:invoices_paginate*');

            return [
                'code' => 200,
                'message' => 'Fultran agregado correctamente',
                'fultran' => $fultran,
            ];
        });
    }

    public function edit($id)
    {
        return $this->execute(function () use ($id) {

            $fultran = $this->fultranRepository->find($id);
            $form = new FultranFormResource($fultran);

            $invoice = $this->invoiceRepository->find($fultran->invoice_id, with: ['typeable:id,insurance_statuse_id', 'typeable.insurance_statuse:id,code'], select: ['id', 'type', 'typeable_type', 'typeable_id']);
            $invoice = [
                'id' => $invoice->id,
                'insurance_statuse_code' => $invoice->typeable?->insurance_statuse?->code,
            ];

            $rgResponseEnum = $this->queryController->selectRgResponseEnum(request());
            $genderEnum = $this->queryController->selectGenderEnum(request());
            $zoneEnum = $this->queryController->selectZoneEnum(request());
            $victimConditionEnum = $this->queryController->selectVictimConditionEnum(request());
            $vehicleServiceTypeEnum = $this->queryController->selectVehicleServiceTypeEnum(request());
            $vehicleTypeEnum = $this->queryController->selectVehicleTypeEnum(request());
            $eventTypeEnum = $this->queryController->selectEventTypeEnum(request());
            $yesNoEnum = $this->queryController->selectYesNoEnum(request());
            $municipio = $this->queryController->selectInfiniteMunicipio(request());
            $departamento = $this->queryController->selectInfiniteDepartamento(request());
            $ipsCodHabilitacion = $this->queryController->selectInfiniteIpsCodHabilitacion(request());

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_FULTRAN_CLAIMANIDTYPE]);
            $tipoIdPisis = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            return [
                'code' => 200,
                'form' => $form,

                'invoice' => $invoice,
                ...$rgResponseEnum,
                ...$vehicleServiceTypeEnum,
                ...$victimConditionEnum,
                ...$vehicleTypeEnum,
                ...$zoneEnum,
                ...$yesNoEnum,
                ...$eventTypeEnum,
                ...$genderEnum,
                ...$tipoIdPisis,
                ...$municipio,
                ...$departamento,
                ...$ipsCodHabilitacion,
            ];
        });
    }

    public function update(FultranStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except([]);
            $fultran = $this->fultranRepository->store($post);

            return [
                'code' => 200,
                'message' => 'Fultran modificada correctamente',

            ];
        });
    }

    public function delete($id)
    {
        return $this->runTransaction(function () use ($id) {
            $fultran = $this->fultranRepository->find($id);
            if ($fultran) {

                $fultran->delete();

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

            $eventZones = collect(ZoneEnum::cases())->map(function ($case) {
                return [
                    'value' => $case,
                    'label' => $case->Value(),
                ];
            })->toArray();

            $claimanid_documents = Constants::CODS_SELECT_FORM_FULTRAN_CLAIMANIDTYPE;

            $victim_documents = Constants::CODS_PDF_FURIPS1_VICTIMDOCUMENTTYPE;

            $data = [
                'radication_date' => formatDateToArray($invoice->radication_date),
                'radication_number_previous' => $invoice->furips1?->victimPhone,
                'radication_number' => $invoice->radication_number,
                'tipo_nota_id' => $invoice->tipo_nota_id,
                'note_number' => $invoice->note_number,
                'invoice_number' => $invoice->invoice_number,
                'firstLastNameClaimant' => $invoice->fultran?->firstLastNameClaimant,
                'secondLastNameClaimant' => $invoice->fultran?->secondLastNameClaimant,
                'firstNameClaimant' => $invoice->fultran?->firstNameClaimant,
                'secondNameClaimant' => $invoice->fultran?->secondNameClaimant,
                'claimanid_documents' => $claimanid_documents,
                'claimanid_document' => $invoice->fultran?->claimantIdType?->codigo,
                'claimantIdNumber' => $invoice->fultran?->claimantIdNumber,
                'vehicleServiceType' => $invoice->fultran?->vehicleServiceType,
                'vehiclePlate' => $invoice->fultran?->vehiclePlate,
                'claimantDepartment_name' => $invoice->fultran?->claimantDepartmentCode?->nombre,
                'claimantDepartment_code' => $invoice->fultran?->claimantDepartmentCode?->codigo,
                'claimantPhone' => $invoice->fultran?->claimantPhone,
                'claimantMunicipality_name' => $invoice->fultran?->claimantMunicipalityCode?->nombre,
                'claimantMunicipality_code' => $invoice->fultran?->claimantMunicipalityCode?->codigo,
                'patient_first_surname' => $invoice->patient?->first_surname,
                'patient_second_surname' => $invoice->patient?->second_surname,
                'patient_first_name' => $invoice->patient?->first_name,
                'patient_second_name' => $invoice->patient?->second_name,
                'victim_documents' => $victim_documents,
                'victim_document' => $invoice->patient?->tipo_id_pisi?->codigo,
                'patient_document' => $invoice->patient?->document,
                'patient_birth_date' => formatDateToArray($invoice->patient?->birth_date),
                'select_sexo' => $select_sexo,
                'sexo_code' => $invoice->patient?->sexo?->codigo,
                'eventType' => $invoice->fultran?->eventType,
                'pickupAddress' => $invoice->fultran?->pickupAddress,
                'pickupDepartment_name' => $invoice->fultran?->pickupDepartmentCode?->nombre,
                'pickupDepartment_code' => $invoice->fultran?->pickupDepartmentCode?->codigo,
                'pickupZone' => $invoice->fultran?->pickupZone,
                'pickupMunicipality_name' => $invoice->fultran?->pickupMunicipalityCode?->nombre,
                'pickupMunicipality_code' => $invoice->fultran?->pickupMunicipalityCode?->codigo,
                'eventZones' => $eventZones,
                'transferDate' => formatDateToArray($invoice->fultran?->transferDate),
                'transferTime' => formatTimeToArray($invoice->fultran?->transferTime),
                'transferPickupDepartment_name' => $invoice->fultran?->transferPickupDepartmentCode?->nombre,
                'transferPickupDepartment_code' => $invoice->fultran?->transferPickupDepartmentCode?->codigo,
                'transferPickupMunicipality_name' => $invoice->fultran?->transferPickupMunicipalityCode?->nombre,
                'transferPickupMunicipality_code' => $invoice->fultran?->transferPickupMunicipalityCode?->codigo,
                'victimCondition' => $invoice->fultran?->victimCondition,
                'involvedVehiclePlate' => $invoice->fultran?->involvedVehiclePlate,
                'insurerCode' => $invoice->fultran?->insurerCode,
                'involvedVehicleType' => $invoice->fultran?->involvedVehicleType,
                'sirasRecordNumber' => $invoice->fultran?->sirasRecordNumber,
                'billedValue' => $invoice->fultran?->billedValue,
                'claimedValue' => $invoice->fultran?->claimedValue,
                'serviceEnabledIndication' => $invoice->fultran?->serviceEnabledIndication,
                
                'policy_number' => $invoice?->typeable?->policy_number,
                'policy_start_date' => formatDateToArray($invoice?->typeable?->start_date),
                'policy_end_date' => formatDateToArray($invoice?->typeable?->end_date),

                'ipsName' => $invoice->fultran?->ipsName,
                'ipsNit' => $invoice->fultran?->ipsNit,
                'ipsAddress' => $invoice->fultran?->ipsAddress,
                'ipsReceptionHabilitation_code' => $invoice->fultran?->ipsReceptionHabilitationCode?->codigo,
                'ipsPhone' => $invoice->fultran?->ipsPhone,
            ];

            $pdf = $this->invoiceRepository
                ->pdf('Exports.FurTran.FurTranExportPdf', $data, is_stream: true);

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


    public function downloadTxt($id)
    {
        $fultran = $this->fultranRepository->find($id);

        $data = [
            '1' => $fultran->previousRecordNumber,
            '2' => $fultran->rgResponse?->Value(),
            '3' => $fultran->invoice?->invoice_number,
            '4' => $fultran->invoice?->serviceVendor?->ipsable?->codigo,
            '5' => $fultran->firstLastNameClaimant,
            '6' => $fultran->secondLastNameClaimant,
            '7' => $fultran->firstNameClaimant,
            '8' => $fultran->secondNameClaimant,
            '9' => $fultran->claimantIdType?->codigo,
            '10' => $fultran->claimantIdNumber,
            '11' => $fultran->vehicleServiceType?->Value(),
            '12' => $fultran->vehiclePlate,
            '13' => $fultran->claimantAddress,
            '14' => $fultran->claimantPhone,
            '15' => $fultran->claimantDepartmentCode?->codigo,
            '16' => $fultran->claimantMunicipalityCode?->codigo,
            '17' => $fultran->invoice?->patient?->typeDocument?->codigo,
            '18' => $fultran->invoice?->patient?->document,
            '19' => $fultran->invoice?->patient?->first_name,
            '20' => $fultran->invoice?->patient?->second_name,
            '21' => $fultran->invoice?->patient?->first_surname,
            '22' => $fultran->invoice?->patient?->second_surname,
            '23' => $fultran->invoice?->patient?->birth_date,
            '24' => $fultran->victimGender?->Value(),
            '25' => $fultran->eventType?->Value(),
            '26' => $fultran->pickupAddress,
            '27' => $fultran->pickupDepartmentCode?->codigo,
            '28' => $fultran->pickupMunicipalityCode?->codigo,
            '29' => $fultran->pickupZone?->Value(),
            '30' => $fultran->transferDate,
            '31' => $fultran->transferTime,
            '32' => $fultran->ipsReceptionHabilitationCode?->codigo,
            '33' => $fultran->transferPickupDepartmentCode?->codigo,
            '34' => $fultran->transferPickupMunicipalityCode?->codigo,
            '35' => $fultran->victimCondition?->Value(),
            '36' => $fultran->invoice?->typeable?->insurance_statuse?->code,
            '37' => $fultran->involvedVehicleType?->Value(),
            '38' => $fultran->involvedVehiclePlate,
            '39' => $fultran->insurerCode,
            '40' => $fultran->invoice?->typeable?->policy_number,
            '41' => $fultran->invoice?->typeable?->start_date,
            '42' => $fultran->invoice?->typeable?->end_date,
            '43' => $fultran->sirasRecordNumber,
            '44' => $fultran->billedValue,
            '45' => $fultran->claimedValue,
            '46' => $fultran->serviceEnabledIndication?->Value(),
        ];
        return $fultran->invoice?->typeable?->policy_number;

        // Generate comma-separated text content
        $textContent = implode(',', array_map(function ($value) {
            return $value ?? '';
        }, $data)) . "\n";

        // Define file name
        $fileName = 'fultran_' . $id . '.txt';

        // Return response with text file for download
        return response($textContent, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}

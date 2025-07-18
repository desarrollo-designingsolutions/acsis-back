<?php

namespace App\Http\Controllers;

use App\Enums\ZoneEnum;
use App\Helpers\Constants;
use App\Http\Requests\Furtran\FurtranStoreRequest;
use App\Http\Resources\Furtran\FurtranFormResource;
use App\Http\Resources\Furtran\FurtranPaginateResource;
use App\Repositories\FurtranRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\SexoRepository;
use App\Services\CacheService;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;

class FurtranController extends Controller
{
    use HttpResponseTrait;

    private $key_redis_project;

    public function __construct(
        protected InvoiceRepository $invoiceRepository,
        protected FurtranRepository $furtranRepository,
        protected QueryController $queryController,
        protected CacheService $cacheService,
        protected SexoRepository $sexoRepository,
    ) {
        $this->key_redis_project = env('KEY_REDIS_PROJECT');
    }

    public function paginate(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->furtranRepository->paginate($request->all());
            $tableData = FurtranPaginateResource::collection($data);

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

            $invoice = $this->invoiceRepository->find($invoice_id, with: [
                'typeable:id,insurance_statuse_id',
                'typeable.insurance_statuse:id,code',
                'serviceVendor:id,ipsable_type,ipsable_id',
                'serviceVendor.ipsable:id,codigo',
            ], select: [
                'id',
                'type',
                'typeable_type',
                'typeable_id',
                'service_vendor_id',
            ]);
            $invoice = [
                'id' => $invoice->id,
                'insurance_statuse_code' => $invoice->typeable?->insurance_statuse?->code,
                'serviceVendor_ipsable_codigo' => $invoice?->serviceVendor?->ipsable?->codigo,
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

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_FURTRAN_CLAIMANIDTYPE]);
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

    public function store(FurtranStoreRequest $request)
    {

        return $this->runTransaction(function () use ($request) {

            $post = $request->except([]);
            $furtran = $this->furtranRepository->store($post);

            $this->cacheService->clearByPrefix($this->key_redis_project . 'string:invoices_paginate*');

            return [
                'code' => 200,
                'message' => 'Furtran agregado correctamente',
                'furtran' => $furtran,
            ];
        });
    }

    public function edit($id)
    {
        return $this->execute(function () use ($id) {

            $furtran = $this->furtranRepository->find($id);
            $form = new FurtranFormResource($furtran);

            $invoice = $this->invoiceRepository->find($furtran->invoice_id, with: [
                'typeable:id,insurance_statuse_id',
                'typeable.insurance_statuse:id,code',
                'serviceVendor:id,ipsable_type,ipsable_id',
                'serviceVendor.ipsable:id,codigo',
            ], select: [
                'id',
                'type',
                'typeable_type',
                'typeable_id',
                'service_vendor_id',
            ]);
            $invoice = [
                'id' => $invoice->id,
                'insurance_statuse_code' => $invoice->typeable?->insurance_statuse?->code,
                'serviceVendor_ipsable_codigo' => $invoice?->serviceVendor?->ipsable?->codigo,
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

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_FURTRAN_CLAIMANIDTYPE]);
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

    public function update(FurtranStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except([]);
            $furtran = $this->furtranRepository->store($post);

            return [
                'code' => 200,
                'message' => 'Furtran modificada correctamente',

            ];
        });
    }

    public function delete($id)
    {
        return $this->runTransaction(function () use ($id) {
            $furtran = $this->furtranRepository->find($id);
            if ($furtran) {

                $furtran->delete();

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

            $claimanid_documents = Constants::CODS_SELECT_FORM_FURTRAN_CLAIMANIDTYPE;

            $victim_documents = Constants::CODS_PDF_FURIPS1_VICTIMDOCUMENTTYPE;

            $data = [
                'radication_date' => formatDateToArray($invoice->radication_date),
                'radication_number_previous' => $invoice->furips1?->victimPhone,
                'radication_number' => $invoice->radication_number,
                'tipo_nota_id' => $invoice->tipo_nota_id,
                'note_number' => $invoice->note_number,
                'invoice_number' => $invoice->invoice_number,
                'firstLastNameClaimant' => $invoice->furtran?->firstLastNameClaimant,
                'secondLastNameClaimant' => $invoice->furtran?->secondLastNameClaimant,
                'firstNameClaimant' => $invoice->furtran?->firstNameClaimant,
                'secondNameClaimant' => $invoice->furtran?->secondNameClaimant,
                'claimanid_documents' => $claimanid_documents,
                'claimanid_document' => $invoice->furtran?->claimantIdType?->codigo,
                'claimantIdNumber' => $invoice->furtran?->claimantIdNumber,
                'vehicleServiceType' => $invoice->furtran?->vehicleServiceType,
                'vehiclePlate' => $invoice->furtran?->vehiclePlate,
                'claimantDepartment_name' => $invoice->furtran?->claimantDepartmentCode?->nombre,
                'claimantDepartment_code' => $invoice->furtran?->claimantDepartmentCode?->codigo,
                'claimantPhone' => $invoice->furtran?->claimantPhone,
                'claimantMunicipality_name' => $invoice->furtran?->claimantMunicipalityCode?->nombre,
                'claimantMunicipality_code' => $invoice->furtran?->claimantMunicipalityCode?->codigo,
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
                'eventType' => $invoice->furtran?->eventType,
                'pickupAddress' => $invoice->furtran?->pickupAddress,
                'pickupDepartment_name' => $invoice->furtran?->pickupDepartmentCode?->nombre,
                'pickupDepartment_code' => $invoice->furtran?->pickupDepartmentCode?->codigo,
                'pickupZone' => $invoice->furtran?->pickupZone,
                'pickupMunicipality_name' => $invoice->furtran?->pickupMunicipalityCode?->nombre,
                'pickupMunicipality_code' => $invoice->furtran?->pickupMunicipalityCode?->codigo,
                'eventZones' => $eventZones,
                'transferDate' => formatDateToArray($invoice->furtran?->transferDate),
                'transferTime' => formatTimeToArray($invoice->furtran?->transferTime),
                'transferPickupDepartment_name' => $invoice->furtran?->transferPickupDepartmentCode?->nombre,
                'transferPickupDepartment_code' => $invoice->furtran?->transferPickupDepartmentCode?->codigo,
                'transferPickupMunicipality_name' => $invoice->furtran?->transferPickupMunicipalityCode?->nombre,
                'transferPickupMunicipality_code' => $invoice->furtran?->transferPickupMunicipalityCode?->codigo,
                'victimCondition' => $invoice->furtran?->victimCondition,
                'involvedVehiclePlate' => $invoice->furtran?->involvedVehiclePlate,
                'insurerCode' => $invoice->furtran?->insurerCode,
                'involvedVehicleType' => $invoice->furtran?->involvedVehicleType,
                'sirasRecordNumber' => $invoice->furtran?->sirasRecordNumber,
                'billedValue' => $invoice->furtran?->billedValue,
                'claimedValue' => $invoice->furtran?->claimedValue,
                'serviceEnabledIndication' => $invoice->furtran?->serviceEnabledIndication,

                'policy_number' => $invoice?->typeable?->policy_number,
                'policy_start_date' => formatDateToArray($invoice?->typeable?->start_date),
                'policy_end_date' => formatDateToArray($invoice?->typeable?->end_date),

                'ipsName' => $invoice->furtran?->ipsName,
                'ipsNit' => $invoice->furtran?->ipsNit,
                'ipsAddress' => $invoice->furtran?->ipsAddress,
                'ipsReceptionHabilitation_code' => $invoice->furtran?->ipsReceptionHabilitationCode?->codigo,
                'ipsPhone' => $invoice->furtran?->ipsPhone,

                'insurance_status' => $invoice?->typeable?->insurance_statuse?->code,
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
        $furtran = $this->furtranRepository->find($id);

        $data = [
            '1' => $furtran->previousRecordNumber,
            '2' => $furtran->rgResponse?->Value(),
            '3' => $furtran->invoice?->invoice_number,
            '4' => $furtran->invoice?->serviceVendor?->ipsable?->codigo,
            '5' => $furtran->firstLastNameClaimant,
            '6' => $furtran->secondLastNameClaimant,
            '7' => $furtran->firstNameClaimant,
            '8' => $furtran->secondNameClaimant,
            '9' => $furtran->claimantIdType?->codigo,
            '10' => $furtran->claimantIdNumber,
            '11' => $furtran->vehicleServiceType?->Value(),
            '12' => $furtran->vehiclePlate,
            '13' => $furtran->claimantAddress,
            '14' => $furtran->claimantPhone,
            '15' => $furtran->claimantDepartmentCode?->codigo,
            '16' => $furtran->claimantMunicipalityCode?->codigo,
            '17' => $furtran->invoice?->patient?->typeDocument?->codigo,
            '18' => $furtran->invoice?->patient?->document,
            '19' => $furtran->invoice?->patient?->first_name,
            '20' => $furtran->invoice?->patient?->second_name,
            '21' => $furtran->invoice?->patient?->first_surname,
            '22' => $furtran->invoice?->patient?->second_surname,
            '23' => $furtran->invoice?->patient?->birth_date,
            '24' => $furtran->victimGender?->Value(),
            '25' => $furtran->eventType?->Value(),
            '26' => $furtran->pickupAddress,
            '27' => $furtran->pickupDepartmentCode?->codigo,
            '28' => $furtran->pickupMunicipalityCode?->codigo,
            '29' => $furtran->pickupZone?->Value(),
            '30' => $furtran->transferDate,
            '31' => $furtran->transferTime,
            '32' => $furtran->ipsReceptionHabilitationCode?->codigo,
            '33' => $furtran->transferPickupDepartmentCode?->codigo,
            '34' => $furtran->transferPickupMunicipalityCode?->codigo,
            '35' => $furtran->victimCondition?->Value(),
            '36' => $furtran->invoice?->typeable?->insurance_statuse?->code,
            '37' => $furtran->involvedVehicleType?->Value(),
            '38' => $furtran->involvedVehiclePlate,
            '39' => $furtran->insurerCode,
            '40' => $furtran->invoice?->typeable?->policy_number,
            '41' => $furtran->invoice?->typeable?->start_date,
            '42' => $furtran->invoice?->typeable?->end_date,
            '43' => $furtran->sirasRecordNumber,
            '44' => $furtran->billedValue,
            '45' => $furtran->claimedValue,
            '46' => $furtran->serviceEnabledIndication?->Value(),
        ];

        // Generate comma-separated text content
        $textContent = implode(',', array_map(function ($value) {
            return $value ?? '';
        }, $data)) . "\n";

        // Define file name
        $fileName = 'furtran_' . $id . '.txt';

        // Return response with text file for download
        return response($textContent, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}

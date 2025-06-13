<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Http\Requests\Furips1\Furips1StoreRequest;
use App\Http\Resources\Furips1\Furips1FormResource;
use App\Http\Resources\Furips1\Furips1PaginateResource;
use App\Repositories\Furips1Repository;
use App\Repositories\InvoiceRepository;
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

            $invoice = $this->invoiceRepository->find($invoice_id, with: ['typeable:id,insurance_statuse_id', 'typeable.insurance_statuse:id,code'], select: ['id', 'type', 'typeable_type', 'typeable_id']);
            $invoice = [
                'id' => $invoice->id,
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

            $tipoIdPisis = [
                'ownerDocumentType_arrayInfo' => $tipoIdPisis1['tipoIdPisis_arrayInfo'],
                'driverDocumentType_arrayInfo' => $tipoIdPisis2['tipoIdPisis_arrayInfo'],
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

            $this->cacheService->clearByPrefix($this->key_redis_project . 'string:invoices_paginate*');

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

            $invoice = $this->invoiceRepository->find($furips1->invoice_id, with: ['typeable:id,insurance_statuse_id', 'typeable.insurance_statuse:id,code'], select: ['id', 'type', 'typeable_type', 'typeable_id']);
            $invoice = [
                'id' => $invoice->id,
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

            $tipoIdPisis = [
                'ownerDocumentType_arrayInfo' => $tipoIdPisis1['tipoIdPisis_arrayInfo'],
                'driverDocumentType_arrayInfo' => $tipoIdPisis2['tipoIdPisis_arrayInfo'],
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
}

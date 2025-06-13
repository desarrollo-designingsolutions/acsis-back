<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Http\Requests\Fultran\FultranStoreRequest;
use App\Http\Resources\Fultran\FultranFormResource;
use App\Http\Resources\Fultran\FultranPaginateResource;
use App\Repositories\FultranRepository;
use App\Repositories\InvoiceRepository;
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
}

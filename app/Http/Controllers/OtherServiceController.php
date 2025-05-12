<?php

namespace App\Http\Controllers;

use App\Enums\Service\TypeServiceEnum;
use App\Http\Requests\OtherService\OtherServiceStoreRequest;
use App\Http\Resources\OtherService\OtherServiceFormResource;
use App\Repositories\InvoiceRepository;
use App\Repositories\OtherServiceRepository;
use App\Repositories\ServiceRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;

class OtherServiceController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected OtherServiceRepository $otherServiceRepository,
        protected ServiceRepository $serviceRepository,
        protected InvoiceRepository $invoiceRepository,
        protected QueryController $queryController,
    ) {}

    public function create(Request $request)
    {
        return $this->execute(function () use ($request) {

            $tipoOtrosServicios = $this->queryController->selectInfiniteTipoOtrosServicios(request());
            $conceptoRecaudo = $this->queryController->selectInfiniteConceptoRecaudo(request());
            $cupsRips = $this->queryController->selectInfiniteCupsRips(request());

            return [
                'code' => 200,
                ...$tipoOtrosServicios,
                ...$conceptoRecaudo,
                ...$cupsRips,
            ];
        });
    }

    public function store(OtherServiceStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            $postOtherService = $request->except(["service_id", "company_id", "invoice_id"]);

            $otherService = $this->otherServiceRepository->store($postOtherService);

            $postService = [
                "company_id" => $request->input("company_id"),
                "invoice_id" => $request->input("invoice_id"),
                "type" => TypeServiceEnum::SERVICE_TYPE_007,
                "serviceable_type" => TypeServiceEnum::SERVICE_TYPE_007->model(),
                "serviceable_id" => $otherService->id,
                "codigo_servicio" => $request->input("codTecnologiaSalud"),
                "nombre_servicio" => $request->input("nomTecnologiaSalud"),
                "quantity" => $request->input("cantidadOS"),
                "unit_value" => $request->input("vrUnitOS"),
                "total_value" => $request->input("vrServicio"),
            ];

            $service = $this->serviceRepository->store($postService);

            return [
                'code' => 200,
                'message' => 'Servicio agregado correctamente',
            ];
        });
    }

    public function edit($service_id)
    {
        return $this->execute(function () use ($service_id) {

            $service = $this->serviceRepository->find($service_id);

            $otherService = $service->serviceable;
            $form = new OtherServiceFormResource($otherService);

            $tipoOtrosServicios = $this->queryController->selectInfiniteTipoOtrosServicios(request());
            $conceptoRecaudo = $this->queryController->selectInfiniteConceptoRecaudo(request());
            $cupsRips = $this->queryController->selectInfiniteCupsRips(request());
            return [
                'code' => 200,
                'form' => $form,
                ...$tipoOtrosServicios,
                ...$conceptoRecaudo,
                ...$cupsRips,
            ];
        });
    }

    public function update(OtherServiceStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request, $id) {

            $postOtherService = $request->except(["service_id", "company_id", "invoice_id"]);

            $otherService = $this->otherServiceRepository->store($postOtherService);

            $postService = [
                "id" => $request->input("service_id"),
                "company_id" => $request->input("company_id"),
                "serviceable_id" => $otherService->id,
                "codigo_servicio" => $request->input("codTecnologiaSalud"),
                "nombre_servicio" => $request->input("nomTecnologiaSalud"),
                "quantity" => $request->input("cantidadOS"),
                "unit_value" => $request->input("vrUnitOS"),
                "total_value" => $request->input("vrServicio"),
            ];

            $service = $this->serviceRepository->store($postService);

            return [
                'code' => 200,
                'message' => 'Servicio modificado correctamente',
            ];
        });
    }
}

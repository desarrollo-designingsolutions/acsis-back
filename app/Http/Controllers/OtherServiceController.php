<?php

namespace App\Http\Controllers;

use App\Enums\Service\TypeServiceEnum;
use App\Helpers\Constants;
use App\Http\Requests\OtherService\OtherServiceStoreRequest;
use App\Http\Resources\OtherService\OtherServiceFormResource;
use App\Models\Service;
use App\Repositories\InvoiceRepository;
use App\Repositories\OtherServiceRepository;
use App\Repositories\ServiceRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

            $post = $request->all();

            // Get the next consecutivo
            $consecutivo = getNextConsecutivo($post['invoice_id'], TypeServiceEnum::SERVICE_TYPE_007);

            // Create OtherService
            $otherService = $this->otherServiceRepository->store([
                'idMIPRES' => $post['idMIPRES'],
                'numAutorizacion' => $post['numAutorizacion'],
                'fechaSuministroTecnologia' => $post['fechaSuministroTecnologia'],
                'tipoOS_id' => $post['tipoOS_id'],
                'codTecnologiaSalud' => $post['codTecnologiaSalud'],
                'nomTecnologiaSalud' => $post['nomTecnologiaSalud'],
                'cantidadOS' => $post['cantidadOS'],
                'vrUnitOS' => $post['vrUnitOS'],
                'valorPagoModerador' => $post['valorPagoModerador'],
                'vrServicio' => $post['vrServicio'],
                'conceptoRecaudo_id' => $post['conceptoRecaudo_id'],
            ]);

            // Create Service
            $service = $this->serviceRepository->store([
                'company_id' => $post['company_id'],
                'invoice_id' => $post['invoice_id'],
                'consecutivo' => $consecutivo,
                'type' => TypeServiceEnum::SERVICE_TYPE_007,
                'serviceable_type' => TypeServiceEnum::SERVICE_TYPE_007->model(),
                'serviceable_id' => $otherService->id,
                'codigo_servicio' => $post['codTecnologiaSalud'],
                'nombre_servicio' => $post['nomTecnologiaSalud'],
                'quantity' => $post['cantidadOS'],
                'unit_value' => $post['vrUnitOS'],
                'total_value' => $post['vrServicio'],
            ]);

            // Prepare service data for JSON
            $serviceData = [
                'codPrestador' => '',
                'numAutorizacion' => $post['numAutorizacion'],
                'idMIPRES' => $post['idMIPRES'],
                'fechaSuministroTecnologia' => $post['fechaSuministroTecnologia'],
                'tipoOS' => $otherService->tipoOtrosServicio?->codigo,
                'codTecnologiaSalud' => $post['codTecnologiaSalud'],
                'nomTecnologiaSalud' => $post['nomTecnologiaSalud'],
                'cantidadOS' => $post['cantidadOS'],
                'tipoDocumentoldentificacion' => "",
                'numDocumentoldentificacion' => "",
                'vrUnitOS' => $post['vrUnitOS'],
                'vrServicio' => $post['vrServicio'],
                'conceptoRecaudo' => $otherService->conceptoRecaudo?->codigo,
                'valorPagoModerador' => $post['valorPagoModerador'],
                'numFEVPagoModerador' => '',
                'consecutivo' => $consecutivo,
            ];

            // Update JSON with new service
            updateInvoiceServicesJson(
                $post['invoice_id'],
                TypeServiceEnum::SERVICE_TYPE_007,
                $serviceData,
                'add'
            );

            return [
                'code' => 200,
                'message' => 'Servicio agregado correctamente',
            ];
        }, debug: false);
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

            $post = $request->all();

            // Update OtherService
            $otherService = $this->otherServiceRepository->store([
                'numAutorizacion' => $post['numAutorizacion'],
                'idMIPRES' => $post['idMIPRES'],
                'fechaSuministroTecnologia' => $post['fechaSuministroTecnologia'],
                'tipoOS_id' => $post['tipoOS_id'],
                'codTecnologiaSalud' => $post['codTecnologiaSalud'],
                'nomTecnologiaSalud' => $post['nomTecnologiaSalud'],
                'cantidadOS' => $post['cantidadOS'],
                'vrUnitOS' => $post['vrUnitOS'],
                'valorPagoModerador' => $post['valorPagoModerador'],
                'vrServicio' => $post['vrServicio'],
                'conceptoRecaudo_id' => $post['conceptoRecaudo_id'],
            ], $id);

            // Update Service
            $service = $this->serviceRepository->store([
                'codigo_servicio' => $post['codTecnologiaSalud'],
                'nombre_servicio' => $post['nomTecnologiaSalud'],
                'quantity' => $post['cantidadOS'],
                'unit_value' => $post['vrUnitOS'],
                'total_value' => $post['vrServicio'],
            ], $post['service_id']);

            // Store the current consecutivo
            $consecutivo = $service->consecutivo;

            // Prepare service data for JSON
            $serviceData = [
                'codPrestador' => '',
                'numAutorizacion' => $post['numAutorizacion'],
                'idMIPRES' => $post['idMIPRES'],
                'fechaSuministroTecnologia' => $post['fechaSuministroTecnologia'],
                'tipoOS' => $otherService->tipoOtrosServicio?->codigo,
                'codTecnologiaSalud' => $post['codTecnologiaSalud'],
                'nomTecnologiaSalud' => $post['nomTecnologiaSalud'],
                'cantidadOS' => $post['cantidadOS'],
                'tipoDocumentoldentificacion' => "",
                'numDocumentoldentificacion' => "",
                'vrUnitOS' => $post['vrUnitOS'],
                'vrServicio' => $post['vrServicio'],
                'conceptoRecaudo' => $otherService->conceptoRecaudo?->codigo,
                'valorPagoModerador' => $post['valorPagoModerador'],
                'numFEVPagoModerador' => '',
                'consecutivo' => $consecutivo,
            ];

            // // Update JSON with edited service
            // updateInvoiceServicesJson(
            //     $post['invoice_id'],
            //     TypeServiceEnum::SERVICE_TYPE_007,
            //     $serviceData,
            //     'edit',
            //     $consecutivo
            // );

            return [
                'code' => 200,
                'message' => 'Servicio modificado correctamente',
            ];
        }, debug: false);
    }
}

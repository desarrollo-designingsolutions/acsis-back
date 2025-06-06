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
use Carbon\Carbon;
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
        return $this->execute(function () {

            $tipoOtrosServicios = $this->queryController->selectInfiniteTipoOtrosServicios(request());
            $cupsRips = $this->queryController->selectInfiniteCupsRips(request());

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_TIPODOCUMENTOIDENTIFICACION]);
            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_OTHERSERVICE_CONCEPTORECAUDO]);
            $conceptoRecaudo = $this->queryController->selectInfiniteConceptoRecaudo($newRequest);

            $invoice = $this->invoiceRepository->find(request('invoice_id'), select: ['id', 'invoice_date']);

            return [
                'code' => 200,
                'invoice' => $invoice,
                ...$tipoOtrosServicios,
                ...$conceptoRecaudo,
                ...$cupsRips,
                ...$tipoDocumento,
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
                'tipoDocumentoIdentificacion_id' => $post['tipoDocumentoIdentificacion_id'],
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'numFEVPagoModerador' => $post['numFEVPagoModerador'],
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
                'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo,
                'numAutorizacion' => $post['numAutorizacion'] ?? '',
                'idMIPRES' => $post['idMIPRES'] ?? null,
                'fechaSuministroTecnologia' => Carbon::parse($post['fechaSuministroTecnologia'])->format('Y-m-d H:i'),
                'tipoOS' => $otherService->tipoOtrosServicio?->codigo,
                'codTecnologiaSalud' => $post['codTecnologiaSalud'],
                'nomTecnologiaSalud' => $post['nomTecnologiaSalud'],
                'cantidadOS' => intval($post['cantidadOS']),
                'tipoDocumentoIdentificacion' => $otherService->tipoDocumentoIdentificacion?->codigo,
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'vrUnitOS' => floatval($post['vrUnitOS']),
                'vrServicio' => floatval($post['vrServicio']),
                'conceptoRecaudo' => $otherService->conceptoRecaudo?->codigo,
                'valorPagoModerador' => floatval($post['valorPagoModerador']),
                'numFEVPagoModerador' => $post['numFEVPagoModerador'],
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
            $cupsRips = $this->queryController->selectInfiniteCupsRips(request());

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_TIPODOCUMENTOIDENTIFICACION]);
            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_OTHERSERVICE_CONCEPTORECAUDO]);
            $conceptoRecaudo = $this->queryController->selectInfiniteConceptoRecaudo($newRequest);

            $invoice = $this->invoiceRepository->find(request('invoice_id'), select: ['id', 'invoice_date']);

            return [
                'code' => 200,
                'form' => $form,
                'invoice' => $invoice,
                ...$tipoOtrosServicios,
                ...$conceptoRecaudo,
                ...$cupsRips,
                ...$tipoDocumento,
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
                'tipoDocumentoIdentificacion_id' => $post['tipoDocumentoIdentificacion_id'],
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'numFEVPagoModerador' => $post['numFEVPagoModerador'],
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
                'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo,
                'numAutorizacion' => $post['numAutorizacion'] ?? '',
                'idMIPRES' => $post['idMIPRES'] ?? null,
                'fechaSuministroTecnologia' => Carbon::parse($post['fechaSuministroTecnologia'])->format('Y-m-d H:i'),
                'tipoOS' => $otherService->tipoOtrosServicio?->codigo,
                'codTecnologiaSalud' => $post['codTecnologiaSalud'],
                'nomTecnologiaSalud' => $post['nomTecnologiaSalud'],
                'cantidadOS' => intval($post['cantidadOS']),
                'tipoDocumentoIdentificacion' => $otherService->tipoDocumentoIdentificacion?->codigo,
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'vrUnitOS' => floatval($post['vrUnitOS']),
                'vrServicio' => floatval($post['vrServicio']),
                'conceptoRecaudo' => $otherService->conceptoRecaudo?->codigo,
                'valorPagoModerador' => floatval($post['valorPagoModerador']),
                'numFEVPagoModerador' => $post['numFEVPagoModerador'],
                'consecutivo' => $consecutivo,
            ];

            // Update JSON with edited service
            updateInvoiceServicesJson(
                $post['invoice_id'],
                TypeServiceEnum::SERVICE_TYPE_007,
                $serviceData,
                'edit',
                $consecutivo
            );

            return [
                'code' => 200,
                'message' => 'Servicio modificado correctamente',
            ];
        }, debug: false);
    }
}

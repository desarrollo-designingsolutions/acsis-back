<?php

namespace App\Http\Controllers;

use App\Enums\Service\TypeServiceEnum;
use App\Http\Requests\Hospitalization\HospitalizationStoreRequest;
use App\Http\Resources\Hospitalization\HospitalizationFormResource;
use App\Repositories\HospitalizationRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\ServiceRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HospitalizationController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected HospitalizationRepository $hospitalizationRepository,
        protected ServiceRepository $serviceRepository,
        protected InvoiceRepository $invoiceRepository,
        protected QueryController $queryController,
    ) {}

    public function create(Request $request)
    {
        return $this->execute(function () {

            $viaIngresoUsuario = $this->queryController->selectInfiniteViaIngresoUsuario(request());
            $ripsCausaExternaVersion2 = $this->queryController->selectInfiniteRipsCausaExternaVersion2(request());
            $cie10 = $this->queryController->selectInfiniteCie10(request());
            $condicionyDestinoUsuarioEgreso = $this->queryController->selectInfiniteCondicionyDestinoUsuarioEgreso(request());

            $invoice = $this->invoiceRepository->find(request('invoice_id'), select: ['id', 'invoice_date']);

            return [
                'code' => 200,
                'invoice' => $invoice,
                ...$viaIngresoUsuario,
                ...$ripsCausaExternaVersion2,
                ...$cie10,
                ...$condicionyDestinoUsuarioEgreso,
            ];
        });
    }

    public function store(HospitalizationStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->all();

            // Get the next consecutivo
            $consecutivo = getNextConsecutivo($post['invoice_id'], TypeServiceEnum::SERVICE_TYPE_004);

            // Create Hospitalization
            $hospitalization = $this->hospitalizationRepository->store([
                'viaIngresoServicioSalud_id' => $post['viaIngresoServicioSalud_id'],
                'fechaInicioAtencion' => $post['fechaInicioAtencion'],
                'numAutorizacion' => $post['numAutorizacion'],
                'causaMotivoAtencion_id' => $post['causaMotivoAtencion_id'],
                'codDiagnosticoPrincipal_id' => $post['codDiagnosticoPrincipal_id'],
                'codDiagnosticoPrincipalE_id' => $post['codDiagnosticoPrincipalE_id'],
                'codDiagnosticoRelacionadoE1_id' => $post['codDiagnosticoRelacionadoE1_id'],
                'codDiagnosticoRelacionadoE2_id' => $post['codDiagnosticoRelacionadoE2_id'],
                'codDiagnosticoRelacionadoE3_id' => $post['codDiagnosticoRelacionadoE3_id'],
                'codComplicacion_id' => $post['codComplicacion_id'],
                'condicionDestinoUsuarioEgreso_id' => $post['condicionDestinoUsuarioEgreso_id'],
                'codDiagnosticoCausaMuerte_id' => $post['codDiagnosticoCausaMuerte_id'],
                'fechaEgreso' => $post['fechaEgreso'],
            ]);

            // Create Service
            $service = $this->serviceRepository->store([
                'company_id' => $post['company_id'],
                'invoice_id' => $post['invoice_id'],
                'consecutivo' => $consecutivo,
                'type' => TypeServiceEnum::SERVICE_TYPE_004,
                'serviceable_type' => TypeServiceEnum::SERVICE_TYPE_004->model(),
                'serviceable_id' => $hospitalization->id,
                'codigo_servicio' => null,
                'nombre_servicio' => null,
                'quantity' => 1,
                'unit_value' => 0,
                'total_value' => 0,
            ]);

            // Prepare service data for JSON
            $serviceData = [
                'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo,
                'viaIngresoServicioSalud' => $hospitalization->viaIngresoServicioSalud?->codigo,
                'fechaInicioAtencion' => Carbon::parse($post['fechaInicioAtencion'])->format('Y-m-d H:i'),
                'numAutorizacion' => $post['numAutorizacion'] ?? null,
                'causaMotivoAtencion' => $hospitalization->causaMotivoAtencion?->codigo,
                'codDiagnosticoPrincipal' => $hospitalization->codDiagnosticoPrincipal?->codigo,
                'codDiagnosticoPrincipalE' => $hospitalization->codDiagnosticoPrincipalE?->codigo,
                'codDiagnosticoRelacionadoE1' => $hospitalization->codDiagnosticoRelacionadoE1?->codigo ?? '',
                'codDiagnosticoRelacionadoE2' => $hospitalization->codDiagnosticoRelacionadoE2?->codigo ?? '',
                'codDiagnosticoRelacionadoE3' => $hospitalization->codDiagnosticoRelacionadoE3?->codigo ?? '',
                'codComplicacion' => $hospitalization->codComplicacion?->codigo ?? '',
                'condicionDestinoUsuarioEgreso' => $hospitalization->condicionDestinoUsuarioEgreso?->codigo,
                'codDiagnosticoCausaMuerte' => $hospitalization->codDiagnosticoCausaMuerte?->codigo ?? '',
                'fechaEgreso' => Carbon::parse($post['fechaEgreso'])->format('Y-m-d H:i'),
                'consecutivo' => $consecutivo,
            ];

            // Update JSON with new service
            updateInvoiceServicesJson(
                $post['invoice_id'],
                TypeServiceEnum::SERVICE_TYPE_004,
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

            $hospitalization = $service->serviceable;
            $form = new HospitalizationFormResource($hospitalization);

            $viaIngresoUsuario = $this->queryController->selectInfiniteViaIngresoUsuario(request());
            $ripsCausaExternaVersion2 = $this->queryController->selectInfiniteRipsCausaExternaVersion2(request());
            $cie10 = $this->queryController->selectInfiniteCie10(request());
            $condicionyDestinoUsuarioEgreso = $this->queryController->selectInfiniteCondicionyDestinoUsuarioEgreso(request());

            $invoice = $this->invoiceRepository->find(request('invoice_id'), select: ['id', 'invoice_date']);

            return [
                'code' => 200,
                'form' => $form,
                'invoice' => $invoice,
                ...$viaIngresoUsuario,
                ...$cie10,
                ...$ripsCausaExternaVersion2,
                ...$condicionyDestinoUsuarioEgreso,
            ];
        });
    }

    public function update(HospitalizationStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request, $id) {

            $post = $request->all();

            // Update Hospitalization
            $hospitalization = $this->hospitalizationRepository->store([
                'viaIngresoServicioSalud_id' => $post['viaIngresoServicioSalud_id'],
                'fechaInicioAtencion' => $post['fechaInicioAtencion'],
                'numAutorizacion' => $post['numAutorizacion'],
                'causaMotivoAtencion_id' => $post['causaMotivoAtencion_id'],
                'codDiagnosticoPrincipal_id' => $post['codDiagnosticoPrincipal_id'],
                'codDiagnosticoPrincipalE_id' => $post['codDiagnosticoPrincipalE_id'],
                'codDiagnosticoRelacionadoE1_id' => $post['codDiagnosticoRelacionadoE1_id'],
                'codDiagnosticoRelacionadoE2_id' => $post['codDiagnosticoRelacionadoE2_id'],
                'codDiagnosticoRelacionadoE3_id' => $post['codDiagnosticoRelacionadoE3_id'],
                'codComplicacion_id' => $post['codComplicacion_id'],
                'condicionDestinoUsuarioEgreso_id' => $post['condicionDestinoUsuarioEgreso_id'],
                'codDiagnosticoCausaMuerte_id' => $post['codDiagnosticoCausaMuerte_id'],
                'fechaEgreso' => $post['fechaEgreso'],
            ], $id);

            // Update Service
            $service = $this->serviceRepository->store([
                'codigo_servicio' => null,
                'nombre_servicio' => null,
                'quantity' => 1,
                'unit_value' => 0,
                'total_value' => 0,
            ], $post['service_id']);

            // Store the current consecutivo
            $consecutivo = $service->consecutivo;

            // Prepare service data for JSON
            $serviceData = [
                'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo,
                'viaIngresoServicioSalud' => $hospitalization->viaIngresoServicioSalud?->codigo,
                'fechaInicioAtencion' => Carbon::parse($post['fechaInicioAtencion'])->format('Y-m-d H:i'),
                'numAutorizacion' => $post['numAutorizacion'] ?? null,
                'causaMotivoAtencion' => $hospitalization->causaMotivoAtencion?->codigo,
                'codDiagnosticoPrincipal' => $hospitalization->codDiagnosticoPrincipal?->codigo,
                'codDiagnosticoPrincipalE' => $hospitalization->codDiagnosticoPrincipalE?->codigo,
                'codDiagnosticoRelacionadoE1' => $hospitalization->codDiagnosticoRelacionadoE1?->codigo ?? '',
                'codDiagnosticoRelacionadoE2' => $hospitalization->codDiagnosticoRelacionadoE2?->codigo ?? '',
                'codDiagnosticoRelacionadoE3' => $hospitalization->codDiagnosticoRelacionadoE3?->codigo ?? '',
                'codComplicacion' => $hospitalization->codComplicacion?->codigo ?? '',
                'condicionDestinoUsuarioEgreso' => $hospitalization->condicionDestinoUsuarioEgreso?->codigo,
                'codDiagnosticoCausaMuerte' => $hospitalization->codDiagnosticoCausaMuerte?->codigo ?? '',
                'fechaEgreso' => Carbon::parse($post['fechaEgreso'])->format('Y-m-d H:i'),
                'consecutivo' => $consecutivo,
            ];

            // Update JSON with edited service
            updateInvoiceServicesJson(
                $post['invoice_id'],
                TypeServiceEnum::SERVICE_TYPE_004,
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

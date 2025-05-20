<?php

namespace App\Http\Controllers;

use App\Enums\Service\TypeServiceEnum;
use App\Http\Requests\Procedure\ProcedureStoreRequest;
use App\Http\Resources\Procedure\ProcedureFormResource;
use App\Repositories\InvoiceRepository;
use App\Repositories\ProcedureRepository;
use App\Repositories\ServiceRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;

class ProcedureController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected ProcedureRepository $procedureRepository,
        protected ServiceRepository $serviceRepository,
        protected InvoiceRepository $invoiceRepository,
        protected QueryController $queryController,
    ) {}

    public function create(Request $request)
    {
        return $this->execute(function () {

            $cupsRips = $this->queryController->selectInfiniteCupsRips(request());
            $viaIngresoUsuario = $this->queryController->selectInfiniteViaIngresoUsuario(request());
            $modalidadAtencion = $this->queryController->selectInfiniteModalidadAtencion(request());
            $grupoServicio = $this->queryController->selectInfiniteGrupoServicio(request());
            $servicio = $this->queryController->selectInfiniteServicio(request());
            $ripsFinalidadConsultaVersion2 = $this->queryController->selectInfiniteRipsFinalidadConsultaVersion2(request());
            $cie10 = $this->queryController->selectInfiniteCie10(request());
            $conceptoRecaudo = $this->queryController->selectInfiniteConceptoRecaudo(request());
            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis(request());

            return [
                'code' => 200,
                ...$cupsRips,
                ...$viaIngresoUsuario,
                ...$modalidadAtencion,
                ...$grupoServicio,
                ...$servicio,
                ...$ripsFinalidadConsultaVersion2,
                ...$cie10,
                ...$conceptoRecaudo,
                ...$tipoDocumento,
            ];
        });
    }

    public function store(ProcedureStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->all();

            // Get the next consecutivo
            $consecutivo = getNextConsecutivo($post['invoice_id'], TypeServiceEnum::SERVICE_TYPE_002);

            // Create MedicalConsultation
            $procedure = $this->procedureRepository->store([
                'fechaInicioAtencion' => $post['fechaInicioAtencion'],
                'idMIPRES' => $post['idMIPRES'],
                'numAutorizacion' => $post['numAutorizacion'],
                'codProcedimiento_id' => $post['codProcedimiento_id'],
                'viaIngresoServicioSalud_id' => $post['viaIngresoServicioSalud_id'],
                'modalidadGrupoServicioTecSal_id' => $post['modalidadGrupoServicioTecSal_id'],
                'grupoServicios_id' => $post['grupoServicios_id'],
                'codServicio_id' => $post['codServicio_id'],
                'finalidadTecnologiaSalud_id' => $post['finalidadTecnologiaSalud_id'],
                'codDiagnosticoPrincipal_id' => $post['codDiagnosticoPrincipal_id'],
                'codDiagnosticoRelacionado_id' => $post['codDiagnosticoRelacionado_id'],
                'codComplicacion_id' => $post['codComplicacion_id'],
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
                'type' => TypeServiceEnum::SERVICE_TYPE_002,
                'serviceable_type' => TypeServiceEnum::SERVICE_TYPE_002->model(),
                'serviceable_id' => $procedure->id,
                'codigo_servicio' => $procedure->codProcedimiento->codigo,
                'nombre_servicio' => $procedure->codProcedimiento->nombre,
                'quantity' => 1,
                'unit_value' => $post['vrServicio'],
                'total_value' => $post['vrServicio'],
            ]);

            // Prepare service data for JSON
            $serviceData = [
                'codPrestador' => $service->invoice?->serviceVendor?->ips_no_rep?->codigo,
                'fechaInicioAtencion' => $post['fechaInicioAtencion'],
                'idMIPRES' => $post['idMIPRES'],
                'numAutorizacion' => $post['numAutorizacion'],
                'codProcedimiento' => $procedure->codProcedimiento->codigo,
                'viaIngresoServicioSalud' => $procedure->viaIngresoServicioSalud->codigo,
                'modalidadGrupoServicioTecSal' => $procedure->modalidadGrupoServicioTecSal->codigo,
                'grupoServicios' => $procedure->grupoServicios->codigo,
                'codServicio' => $procedure->codServicio->codigo,
                'finalidadTecnologiaSalud' => $procedure->finalidadTecnologiaSalud->codigo,
                'tipoDocumentoIdentificacion' => $procedure->tipoDocumentoIdentificacion->codigo,
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'codDiagnosticoPrincipal' => $procedure->codDiagnosticoPrincipal->codigo,
                'codDiagnosticoRelacionado' => $procedure->codDiagnosticoRelacionado?->codigo,
                'codComplicacion' => $procedure->codComplicacion->codigo,
                'valorPagoModerador' => $post['valorPagoModerador'],
                'numFEVPagoModerador' => $post['numFEVPagoModerador'],
                'consecutivo' => $consecutivo,
                'vrServicio' => $post['vrServicio'],
                'conceptoRecaudo' => $procedure->conceptoRecaudo?->codigo,
            ];

            // Update JSON with new service
            updateInvoiceServicesJson(
                $post['invoice_id'],
                TypeServiceEnum::SERVICE_TYPE_002,
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

            $procedure = $service->serviceable;
            $form = new ProcedureFormResource($procedure);

            $cupsRips = $this->queryController->selectInfiniteCupsRips(request());
            $viaIngresoUsuario = $this->queryController->selectInfiniteViaIngresoUsuario(request());
            $modalidadAtencion = $this->queryController->selectInfiniteModalidadAtencion(request());
            $grupoServicio = $this->queryController->selectInfiniteGrupoServicio(request());
            $servicio = $this->queryController->selectInfiniteServicio(request());
            $ripsFinalidadConsultaVersion2 = $this->queryController->selectInfiniteRipsFinalidadConsultaVersion2(request());
            $cie10 = $this->queryController->selectInfiniteCie10(request());
            $conceptoRecaudo = $this->queryController->selectInfiniteConceptoRecaudo(request());
            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis(request());

            return [
                'code' => 200,
                'form' => $form,
                ...$cupsRips,
                ...$viaIngresoUsuario,
                ...$modalidadAtencion,
                ...$grupoServicio,
                ...$servicio,
                ...$ripsFinalidadConsultaVersion2,
                ...$cie10,
                ...$conceptoRecaudo,
                ...$tipoDocumento,
            ];
        });
    }

    public function update(ProcedureStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request, $id) {

            $post = $request->all();

            // Update MedicalConsultation
            $procedure = $this->procedureRepository->store([
                'fechaInicioAtencion' => $post['fechaInicioAtencion'],
                'idMIPRES' => $post['idMIPRES'],
                'numAutorizacion' => $post['numAutorizacion'],
                'codProcedimiento_id' => $post['codProcedimiento_id'],
                'viaIngresoServicioSalud_id' => $post['viaIngresoServicioSalud_id'],
                'modalidadGrupoServicioTecSal_id' => $post['modalidadGrupoServicioTecSal_id'],
                'grupoServicios_id' => $post['grupoServicios_id'],
                'codServicio_id' => $post['codServicio_id'],
                'finalidadTecnologiaSalud_id' => $post['finalidadTecnologiaSalud_id'],
                'codDiagnosticoPrincipal_id' => $post['codDiagnosticoPrincipal_id'],
                'codDiagnosticoRelacionado_id' => $post['codDiagnosticoRelacionado_id'],
                'codComplicacion_id' => $post['codComplicacion_id'],
                'valorPagoModerador' => $post['valorPagoModerador'],
                'vrServicio' => $post['vrServicio'],
                'conceptoRecaudo_id' => $post['conceptoRecaudo_id'],
                'tipoDocumentoIdentificacion_id' => $post['tipoDocumentoIdentificacion_id'],
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'numFEVPagoModerador' => $post['numFEVPagoModerador'],
            ], $id);

            // Update Service
            $service = $this->serviceRepository->store([
                'codigo_servicio' => $procedure->codProcedimiento->codigo,
                'nombre_servicio' => $procedure->codProcedimiento->nombre,
                'quantity' => 1,
                'unit_value' => $post['vrServicio'],
                'total_value' => $post['vrServicio'],
            ], $post['service_id']);

            // Store the current consecutivo
            $consecutivo = $service->consecutivo;

            // Prepare service data for JSON
            $serviceData = [
                'codPrestador' => $service->invoice?->serviceVendor?->ips_no_rep?->codigo,
                'fechaInicioAtencion' => $post['fechaInicioAtencion'],
                'idMIPRES' => $post['idMIPRES'],
                'numAutorizacion' => $post['numAutorizacion'],
                'codProcedimiento' => $procedure->codProcedimiento->codigo,
                'viaIngresoServicioSalud' => $procedure->viaIngresoServicioSalud->codigo,
                'modalidadGrupoServicioTecSal' => $procedure->modalidadGrupoServicioTecSal->codigo,
                'grupoServicios' => $procedure->grupoServicios->codigo,
                'codServicio' => $procedure->codServicio->codigo,
                'finalidadTecnologiaSalud' => $procedure->finalidadTecnologiaSalud->codigo,
                'tipoDocumentoIdentificacion' => $procedure->tipoDocumentoIdentificacion->codigo,
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'codDiagnosticoPrincipal' => $procedure->codDiagnosticoPrincipal->codigo,
                'codDiagnosticoRelacionado' => $procedure->codDiagnosticoRelacionado?->codigo,
                'codComplicacion' => $procedure->codComplicacion->codigo,
                'valorPagoModerador' => $post['valorPagoModerador'],
                'numFEVPagoModerador' => $post['numFEVPagoModerador'],
                'consecutivo' => $consecutivo,
                'vrServicio' => $post['vrServicio'],
                'conceptoRecaudo' => $procedure->conceptoRecaudo?->codigo,
            ];

            // Update JSON with edited service
            updateInvoiceServicesJson(
                $post['invoice_id'],
                TypeServiceEnum::SERVICE_TYPE_002,
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

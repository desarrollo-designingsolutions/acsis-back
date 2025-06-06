<?php

namespace App\Http\Controllers;

use App\Enums\Service\TypeServiceEnum;
use App\Helpers\Constants;
use App\Http\Requests\MedicalConsultation\MedicalConsultationStoreRequest;
use App\Http\Resources\MedicalConsultation\MedicalConsultationFormResource;
use App\Repositories\InvoiceRepository;
use App\Repositories\MedicalConsultationRepository;
use App\Repositories\ServiceRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MedicalConsultationController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected MedicalConsultationRepository $medicalConsultationRepository,
        protected ServiceRepository $serviceRepository,
        protected InvoiceRepository $invoiceRepository,
        protected QueryController $queryController,
    ) {}

    public function create(Request $request)
    {
        return $this->execute(function () {

            $cupsRips = $this->queryController->selectInfiniteCupsRips(request());

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_MEDICAL_CONSULTATION_FINALIDADTECNOLOGIASALUD]);
            $ripsFinalidadConsultaVersion2 = $this->queryController->selectInfiniteRipsFinalidadConsultaVersion2($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_MEDICAL_CONSULTATION_CONCEPTORECAUDO]);
            $conceptoRecaudo = $this->queryController->selectInfiniteConceptoRecaudo($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_MEDICAL_CONSULTATION_CAUSAMOTIVOATENCION]);
            $ripsCausaExternaVersion2 = $this->queryController->selectInfiniteRipsCausaExternaVersion2($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_TIPODOCUMENTOIDENTIFICACION]);
            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $modalidadAtencion = $this->queryController->selectInfiniteModalidadAtencion(request());
            $grupoServicio = $this->queryController->selectInfiniteGrupoServicio(request());
            $servicio = $this->queryController->selectInfiniteServicio(request());
            $cie10 = $this->queryController->selectInfiniteCie10(request());
            $ripsTipoDiagnosticoPrincipalVersion2 = $this->queryController->selectInfiniteRipsTipoDiagnosticoPrincipalVersion2(request());

            $invoice = $this->invoiceRepository->find(request('invoice_id'), select: ['id', 'invoice_date']);

            return [
                'code' => 200,
                'invoice' => $invoice,
                ...$cupsRips,
                ...$modalidadAtencion,
                ...$grupoServicio,
                ...$servicio,
                ...$ripsFinalidadConsultaVersion2,
                ...$cie10,
                ...$ripsTipoDiagnosticoPrincipalVersion2,
                ...$conceptoRecaudo,
                ...$ripsCausaExternaVersion2,
                ...$tipoDocumento,
            ];
        });
    }

    public function store(MedicalConsultationStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->all();

            // Get the next consecutivo
            $consecutivo = getNextConsecutivo($post['invoice_id'], TypeServiceEnum::SERVICE_TYPE_001);

            // Create MedicalConsultation
            $medicalConsultation = $this->medicalConsultationRepository->store([
                'fechaInicioAtencion' => $post['fechaInicioAtencion'],
                'numAutorizacion' => $post['numAutorizacion'],
                'codConsulta_id' => $post['codConsulta_id'],
                'modalidadGrupoServicioTecSal_id' => $post['modalidadGrupoServicioTecSal_id'],
                'grupoServicios_id' => $post['grupoServicios_id'],
                'codServicio_id' => $post['codServicio_id'],
                'finalidadTecnologiaSalud_id' => $post['finalidadTecnologiaSalud_id'],
                'causaMotivoAtencion_id' => $post['causaMotivoAtencion_id'],
                'codDiagnosticoPrincipal_id' => $post['codDiagnosticoPrincipal_id'],
                'codDiagnosticoRelacionado1_id' => $post['codDiagnosticoRelacionado1_id'],
                'codDiagnosticoRelacionado2_id' => $post['codDiagnosticoRelacionado2_id'],
                'codDiagnosticoRelacionado3_id' => $post['codDiagnosticoRelacionado3_id'],
                'tipoDiagnosticoPrincipal_id' => $post['tipoDiagnosticoPrincipal_id'],
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
                'type' => TypeServiceEnum::SERVICE_TYPE_001,
                'serviceable_type' => TypeServiceEnum::SERVICE_TYPE_001->model(),
                'serviceable_id' => $medicalConsultation->id,
                'codigo_servicio' => $medicalConsultation->codConsulta->codigo,
                'nombre_servicio' => $medicalConsultation->codConsulta->nombre,
                'quantity' => 1,
                'unit_value' => $post['vrServicio'],
                'total_value' => $post['vrServicio'],
            ]);

            // Prepare service data for JSON
            $serviceData = [
                'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo,
                'fechaInicioAtencion' => Carbon::parse($post['fechaInicioAtencion'])->format('Y-m-d H:i'),
                'numAutorizacion' => $post['numAutorizacion'] ?? null,
                'codConsulta' => $medicalConsultation->codConsulta?->codigo,
                'modalidadGrupoServicioTecSal' => $medicalConsultation->modalidadGrupoServicioTecSal?->codigo,
                'grupoServicios' => $medicalConsultation->grupoServicios?->codigo,
                'codServicio' => $medicalConsultation->codServicio?->codigo,
                'finalidadTecnologiaSalud' => $medicalConsultation->finalidadTecnologiaSalud?->codigo,
                'causaMotivoAtencion' => $medicalConsultation->causaMotivoAtencion?->codigo,
                'codDiagnosticoPrincipal' => $medicalConsultation->codDiagnosticoPrincipal?->codigo,
                'codDiagnosticoRelacionado1' => $medicalConsultation->codDiagnosticoRelacionado1?->codigo ?? '',
                'codDiagnosticoRelacionado2' => $medicalConsultation->codDiagnosticoRelacionado2?->codigo ?? '',
                'codDiagnosticoRelacionado3' => $medicalConsultation->codDiagnosticoRelacionado3?->codigo ?? '',
                'tipoDiagnosticoPrincipal' => $medicalConsultation->tipoDiagnosticoPrincipal?->codigo,
                'tipoDocumentoIdentificacion' => $medicalConsultation->tipoDocumentoIdentificacion?->codigo,
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'vrServicio' => floatval($post['vrServicio']),
                'conceptoRecaudo' => $medicalConsultation->conceptoRecaudo?->codigo,
                'valorPagoModerador' => floatval($post['valorPagoModerador']),
                'numFEVPagoModerador' => $post['numFEVPagoModerador'],
                'consecutivo' => $consecutivo,
            ];

            // Update JSON with new service
            updateInvoiceServicesJson(
                $post['invoice_id'],
                TypeServiceEnum::SERVICE_TYPE_001,
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

            $medicalConsultation = $service->serviceable;
            $form = new MedicalConsultationFormResource($medicalConsultation);

            $cupsRips = $this->queryController->selectInfiniteCupsRips(request());

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_MEDICAL_CONSULTATION_FINALIDADTECNOLOGIASALUD]);
            $ripsFinalidadConsultaVersion2 = $this->queryController->selectInfiniteRipsFinalidadConsultaVersion2($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_MEDICAL_CONSULTATION_CONCEPTORECAUDO]);
            $conceptoRecaudo = $this->queryController->selectInfiniteConceptoRecaudo($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_MEDICAL_CONSULTATION_CAUSAMOTIVOATENCION]);
            $ripsCausaExternaVersion2 = $this->queryController->selectInfiniteRipsCausaExternaVersion2($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_TIPODOCUMENTOIDENTIFICACION]);
            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $modalidadAtencion = $this->queryController->selectInfiniteModalidadAtencion(request());
            $grupoServicio = $this->queryController->selectInfiniteGrupoServicio(request());
            $servicio = $this->queryController->selectInfiniteServicio(request());
            $cie10 = $this->queryController->selectInfiniteCie10(request());
            $ripsTipoDiagnosticoPrincipalVersion2 = $this->queryController->selectInfiniteRipsTipoDiagnosticoPrincipalVersion2(request());

            $invoice = $this->invoiceRepository->find(request('invoice_id'), select: ['id', 'invoice_date']);

            return [
                'code' => 200,
                'form' => $form,
                'invoice' => $invoice,
                ...$cupsRips,
                ...$modalidadAtencion,
                ...$grupoServicio,
                ...$servicio,
                ...$ripsFinalidadConsultaVersion2,
                ...$cie10,
                ...$ripsTipoDiagnosticoPrincipalVersion2,
                ...$conceptoRecaudo,
                ...$ripsCausaExternaVersion2,
                ...$tipoDocumento,
            ];
        });
    }

    public function update(MedicalConsultationStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request, $id) {

            $post = $request->all();

            // Update MedicalConsultation
            $medicalConsultation = $this->medicalConsultationRepository->store([
                'fechaInicioAtencion' => $post['fechaInicioAtencion'],
                'numAutorizacion' => $post['numAutorizacion'] ?? null,
                'codConsulta_id' => $post['codConsulta_id'],
                'modalidadGrupoServicioTecSal_id' => $post['modalidadGrupoServicioTecSal_id'],
                'grupoServicios_id' => $post['grupoServicios_id'],
                'codServicio_id' => $post['codServicio_id'],
                'finalidadTecnologiaSalud_id' => $post['finalidadTecnologiaSalud_id'],
                'causaMotivoAtencion_id' => $post['causaMotivoAtencion_id'],
                'codDiagnosticoPrincipal_id' => $post['codDiagnosticoPrincipal_id'],
                'codDiagnosticoRelacionado1_id' => $post['codDiagnosticoRelacionado1_id'],
                'codDiagnosticoRelacionado2_id' => $post['codDiagnosticoRelacionado2_id'],
                'codDiagnosticoRelacionado3_id' => $post['codDiagnosticoRelacionado3_id'],
                'tipoDiagnosticoPrincipal_id' => $post['tipoDiagnosticoPrincipal_id'],
                'valorPagoModerador' => $post['valorPagoModerador'],
                'vrServicio' => $post['vrServicio'],
                'conceptoRecaudo_id' => $post['conceptoRecaudo_id'],
                'tipoDocumentoIdentificacion_id' => $post['tipoDocumentoIdentificacion_id'],
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'numFEVPagoModerador' => $post['numFEVPagoModerador'],
            ], $id);

            // Update Service
            $service = $this->serviceRepository->store([
                'codigo_servicio' => $medicalConsultation->codConsulta->codigo,
                'nombre_servicio' => $medicalConsultation->codConsulta->nombre,
                'quantity' => 1,
                'unit_value' => $post['vrServicio'],
                'total_value' => $post['vrServicio'],
            ], $post['service_id']);

            // Store the current consecutivo
            $consecutivo = $service->consecutivo;

            // Prepare service data for JSON
            $serviceData = [
                'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo,
                'fechaInicioAtencion' => Carbon::parse($post['fechaInicioAtencion'])->format('Y-m-d H:i'),
                'numAutorizacion' => $post['numAutorizacion'] ?? null,
                'codConsulta' => $medicalConsultation->codConsulta?->codigo,
                'modalidadGrupoServicioTecSal' => $medicalConsultation->modalidadGrupoServicioTecSal?->codigo,
                'grupoServicios' => $medicalConsultation->grupoServicios?->codigo,
                'codServicio' => $medicalConsultation->codServicio?->codigo,
                'finalidadTecnologiaSalud' => $medicalConsultation->finalidadTecnologiaSalud?->codigo,
                'causaMotivoAtencion' => $medicalConsultation->causaMotivoAtencion?->codigo,
                'codDiagnosticoPrincipal' => $medicalConsultation->codDiagnosticoPrincipal?->codigo,
                'codDiagnosticoRelacionado1' => $medicalConsultation->codDiagnosticoRelacionado1?->codigo ?? '',
                'codDiagnosticoRelacionado2' => $medicalConsultation->codDiagnosticoRelacionado2?->codigo ?? '',
                'codDiagnosticoRelacionado3' => $medicalConsultation->codDiagnosticoRelacionado3?->codigo ?? '',
                'tipoDiagnosticoPrincipal' => $medicalConsultation->tipoDiagnosticoPrincipal?->codigo,
                'tipoDocumentoIdentificacion' => $medicalConsultation->tipoDocumentoIdentificacion?->codigo,
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'vrServicio' => floatval($post['vrServicio']),
                'conceptoRecaudo' => $medicalConsultation->conceptoRecaudo?->codigo,
                'valorPagoModerador' => floatval($post['valorPagoModerador']),
                'numFEVPagoModerador' => $post['numFEVPagoModerador'],
                'consecutivo' => $consecutivo,
            ];

            // Update JSON with edited service
            updateInvoiceServicesJson(
                $post['invoice_id'],
                TypeServiceEnum::SERVICE_TYPE_001,
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

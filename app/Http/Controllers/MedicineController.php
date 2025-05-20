<?php

namespace App\Http\Controllers;

use App\Enums\Service\TypeServiceEnum;
use App\Http\Requests\Medicine\MedicineStoreRequest;
use App\Http\Resources\Medicine\MedicineFormResource;
use App\Repositories\InvoiceRepository;
use App\Repositories\MedicineRepository;
use App\Repositories\ServiceRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected MedicineRepository $medicineRepository,
        protected ServiceRepository $serviceRepository,
        protected InvoiceRepository $invoiceRepository,
        protected QueryController $queryController,
    ) {}

    public function create(Request $request)
    {
        return $this->execute(function () {

            $cie10 = $this->queryController->selectInfiniteCie10(request());
            $tipoMedicamentoPosVersion2 = $this->queryController->selectInfiniteTipoMedicamentoPosVersion2(request());
            $umm = $this->queryController->selectInfiniteUmm(request());
            $conceptoRecaudo = $this->queryController->selectInfiniteConceptoRecaudo(request());
            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis(request());

            return [
                'code' => 200,
                ...$cie10,
                ...$tipoMedicamentoPosVersion2,
                ...$umm,
                ...$conceptoRecaudo,
                ...$tipoDocumento,
            ];
        });
    }

    public function store(MedicineStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->all();

            // Get the next consecutivo
            $consecutivo = getNextConsecutivo($post['invoice_id'], TypeServiceEnum::SERVICE_TYPE_006);

            // Create Medicine
            $medicine = $this->medicineRepository->store([
                'numAutorizacion' => $post['numAutorizacion'],
                'idMIPRES' => $post['idMIPRES'],
                'fechaDispensAdmon' => $post['fechaDispensAdmon'],
                'codDiagnosticoPrincipal_id' => $post['codDiagnosticoPrincipal_id'],
                'codDiagnosticoRelacionado_id' => $post['codDiagnosticoRelacionado_id'],
                'tipoMedicamento_id' => $post['tipoMedicamento_id'],
                'codTecnologiaSalud' => $post['codTecnologiaSalud'],
                'nomTecnologiaSalud' => $post['nomTecnologiaSalud'],
                'concentracionMedicamento' => $post['concentracionMedicamento'],
                'unidadMedida_id' => $post['unidadMedida_id'],
                'formaFarmaceutica' => $post['formaFarmaceutica'],
                'unidadMinDispensa' => $post['unidadMinDispensa'],
                'cantidadMedicamento' => $post['cantidadMedicamento'],
                'diasTratamiento' => $post['diasTratamiento'],
                'vrUnitMedicamento' => $post['vrUnitMedicamento'],
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
                'type' => TypeServiceEnum::SERVICE_TYPE_006,
                'serviceable_type' => TypeServiceEnum::SERVICE_TYPE_006->model(),
                'serviceable_id' => $medicine->id,
                'codigo_servicio' => $post['codTecnologiaSalud'],
                'nombre_servicio' => $post['nomTecnologiaSalud'],
                'quantity' => $post['cantidadMedicamento'],
                'unit_value' => $post['vrServicio'],
                'total_value' => $post['vrServicio'],
            ]);

            // Prepare service data for JSON
            $serviceData = [
                'codPrestador' => $service->invoice?->serviceVendor?->ips_no_rep?->codigo,
                'numAutorizacion' => $post['numAutorizacion'],
                'idMIPRES' => $post['idMIPRES'],
                'fechaDispensAdmon' => $post['fechaDispensAdmon'],
                'codDiagnosticoPrincipal' => $medicine->codDiagnosticoPrincipal?->codigo,
                'codDiagnosticoRelacionado' => $medicine->codDiagnosticoRelacionado?->codigo,
                'tipoMedicamento' => $medicine->tipoMedicamento?->codigo,
                'codTecnologiaSalud' => $post['codTecnologiaSalud'],
                'nomTecnologiaSalud' => $post['nomTecnologiaSalud'],
                'concentracionMedicamento' => $post['concentracionMedicamento'],
                'unidadMedida' => $medicine->unidadMedida?->codigo,
                'formaFarmaceutica' => $post['formaFarmaceutica'],
                'unidadMinDispensa' => $post['unidadMinDispensa'],
                'cantidadMedicamento' => $post['cantidadMedicamento'],
                'diasTratamiento' => $post['diasTratamiento'],
                'tipoDocumentoIdentificacion' => $medicine->tipoDocumentoIdentificacion?->codigo,
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'vrUnitMedicamento' => $post['vrUnitMedicamento'],
                'vrServicio' => $post['vrServicio'],
                'conceptoRecaudo' => $medicine->conceptoRecaudo?->codigo,
                'valorPagoModerador' => $post['valorPagoModerador'],
                'numFEVPagoModerador' => $post['numFEVPagoModerador'],
                'consecutivo' => $consecutivo,
            ];

            // Update JSON with new service
            updateInvoiceServicesJson(
                $post['invoice_id'],
                TypeServiceEnum::SERVICE_TYPE_006,
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

            $medicine = $service->serviceable;
            $form = new MedicineFormResource($medicine);

            $cie10 = $this->queryController->selectInfiniteCie10(request());
            $tipoMedicamentoPosVersion2 = $this->queryController->selectInfiniteTipoMedicamentoPosVersion2(request());
            $umm = $this->queryController->selectInfiniteUmm(request());
            $conceptoRecaudo = $this->queryController->selectInfiniteConceptoRecaudo(request());
            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis(request());

            return [
                'code' => 200,
                'form' => $form,
                ...$cie10,
                ...$tipoMedicamentoPosVersion2,
                ...$umm,
                ...$conceptoRecaudo,
                ...$tipoDocumento,
            ];
        });
    }

    public function update(MedicineStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request, $id) {

            $post = $request->all();

            // Update Medicine
            $medicine = $this->medicineRepository->store([
                'numAutorizacion' => $post['numAutorizacion'],
                'idMIPRES' => $post['idMIPRES'],
                'fechaDispensAdmon' => $post['fechaDispensAdmon'],
                'codDiagnosticoPrincipal_id' => $post['codDiagnosticoPrincipal_id'],
                'codDiagnosticoRelacionado_id' => $post['codDiagnosticoRelacionado_id'],
                'tipoMedicamento_id' => $post['tipoMedicamento_id'],
                'codTecnologiaSalud' => $post['codTecnologiaSalud'],
                'nomTecnologiaSalud' => $post['nomTecnologiaSalud'],
                'concentracionMedicamento' => $post['concentracionMedicamento'],
                'unidadMedida_id' => $post['unidadMedida_id'],
                'formaFarmaceutica' => $post['formaFarmaceutica'],
                'unidadMinDispensa' => $post['unidadMinDispensa'],
                'cantidadMedicamento' => $post['cantidadMedicamento'],
                'diasTratamiento' => $post['diasTratamiento'],
                'vrUnitMedicamento' => $post['vrUnitMedicamento'],
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
                'quantity' => $post['cantidadMedicamento'],
                'unit_value' => $post['vrServicio'],
                'total_value' => $post['vrServicio'],
            ], $post['service_id']);

            // Store the current consecutivo
            $consecutivo = $service->consecutivo;

            // Prepare service data for JSON
            $serviceData = [
                'codPrestador' => $service->invoice?->serviceVendor?->ips_no_rep?->codigo,
                'numAutorizacion' => $post['numAutorizacion'],
                'idMIPRES' => $post['idMIPRES'],
                'fechaDispensAdmon' => $post['fechaDispensAdmon'],
                'codDiagnosticoPrincipal' => $medicine->codDiagnosticoPrincipal?->codigo,
                'codDiagnosticoRelacionado' => $medicine->codDiagnosticoRelacionado?->codigo,
                'tipoMedicamento' => $medicine->tipoMedicamento?->codigo,
                'codTecnologiaSalud' => $post['codTecnologiaSalud'],
                'nomTecnologiaSalud' => $post['nomTecnologiaSalud'],
                'concentracionMedicamento' => $post['concentracionMedicamento'],
                'unidadMedida' => $medicine->unidadMedida?->codigo,
                'formaFarmaceutica' => $post['formaFarmaceutica'],
                'unidadMinDispensa' => $post['unidadMinDispensa'],
                'cantidadMedicamento' => $post['cantidadMedicamento'],
                'diasTratamiento' => $post['diasTratamiento'],
                'tipoDocumentoIdentificacion' => $medicine->tipoDocumentoIdentificacion?->codigo,
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'vrUnitMedicamento' => $post['vrUnitMedicamento'],
                'vrServicio' => $post['vrServicio'],
                'conceptoRecaudo' => $medicine->conceptoRecaudo?->codigo,
                'valorPagoModerador' => $post['valorPagoModerador'],
                'numFEVPagoModerador' => $post['numFEVPagoModerador'],
                'consecutivo' => $consecutivo,
            ];

            // Update JSON with edited service
            updateInvoiceServicesJson(
                $post['invoice_id'],
                TypeServiceEnum::SERVICE_TYPE_006,
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

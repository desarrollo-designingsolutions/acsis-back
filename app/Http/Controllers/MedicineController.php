<?php

namespace App\Http\Controllers;

use App\Enums\Service\TypeServiceEnum;
use App\Helpers\Constants;
use App\Http\Requests\Medicine\MedicineStoreRequest;
use App\Http\Resources\Medicine\MedicineFormResource;
use App\Repositories\InvoiceRepository;
use App\Repositories\MedicineRepository;
use App\Repositories\ServiceRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_TIPODOCUMENTOIDENTIFICACION]);
            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_MEDICINE_CONCEPTORECAUDO]);
            $conceptoRecaudo = $this->queryController->selectInfiniteConceptoRecaudo($newRequest);

            $cie10 = $this->queryController->selectInfiniteCie10(request());
            $tipoMedicamentoPosVersion2 = $this->queryController->selectInfiniteTipoMedicamentoPosVersion2(request());
            $umm = $this->queryController->selectInfiniteUmm(request());
            $upr = $this->queryController->selectInfiniteUpr(request());
            $ffm = $this->queryController->selectInfiniteFfm(request());
            $dci = $this->queryController->selectInfiniteDci(request());

            $invoice = $this->invoiceRepository->find(request('invoice_id'), select: ['id', 'invoice_date']);

            $ium = $this->queryController->selectInfiniteIum(request());
            $catalogoCum = $this->queryController->selectInfiniteCatalogoCum(request());

            $codTecnologiaSaludables = [
                [
                    'value' => "App\Models\Ium",
                    'label' => 'Ium',
                    'url' => '/selectInfiniteIum',
                    'arrayInfo' => 'ium',
                    'itemsData' => $ium['ium_arrayInfo'],
                ],
                [
                    'value' => "App\Models\CatalogoCum",
                    'label' => 'CatalogoCum',
                    'url' => '/selectInfiniteCatalogoCum',
                    'arrayInfo' => 'catalogoCum',
                    'itemsData' => $catalogoCum['catalogoCum_arrayInfo'],
                ],
            ];

            return [
                'code' => 200,
                'invoice' => $invoice,
                'codTecnologiaSaludables' => $codTecnologiaSaludables,
                ...$cie10,
                ...$tipoMedicamentoPosVersion2,
                ...$umm,
                ...$conceptoRecaudo,
                ...$tipoDocumento,
                ...$upr,
                ...$ffm,
                ...$dci,
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
                'codTecnologiaSaludable_id' => $post['codTecnologiaSaludable_id'],
                'codTecnologiaSaludable_type' => $post['codTecnologiaSaludable_type'],
                'nomTecnologiaSalud_id' => $post['nomTecnologiaSalud_id'],
                'concentracionMedicamento' => $post['concentracionMedicamento'],
                'unidadMedida_id' => $post['unidadMedida_id'],
                'formaFarmaceutica_id' => $post['formaFarmaceutica_id'],
                'unidadMinDispensa_id' => $post['unidadMinDispensa_id'],
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
                'codigo_servicio' => $medicine->codTecnologiaSaludable?->codigo ?? null,
                'nombre_servicio' => $medicine->nomTecnologiaSalud?->nombre ?? null,
                'quantity' => $post['cantidadMedicamento'],
                'unit_value' => $post['vrServicio'],
                'total_value' => $post['vrServicio'],
            ]);

            // Prepare service data for JSON
            $serviceData = [
                'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo ?? '',
                'numAutorizacion' => $post['numAutorizacion'] ?? null,
                'idMIPRES' => $post['idMIPRES'] ?? null,
                'fechaDispensAdmon' => Carbon::parse($post['fechaDispensAdmon'])->format('Y-m-d H:i'),
                'codDiagnosticoPrincipal' => $medicine->codDiagnosticoPrincipal?->codigo,
                'codDiagnosticoRelacionado' => $medicine->codDiagnosticoRelacionado?->codigo ?? '',
                'tipoMedicamento' => $medicine->tipoMedicamento?->codigo,

                'codTecnologiaSalud' => $medicine->codTecnologiaSaludable?->codigo,

                'nomTecnologiaSalud' => $medicine->nomTecnologiaSalud?->nombre ?? null,
                'concentracionMedicamento' => floatval($post['concentracionMedicamento']) ?? null,
                'unidadMedida' => floatval($medicine->unidadMedida?->codigo) ?? null,
                'formaFarmaceutica' => $medicine->formaFarmaceutica?->codigo ?? null,
                'unidadMinDispensa' => floatval($medicine->unidadMinDispensa?->codigo) ?? null,
                'cantidadMedicamento' => intval($post['cantidadMedicamento']),
                'diasTratamiento' => intval($post['diasTratamiento']),
                'tipoDocumentoIdentificacion' => $medicine->tipoDocumentoIdentificacion?->codigo,
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'vrUnitMedicamento' => floatval($post['vrUnitMedicamento']),
                'vrServicio' => floatval($post['vrServicio']),
                'conceptoRecaudo' => $medicine->conceptoRecaudo?->codigo,
                'valorPagoModerador' => floatval($post['valorPagoModerador']),
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

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_TIPODOCUMENTOIDENTIFICACION]);
            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_MEDICINE_CONCEPTORECAUDO]);
            $conceptoRecaudo = $this->queryController->selectInfiniteConceptoRecaudo($newRequest);

            $cie10 = $this->queryController->selectInfiniteCie10(request());
            $tipoMedicamentoPosVersion2 = $this->queryController->selectInfiniteTipoMedicamentoPosVersion2(request());
            $umm = $this->queryController->selectInfiniteUmm(request());
            $upr = $this->queryController->selectInfiniteUpr(request());
            $ffm = $this->queryController->selectInfiniteFfm(request());
            $dci = $this->queryController->selectInfiniteDci(request());

            $invoice = $this->invoiceRepository->find(request('invoice_id'), select: ['id', 'invoice_date']);

            $ium = $this->queryController->selectInfiniteIum(request());
            $catalogoCum = $this->queryController->selectInfiniteCatalogoCum(request());

            $codTecnologiaSaludables = [
                [
                    'value' => "App\Models\Ium",
                    'label' => 'Ium',
                    'url' => '/selectInfiniteIum',
                    'arrayInfo' => 'ium',
                    'itemsData' => $ium['ium_arrayInfo'],
                ],
                [
                    'value' => "App\Models\CatalogoCum",
                    'label' => 'CatalogoCum',
                    'url' => '/selectInfiniteCatalogoCum',
                    'arrayInfo' => 'catalogoCum',
                    'itemsData' => $catalogoCum['catalogoCum_arrayInfo'],
                ],
            ];

            return [
                'code' => 200,
                'form' => $form,
                'invoice' => $invoice,
                'codTecnologiaSaludables' => $codTecnologiaSaludables,
                ...$cie10,
                ...$tipoMedicamentoPosVersion2,
                ...$umm,
                ...$conceptoRecaudo,
                ...$tipoDocumento,
                ...$upr,
                ...$ffm,
                ...$dci,
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
                'codTecnologiaSaludable_id' => $post['codTecnologiaSaludable_id'],
                'codTecnologiaSaludable_type' => $post['codTecnologiaSaludable_type'],
                'nomTecnologiaSalud_id' => $post['nomTecnologiaSalud_id'],
                'concentracionMedicamento' => $post['concentracionMedicamento'],
                'unidadMedida_id' => $post['unidadMedida_id'],
                'formaFarmaceutica_id' => $post['formaFarmaceutica_id'],
                'unidadMinDispensa_id' => $post['unidadMinDispensa_id'],
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
                'codigo_servicio' => $medicine->codTecnologiaSaludable?->codigo,
                'nombre_servicio' => $medicine->nomTecnologiaSalud?->nombre ?? null,
                'quantity' => $post['cantidadMedicamento'],
                'unit_value' => $post['vrServicio'],
                'total_value' => $post['vrServicio'],
            ], $post['service_id']);

            // Store the current consecutivo
            $consecutivo = $service->consecutivo;

            // Prepare service data for JSON
            $serviceData = [
                'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo ?? '',
                'numAutorizacion' => $post['numAutorizacion'] ?? null,
                'idMIPRES' => $post['idMIPRES'] ?? null,
                'fechaDispensAdmon' => Carbon::parse($post['fechaDispensAdmon'])->format('Y-m-d H:i'),
                'codDiagnosticoPrincipal' => $medicine->codDiagnosticoPrincipal?->codigo,
                'codDiagnosticoRelacionado' => $medicine->codDiagnosticoRelacionado?->codigo ?? '',
                'tipoMedicamento' => $medicine->tipoMedicamento?->codigo,
                'codTecnologiaSalud' => $medicine->codTecnologiaSaludable?->codigo,
                'nomTecnologiaSalud' => $medicine->nomTecnologiaSalud?->nombre ?? null,
                'concentracionMedicamento' => floatval($post['concentracionMedicamento']) ?? null,
                'unidadMedida' => floatval($medicine->unidadMedida?->codigo) ?? null,
                'formaFarmaceutica' => $medicine->formaFarmaceutica?->codigo ?? null,
                'unidadMinDispensa' => floatval($medicine->unidadMinDispensa?->codigo) ?? null,
                'cantidadMedicamento' => intval($post['cantidadMedicamento']),
                'diasTratamiento' => intval($post['diasTratamiento']),
                'tipoDocumentoIdentificacion' => $medicine->tipoDocumentoIdentificacion?->codigo,
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
                'vrUnitMedicamento' => floatval($post['vrUnitMedicamento']),
                'vrServicio' => floatval($post['vrServicio']),
                'conceptoRecaudo' => $medicine->conceptoRecaudo?->codigo,
                'valorPagoModerador' => floatval($post['valorPagoModerador']),
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

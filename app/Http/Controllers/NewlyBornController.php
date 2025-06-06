<?php

namespace App\Http\Controllers;

use App\Enums\Service\TypeServiceEnum;
use App\Helpers\Constants;
use App\Http\Requests\NewlyBorn\NewlyBornStoreRequest;
use App\Http\Resources\NewlyBorn\NewlyBornFormResource;
use App\Repositories\InvoiceRepository;
use App\Repositories\NewlyBornRepository;
use App\Repositories\ServiceRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NewlyBornController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected NewlyBornRepository $newlyBornRepository,
        protected ServiceRepository $serviceRepository,
        protected InvoiceRepository $invoiceRepository,
        protected QueryController $queryController,
    ) {}

    public function create(Request $request)
    {
        return $this->execute(function () {

            $cie10 = $this->queryController->selectInfiniteCie10(request());
            $condicionyDestinoUsuarioEgreso = $this->queryController->selectInfiniteCondicionyDestinoUsuarioEgreso(request());
            $sexo = $this->queryController->selectInfiniteSexo(request());

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_NEWBORN_TIPODOCUMENTOIDENTIFICACION]);
            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $invoice = $this->invoiceRepository->find(request('invoice_id'), select: ['id', 'invoice_date']);

            return [
                'code' => 200,
                'invoice' => $invoice,
                ...$cie10,
                ...$condicionyDestinoUsuarioEgreso,
                ...$sexo,
                ...$tipoDocumento,
            ];
        });
    }

    public function store(NewlyBornStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->all();

            // Get the next consecutivo
            $consecutivo = getNextConsecutivo($post['invoice_id'], TypeServiceEnum::SERVICE_TYPE_005);

            // Create NewlyBorn
            $newlyBorn = $this->newlyBornRepository->store([
                'fechaNacimiento' => $post['fechaNacimiento'],
                'edadGestacional' => $post['edadGestacional'],
                'numConsultasCPrenatal' => $post['numConsultasCPrenatal'],
                'codSexoBiologico_id' => $post['codSexoBiologico_id'],
                'peso' => $post['peso'],
                'codDiagnosticoPrincipal_id' => $post['codDiagnosticoPrincipal_id'],
                'condicionDestinoUsuarioEgreso_id' => $post['condicionDestinoUsuarioEgreso_id'],
                'codDiagnosticoCausaMuerte_id' => $post['codDiagnosticoCausaMuerte_id'],
                'fechaEgreso' => $post['fechaEgreso'],
                'tipoDocumentoIdentificacion_id' => $post['tipoDocumentoIdentificacion_id'],
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
            ]);

            // Create Service
            $service = $this->serviceRepository->store([
                'company_id' => $post['company_id'],
                'invoice_id' => $post['invoice_id'],
                'consecutivo' => $consecutivo,
                'type' => TypeServiceEnum::SERVICE_TYPE_005,
                'serviceable_type' => TypeServiceEnum::SERVICE_TYPE_005->model(),
                'serviceable_id' => $newlyBorn->id,
                'codigo_servicio' => null,
                'nombre_servicio' => null,
                'quantity' => 1,
                'unit_value' => 0,
                'total_value' => 0,
            ]);

            // Prepare service data for JSON
            $serviceData = [
                'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo,
                'tipoDocumentoIdentificacion' => $newlyBorn->tipoDocumentoIdentificacion?->codigo,
                'numDocumentoIdentificacion' => intval($post['numDocumentoIdentificacion']),
                'fechaNacimiento' => Carbon::parse($post['fechaNacimiento'])->format('Y-m-d H:i'),
                'edadGestacional' => intval($post['edadGestacional']),
                'numConsultasCPrenatal' => intval($post['numConsultasCPrenatal']),
                'codSexoBiologico' => $newlyBorn->codSexoBiologico?->codigo,
                'peso' => floatval($post['peso']),
                'codDiagnosticoPrincipal' => $newlyBorn->codDiagnosticoPrincipal?->codigo,
                'condicionDestinoUsuarioEgreso' => $newlyBorn->condicionDestinoUsuarioEgreso?->codigo,
                'codDiagnosticoCausaMuerte' => $newlyBorn->codDiagnosticoCausaMuerte?->codigo ?? '',
                'fechaEgreso' => Carbon::parse($post['fechaEgreso'])->format('Y-m-d H:i'),
                'consecutivo' => $consecutivo,
            ];

            // Update JSON with new service
            updateInvoiceServicesJson(
                $post['invoice_id'],
                TypeServiceEnum::SERVICE_TYPE_005,
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

            $newlyBorn = $service->serviceable;
            $form = new NewlyBornFormResource($newlyBorn);

            $cie10 = $this->queryController->selectInfiniteCie10(request());
            $condicionyDestinoUsuarioEgreso = $this->queryController->selectInfiniteCondicionyDestinoUsuarioEgreso(request());
            $sexo = $this->queryController->selectInfiniteSexo(request());

            $newRequest = new Request(['codigo_in' => Constants::CODS_SELECT_FORM_SERVICE_NEWBORN_TIPODOCUMENTOIDENTIFICACION]);
            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis($newRequest);

            $invoice = $this->invoiceRepository->find(request('invoice_id'), select: ['id', 'invoice_date']);

            return [
                'code' => 200,
                'form' => $form,
                'invoice' => $invoice,
                ...$cie10,
                ...$condicionyDestinoUsuarioEgreso,
                ...$sexo,
                ...$tipoDocumento,
            ];
        });
    }

    public function update(NewlyBornStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request, $id) {

            $post = $request->all();

            // Update NewlyBorn
            $newlyBorn = $this->newlyBornRepository->store([
                'fechaNacimiento' => $post['fechaNacimiento'],
                'edadGestacional' => $post['edadGestacional'],
                'numConsultasCPrenatal' => $post['numConsultasCPrenatal'],
                'codSexoBiologico_id' => $post['codSexoBiologico_id'],
                'peso' => $post['peso'],
                'codDiagnosticoPrincipal_id' => $post['codDiagnosticoPrincipal_id'],
                'condicionDestinoUsuarioEgreso_id' => $post['condicionDestinoUsuarioEgreso_id'],
                'codDiagnosticoCausaMuerte_id' => $post['codDiagnosticoCausaMuerte_id'],
                'fechaEgreso' => $post['fechaEgreso'],
                'tipoDocumentoIdentificacion_id' => $post['tipoDocumentoIdentificacion_id'],
                'numDocumentoIdentificacion' => $post['numDocumentoIdentificacion'],
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
                'tipoDocumentoIdentificacion' => $newlyBorn->tipoDocumentoIdentificacion?->codigo,
                'numDocumentoIdentificacion' => intval($post['numDocumentoIdentificacion']),
                'fechaNacimiento' => Carbon::parse($post['fechaNacimiento'])->format('Y-m-d H:i'),
                'edadGestacional' => intval($post['edadGestacional']),
                'numConsultasCPrenatal' => intval($post['numConsultasCPrenatal']),
                'codSexoBiologico' => $newlyBorn->codSexoBiologico?->codigo,
                'peso' => floatval($post['peso']),
                'codDiagnosticoPrincipal' => $newlyBorn->codDiagnosticoPrincipal?->codigo,
                'condicionDestinoUsuarioEgreso' => $newlyBorn->condicionDestinoUsuarioEgreso?->codigo,
                'codDiagnosticoCausaMuerte' => $newlyBorn->codDiagnosticoCausaMuerte?->codigo ?? '',
                'fechaEgreso' => Carbon::parse($post['fechaEgreso'])->format('Y-m-d H:i'),
                'consecutivo' => $consecutivo,
            ];

            // Update JSON with edited service
            updateInvoiceServicesJson(
                $post['invoice_id'],
                TypeServiceEnum::SERVICE_TYPE_005,
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

<?php

namespace App\Http\Controllers;

use App\Attributes\Description;
use App\Enums\Invoice\StatusXmlInvoiceEnum;
use App\Enums\Invoice\TypeInvoiceEnum;
use App\Enums\Service\TypeServiceEnum;
use App\Events\InvoiceRowUpdatedNow;
use App\Exports\Invoice\InvoiceExcelErrorsValidationXmlExport;
use App\Exports\Invoice\InvoiceExcelExport;
use App\Helpers\Constants;
use App\Http\Requests\Invoice\InvoiceStoreRequest;
use App\Http\Resources\Invoice\InvoiceFormResource;
use App\Http\Resources\Invoice\InvoiceListResource;
use App\Http\Resources\InvoiceSoat\InvoiceSoatFormResource;
use App\Models\Service;
use App\Repositories\Cie10Repository;
use App\Repositories\ConceptoRecaudoRepository;
use App\Repositories\CondicionyDestinoUsuarioEgresoRepository;
use App\Repositories\CupsRipsRepository;
use App\Repositories\EntityRepository;
use App\Repositories\GrupoServicioRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\InvoiceSoatRepository;
use App\Repositories\ModalidadAtencionRepository;
use App\Repositories\MunicipioRepository;
use App\Repositories\PaisRepository;
use App\Repositories\PatientRepository;
use App\Repositories\RipsCausaExternaVersion2Repository;
use App\Repositories\RipsFinalidadConsultaVersion2Repository;
use App\Repositories\RipsTipoDiagnosticoPrincipalVersion2Repository;
use App\Repositories\RipsTipoUsuarioVersion2Repository;
use App\Repositories\ServiceVendorRepository;
use App\Repositories\ServicioRepository;
use App\Repositories\SexoRepository;
use App\Repositories\TipoIdPisisRepository;
use App\Repositories\TipoMedicamentoPosVersion2Repository;
use App\Repositories\TipoNotaRepository;
use App\Repositories\TipoOtrosServiciosRepository;
use App\Repositories\TypeEntityRepository;
use App\Repositories\UmmRepository;
use App\Repositories\ViaIngresoUsuarioRepository;
use App\Repositories\ZonaVersion2Repository;
use App\Services\CacheService;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    use HttpResponseTrait;

    private $key_redis_project;

    public function __construct(
        protected InvoiceRepository $invoiceRepository,
        protected QueryController $queryController,
        protected TypeEntityRepository $typeEntityRepository,
        protected CacheService $cacheService,
        protected PatientRepository $patientRepository,
        protected InvoiceSoatRepository $invoiceSoatRepository,
        protected ServiceVendorRepository $serviceVendorRepository,
        protected TipoNotaRepository $tipoNotaRepository,
        protected TipoIdPisisRepository $tipoIdPisisRepository,
        protected RipsTipoUsuarioVersion2Repository $ripsTipoUsuarioVersion2Repository,
        protected SexoRepository $sexoRepository,
        protected PaisRepository $paisRepository,
        protected MunicipioRepository $municipioRepository,
        protected ZonaVersion2Repository $zonaVersion2Repository,
        protected CupsRipsRepository $cupsRipsRepository,

        protected ModalidadAtencionRepository $modalidadAtencionRepository,
        protected GrupoServicioRepository $grupoServicioRepository,
        protected ServicioRepository $servicioRepository,
        protected RipsFinalidadConsultaVersion2Repository $ripsFinalidadConsultaVersion2Repository,
        protected RipsCausaExternaVersion2Repository $ripsCausaExternaVersion2Repository,
        protected Cie10Repository $cie10Repository,
        protected RipsTipoDiagnosticoPrincipalVersion2Repository $ripsTipoDiagnosticoPrincipalVersion2Repository,
        protected ConceptoRecaudoRepository $conceptoRecaudoRepository,
        protected ViaIngresoUsuarioRepository $viaIngresoUsuarioRepository,
        protected CondicionyDestinoUsuarioEgresoRepository $condicionyDestinoUsuarioEgresoRepository,
        protected TipoMedicamentoPosVersion2Repository $tipoMedicamentoPosVersion2Repository,
        protected UmmRepository $ummRepository,
        protected TipoOtrosServiciosRepository $tipoOtrosServiciosRepository,
        protected EntityRepository $entityRepository,

    ) {
        $this->key_redis_project = env('KEY_REDIS_PROJECT');
    }

    public function paginate(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->invoiceRepository->paginate($request->all());
            $tableData = InvoiceListResource::collection($data);

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

    public function create()
    {
        return $this->execute(function () {

            $serviceVendors = $this->queryController->selectInfiniteServiceVendor(request());
            $entities = $this->queryController->selectInfiniteEntities(request());
            $tipoNotas = $this->queryController->selectInfinitetipoNota(request());
            $patients = $this->queryController->selectInfinitePatients(request());
            $statusInvoiceEnum = $this->queryController->selectStatusInvoiceEnum(request());

            return [
                'code' => 200,
                ...$statusInvoiceEnum,
                ...$serviceVendors,
                ...$entities,
                ...$tipoNotas,
                ...$patients,
            ];
        });
    }

    public function store(InvoiceStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            // Extract and prepare data
            $post = $request->except(['entity', 'patient', 'TipoNota', 'serviceVendor', 'soat', 'value_paid', 'total', 'remaining_balance', 'value_glosa']);
            $type = $request->input('type');

            $infoDataExtra = $this->saveDataExtraInvoice($type, $request->all());

            $post['typeable_type'] = $infoDataExtra['model'];
            $post['typeable_id'] = $infoDataExtra['id'];
            $post['status_xml'] = StatusXmlInvoiceEnum::INVOICE_STATUS_XML_001;

            $invoice = $this->invoiceRepository->store($post);

            // Build JSON structure
            $jsonData = $this->buildInvoiceJson($invoice->id);

            // Store JSON file
            $this->storeJsonFile($invoice, $jsonData);

            return [
                'code' => 200,
                'message' => 'Factura agregada correctamente',
                'form' => $invoice,
                'infoDataExtra' => $infoDataExtra,
            ];
        }, debug: false);
    }

    public function edit($id)
    {
        return $this->execute(function () use ($id) {

            // Recuperamos la factura
            $invoice = $this->invoiceRepository->find($id);
            $form = new InvoiceFormResource($invoice);

            $infoDataExtra = null;
            // Recuperamos informacion extra dependiendo del tipo de factura
            if ($invoice->type->value == 'INVOICE_TYPE_002') {
                $soat = $this->invoiceSoatRepository->find($form->typeable_id);
                $infoDataExtra = new InvoiceSoatFormResource($soat);
            }

            $serviceVendors = $this->queryController->selectInfiniteServiceVendor(request());
            $entities = $this->queryController->selectInfiniteEntities(request());
            $tipoNotas = $this->queryController->selectInfinitetipoNota(request());
            $patients = $this->queryController->selectInfinitePatients(request());
            $statusInvoiceEnum = $this->queryController->selectStatusInvoiceEnum(request());

            return [
                'code' => 200,
                'form' => $form,
                'infoDataExtra' => $infoDataExtra,
                ...$statusInvoiceEnum,
                ...$serviceVendors,
                ...$entities,
                ...$tipoNotas,
                ...$patients,
            ];
        });
    }

    public function update(InvoiceStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except(['entity', 'patient', 'TipoNota', 'serviceVendor', 'soat', 'value_paid', 'total', 'remaining_balance', 'value_glosa']);
            $type = $request->input('type');

            $infoDataExtra = $this->saveDataExtraInvoice($type, $request->all());

            $post['typeable_type'] = $infoDataExtra['model'];
            $post['typeable_id'] = $infoDataExtra['id'];

            $invoice = $this->invoiceRepository->store($post);

            // Build JSON structure
            $jsonData = $this->buildInvoiceJson($invoice->id);

            // // Store JSON file
            // $this->storeJsonFile($invoice, $jsonData);

            return [
                'code' => 200,
                'message' => 'Factura modificada correctamente',
                'form' => $invoice,
                'infoDataExtra' => $infoDataExtra,
            ];
        });
    }

    public function saveDataExtraInvoice($type, $request)
    {
        $element = ['id' => null, 'model' => null];

        if ($type == 'INVOICE_TYPE_002') {

            $dataSoat = array_merge($request['soat'], ['company_id' => $request['company_id']]);

            // Store SOAT and invoice
            $element = $this->invoiceSoatRepository->store($dataSoat);
            $element['model'] = TypeInvoiceEnum::INVOICE_TYPE_002->model();
        }

        return $element;
    }

    public function delete($id)
    {
        return $this->runTransaction(function () use ($id) {
            $invoice = $this->invoiceRepository->find($id);
            if ($invoice) {

                // Verificar si hay registros relacionados
                // if ($invoice->entities()->exists()) {
                //     throw new \Exception(json_encode([
                //         'message' => 'No se puede eliminar la factura, porque tiene relación de datos en otros módulos',
                //     ]));
                // }

                $invoice->delete();
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

    public function excelExport(Request $request)
    {
        return $this->execute(function () use ($request) {

            $request['typeData'] = 'all';

            $entities = $this->invoiceRepository->paginate($request->all());

            $excel = Excel::raw(new InvoiceExcelExport($entities), \Maatwebsite\Excel\Excel::XLSX);

            $excelBase64 = base64_encode($excel);

            return [
                'code' => 200,
                'excel' => $excelBase64,
            ];
        });
    }

    public function loadBtnCreate(Request $request)
    {
        return $this->execute(function () {

            $TypeInvoiceEnumValues = array_map(function ($case) {
                return [
                    'id' => $case->value,
                    'name' => $case->description(),
                ];
            }, TypeInvoiceEnum::cases());

            return [
                'code' => 200,
                'TypeInvoiceEnumValues' => $TypeInvoiceEnumValues,
            ];
        });
    }

    public function validateInvoiceNumber(Request $request)
    {
        return $this->execute(function () use ($request) {

            $request->validate([
                'invoice_number' => 'required|string',
            ]);

            $exists = $this->invoiceRepository->validateInvoiceNumber($request->all());

            return [
                'message_invoice' => 'El número de factura ya existe.',
                'exists' => $exists,
            ];
        });
    }

    private function buildInvoiceJson($invoice_id): array
    {
        // Load invoice with related data
        $invoice = $this->invoiceRepository->find($invoice_id, [
            'tipoNota',
            'serviceVendor',
            'patient',
            'patient.sexo',
            'patient.rips_tipo_usuario_version2',
            'patient.pais_residency',
            'patient.municipio_residency',
            'patient.zona_version2',
            'patient.tipo_id_pisi',
            'patient.pais_origin',
        ]);

        // Extract related data for cleaner code
        $patient = $invoice->patient;
        $sexo = $patient->sexo;
        $tipoUsuario = $patient->rips_tipo_usuario_version2;
        $pais_residency = $patient->pais_residency;
        $pais_origin = $patient->pais_origin;
        $municipio = $patient->municipio_residency;
        $zonaVersion2 = $patient->zona_version2;
        $tipoIdPisis = $patient->tipo_id_pisi;
        $tipoNota = $invoice->tipoNota;
        $serviceVendor = $invoice->serviceVendor;

        // Build base invoice data
        $baseData = [
            'numDocumentoIdObligado' => $serviceVendor->nit,
            'numFactura' => $invoice->invoice_number,
            'TipoNota' => $tipoNota->codigo ?? '',
            'numNota' => $invoice->note_number,
        ];

        // Convert null values to empty strings
        $baseData = convertNullToEmptyString($baseData);

        // Build user data
        $users = [
            [
                'codSexo' => $sexo->codigo,
                'consecutivo' => 1,
                'incapacidad' => $patient->incapacity,
                'tipoUsuario' => $tipoUsuario->codigo,
                'codPaisOrigen' => $pais_origin->codigo,
                'fechaNacimiento' => $patient->birth_date,
                'codPaisResidencia' => $pais_residency->codigo,
                'codMunicipioResidencia' => $municipio->codigo,
                'numDocumentoIdentificacion' => $patient->document,
                'tipoDocumentoIdentificacion' => $tipoIdPisis->codigo,
                'codZonaTerritorialResidencia' => $zonaVersion2->codigo,
            ],
        ];

        // Combine base data and users into final JSON structure
        $newData = $baseData;
        $newData['usuarios'] = $users;

        // Define file path
        $nameFile = $invoice->invoice_number . '.json';
        $path = "companies/company_{$invoice->company_id}/invoices/invoice_{$invoice->id}/{$nameFile}";
        $disk = Constants::DISK_FILES;

        // Check if invoice has an existing path_json that differs from the new path
        if ($invoice->path_json && $invoice->path_json !== $path) {
            // Delete old file if it exists
            if (Storage::disk($disk)->exists($invoice->path_json)) {
                Storage::disk($disk)->delete($invoice->path_json);
            }
        }

        // Check if file exists
        if (Storage::disk($disk)->exists($path)) {
            // Read existing JSON
            $existingData = json_decode(Storage::disk($disk)->get($path), true);

            // Compare and update only changed fields
            $mergedData = $this->mergeChangedFields($existingData, $newData);
        } else {
            // Use new data if file doesn't exist
            $mergedData = $newData;
        }

        // Store JSON contents
        Storage::disk($disk)->put($path, json_encode($mergedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Update path_json in the invoice
        $this->invoiceRepository->store(['path_json' => $path], $invoice->id);

        return $mergedData;
    }

    /**
     * Merge existing data with new data, updating only changed fields
     */
    private function mergeChangedFields(array $existingData, array $newData): array
    {
        $mergedData = $existingData;

        // Update base fields if they differ
        foreach ($newData as $key => $value) {
            if ($key !== 'usuarios' && (! isset($existingData[$key]) || $existingData[$key] !== $value)) {
                $mergedData[$key] = $value;
            }
        }

        // Handle usuarios array
        if (isset($newData['usuarios'])) {
            $mergedData['usuarios'] = $mergedData['usuarios'] ?? [];
            foreach ($newData['usuarios'] as $index => $newUser) {
                if (isset($mergedData['usuarios'][$index])) {
                    // Update existing user fields if they differ
                    foreach ($newUser as $key => $value) {
                        if (! isset($mergedData['usuarios'][$index][$key]) || $mergedData['usuarios'][$index][$key] !== $value) {
                            $mergedData['usuarios'][$index][$key] = $value;
                        }
                    }
                } else {
                    // Add new user if it doesn't exist
                    $mergedData['usuarios'][$index] = $newUser;
                }
            }
        }

        return $mergedData;
    }

    private function storeJsonFile($invoice, array $jsonData): void
    {
        $nameFile = $invoice->invoice_number . '.json';
        $path = "companies/company_{$invoice->company_id}/invoices/invoice_{$invoice->id}/{$nameFile}";
        $disk = Constants::DISK_FILES;

        // Check if invoice has an existing path_json that differs from the new path
        if ($invoice->path_json && $invoice->path_json !== $path) {
            // Attempt to delete the old file
            if (Storage::disk($disk)->exists($invoice->path_json)) {
                Storage::disk($disk)->delete($invoice->path_json);
            }
        }

        // Check if file exists
        if (Storage::disk($disk)->exists($path)) {
            // Read existing JSON and merge with new data
            $existingData = json_decode(Storage::disk($disk)->get($path), true);
            $mergedData = array_merge($existingData, $jsonData);
        } else {
            $mergedData = $jsonData;
        }

        // Store JSON contents
        Storage::disk($disk)->put($path, json_encode($mergedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Update path_json in the invoice
        $this->invoiceRepository->store(['path_json' => $path], $invoice->id);
    }

    public function downloadJson($id)
    {
        // Buscar la factura
        $invoice = $this->invoiceRepository->find($id, select: ['id', 'invoice_number', 'path_json']);
        $path = $invoice->path_json;
        $disk = Constants::DISK_FILES;

        // Verificar que el archivo existe
        if (! Storage::disk($disk)->exists($path)) {
            abort(404, 'Archivo no encontrado');
        }

        // Obtener el contenido del archivo
        $fileContent = Storage::disk($disk)->get($path);
        $fileName = $invoice->invoice_number . '.json'; // Nombre del archivo para la descarga

        // Devolver el archivo como respuesta descargable
        return response($fileContent, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function uploadXml(Request $request)
    {
        return $this->execute(function () use ($request) {
            if ($request->hasFile('archiveXml')) {
                // Inicializar variables
                $company_id = $request->input('company_id');
                $invoice_id = $request->input('invoice_id');

                $invoice = $this->invoiceRepository->find($invoice_id, with: ['serviceVendor:id,nit'], select: ['id', 'type', 'path_json', 'invoice_number', 'path_xml', 'status_xml', 'service_vendor_id']);
                $jsonContents = openFileJson($invoice->path_json);
                $file = $request->file('archiveXml');

                $data = [
                    'numInvoice' => $invoice->invoice_number,
                    'file_name' => $file->getClientOriginalName(),
                    'jsonContents' => $jsonContents,
                ];

                // Validar datos del XML
                $infoValidation = validateDataFilesXml($request->file('archiveXml')->path(), $data);

                // Determinar el estado y la ruta del archivo XML
                if ($infoValidation['totalErrorMessages'] == 0) {
                    $finalName = "{$invoice->serviceVendor->nit}_{$invoice->invoice_number}_FILEXML.xml";
                    $finalPath = "companies/company_{$company_id}/invoices/{$invoice->type->value}/invoice_{$invoice->id}/{$invoice->invoice_number}/xml";

                    $path = $file->storeAs($finalPath, $finalName, Constants::DISK_FILES);
                    $invoice->path_xml = $path;
                    $invoice->status_xml = StatusXmlInvoiceEnum::INVOICE_STATUS_XML_003;
                    $invoice->validationXml = null;
                } else {
                    $invoice->status_xml = StatusXmlInvoiceEnum::INVOICE_STATUS_XML_002;
                    $invoice->validationXml = json_encode($infoValidation['errorMessages']);
                }

                // Guardar el estado de la factura
                $invoice->save();

                InvoiceRowUpdatedNow::dispatch($invoice->id);

                // Devolver la respuesta adecuada
                return [
                    'code' => 200,
                    'message' => $infoValidation['totalErrorMessages'] == 0 ? 'Archivo subido con éxito' : 'Validaciones finalizadas',
                    'invoice' => $invoice,
                ];
            }
        });
    }

    public function showErrorsValidationXml($id)
    {
        return $this->execute(function () use ($id) {

            // Obtener los mensajes de errores de las validaciones
            $invoice = $this->invoiceRepository->find($id, select: ['id', 'validationXml']);

            return [
                'code' => 200,
                'errorMessages' => json_decode($invoice->validationXml, 1),
            ];
        });
    }

    public function excelErrorsValidation($id)
    {
        return $this->execute(function () use ($id) {

            // Obtener los mensajes de errores de las validaciones
            $invoice = $this->invoiceRepository->find($id, select: ['id', 'validationXml']);
            $errorMessages = json_decode($invoice->validationXml, 1);

            $excel = Excel::raw(new InvoiceExcelErrorsValidationXmlExport($errorMessages), \Maatwebsite\Excel\Excel::XLSX);

            $excelBase64 = base64_encode($excel);

            return [
                'code' => 200,
                'excel' => $excelBase64,
            ];
        });
    }

    public function dataUrgeHosBorn($id)
    {
        return $this->execute(function () use ($id) {
            // Definir los tipos de servicio que queremos consultar
            $serviceTypes = [
                TypeServiceEnum::SERVICE_TYPE_003->value,
                TypeServiceEnum::SERVICE_TYPE_004->value,
                TypeServiceEnum::SERVICE_TYPE_005->value,
            ];


            // Consulta para obtener los servicios que coincidan con los tipos deseados
            $services = Service::where("invoice_id", $id)
                ->whereIn('type', $serviceTypes)
                ->get();

            // Agrupar servicios por tipo
            $groupedServices = $services->groupBy('type');

            // Construir el array de resultados, incluyendo todos los tipos posibles
            $result = [];
            foreach ($serviceTypes as $type) {
                $serviceType = TypeServiceEnum::from($type);

                // Verificar si existen servicios para este tipo
                $servicesByType = $groupedServices->get($type, collect([]));
                $hasServices = $servicesByType->isNotEmpty();
                $serviceId = $hasServices ? $servicesByType->first()->id : null; // ID del primer servicio (para Edit/Delete)

                $result[] = [
                    'icon' => $serviceType->icon(),
                    'color' =>  $serviceType->color(),
                    'title' => $serviceType->description(),
                    'value' => $servicesByType->count(), // Cantidad de servicios de este tipo
                    'secondary_data' =>  null,
                    'change_label' => null,
                    'isHover' => false,
                    'modal' => $serviceType->model(), // Identificador del modal
                    'type' => $type, // Enviar el tipo de servicio para el frontend
                    'hasServices' => $hasServices, // Indicar si existen servicios
                    'serviceId' => $serviceId, // ID para editar/eliminar
                ];
            }

            return [
                'services' => $result,
                'code' => 200,
            ];
        });
    }
}

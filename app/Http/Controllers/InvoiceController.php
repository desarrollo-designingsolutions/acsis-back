<?php

namespace App\Http\Controllers;

use App\Enums\Invoice\StatusXmlInvoiceEnum;
use App\Enums\Invoice\TypeInvoiceEnum;
use App\Enums\Service\TypeServiceEnum;
use App\Events\InvoiceRowUpdatedNow;
use App\Exports\Invoice\InvoiceExcelErrorsValidationXmlExport;
use App\Exports\Invoice\InvoiceExcelExport;
use App\Helpers\Constants;
use App\Helpers\Invoice\JsonStructureValidation;
use App\Http\Requests\Invoice\InvoiceStoreRequest;
use App\Http\Requests\Invoice\InvoiceUploadJsonRequest;
use App\Http\Resources\Invoice\InvoiceFormResource;
use App\Http\Resources\Invoice\InvoiceListResource;
use App\Http\Resources\InvoiceSoat\InvoiceSoatFormResource;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\Municipio;
use App\Models\Pais;
use App\Models\RipsTipoUsuarioVersion2;
use App\Models\Service;
use App\Models\Sexo;
use App\Models\TipoIdPisis;
use App\Models\ZonaVersion2;
use App\Repositories\Cie10Repository;
use App\Repositories\ConceptoRecaudoRepository;
use App\Repositories\CondicionyDestinoUsuarioEgresoRepository;
use App\Repositories\CupsRipsRepository;
use App\Repositories\EntityRepository;
use App\Repositories\GrupoServicioRepository;
use App\Repositories\HospitalizationRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\InvoiceSoatRepository;
use App\Repositories\MedicalConsultationRepository;
use App\Repositories\MedicineRepository;
use App\Repositories\ModalidadAtencionRepository;
use App\Repositories\MunicipioRepository;
use App\Repositories\NewlyBornRepository;
use App\Repositories\OtherServiceRepository;
use App\Repositories\PaisRepository;
use App\Repositories\PatientRepository;
use App\Repositories\ProcedureRepository;
use App\Repositories\RipsCausaExternaVersion2Repository;
use App\Repositories\RipsFinalidadConsultaVersion2Repository;
use App\Repositories\RipsTipoDiagnosticoPrincipalVersion2Repository;
use App\Repositories\RipsTipoUsuarioVersion2Repository;
use App\Repositories\ServiceRepository;
use App\Repositories\ServiceVendorRepository;
use App\Repositories\ServicioRepository;
use App\Repositories\SexoRepository;
use App\Repositories\TipoIdPisisRepository;
use App\Repositories\TipoMedicamentoPosVersion2Repository;
use App\Repositories\TipoNotaRepository;
use App\Repositories\TipoOtrosServiciosRepository;
use App\Repositories\TypeEntityRepository;
use App\Repositories\UmmRepository;
use App\Repositories\UrgencyRepository;
use App\Repositories\ViaIngresoUsuarioRepository;
use App\Repositories\ZonaVersion2Repository;
use App\Services\CacheService;
use App\Services\JsonValidation\JsonDataValidation;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class InvoiceController extends Controller
{
    use HttpResponseTrait;

    private $key_redis_project;

    public function __construct(
        protected InvoiceRepository $invoiceRepository,
        protected QueryController $queryController,
        protected TypeEntityRepository $typeEntityRepository,
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
        protected JsonDataValidation $jsonDataValidation,

        protected MedicalConsultationRepository $medicalConsultationRepository,
        protected ProcedureRepository $procedureRepository,
        protected MedicineRepository $medicineRepository,
        protected OtherServiceRepository $otherServiceRepository,
        protected UrgencyRepository $urgencyRepository,
        protected HospitalizationRepository $hospitalizationRepository,
        protected NewlyBornRepository $newlyBornRepository,
        protected ServiceRepository $serviceRepository,

        protected CacheService $cacheService

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
            $insuranceStatus = $this->queryController->selectInfiniteInsuranceStatus(request());

            // Filter out the excluded types and map the remaining cases
            $typeInvoiceEnumValues = array_map(function ($case) {
                return [
                    'type' => $case->value,
                    'title' => $case->description(),
                ];
            }, array_filter(TypeInvoiceEnum::cases(), function ($case) {
                return $case->value;
            }));

            return [
                'code' => 200,
                ...$statusInvoiceEnum,
                ...$serviceVendors,
                ...$entities,
                ...$tipoNotas,
                ...$patients,
                ...$insuranceStatus,
                'typeInvoiceEnumValues' => $typeInvoiceEnumValues,
            ];
        });
    }

    public function store(InvoiceStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            // Extract and prepare data
            $post = $request->except(['entity', 'patient', 'tipoNota', 'serviceVendor', 'soat', 'value_paid', 'total', 'remaining_balance', 'value_glosa']);
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

            $lastService = $invoice->services()->latest()->first();

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
            $insuranceStatus = $this->queryController->selectInfiniteInsuranceStatus(request());

            // Filter out the excluded types and map the remaining cases
            $typeInvoiceEnumValues = array_map(function ($case) {
                return [
                    'type' => $case->value,
                    'title' => $case->description(),
                ];
            }, array_filter(TypeInvoiceEnum::cases(), function ($case) {
                return $case->value;
            }));

            return [
                'code' => 200,
                'form' => $form,
                'infoDataExtra' => $infoDataExtra,
                ...$statusInvoiceEnum,
                ...$serviceVendors,
                ...$entities,
                ...$tipoNotas,
                ...$patients,
                ...$insuranceStatus,
                'typeInvoiceEnumValues' => $typeInvoiceEnumValues,
                'service_date' => $lastService?->created_at ? Carbon::parse($lastService?->created_at)->format('Y-m-d') : '',
            ];
        });
    }

    public function update(InvoiceStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except(['entity', 'patient', 'tipoNota', 'serviceVendor', 'soat', 'value_paid', 'total', 'remaining_balance', 'value_glosa']);
            $type = $request->input('type');

            $infoDataExtra = $this->saveDataExtraInvoice($type, $request->all());

            $post['typeable_type'] = $infoDataExtra['model'];
            $post['typeable_id'] = $infoDataExtra['id'];

            $invoiceOld = $this->invoiceRepository->find($post['id']);

            // if ($invoiceOld->type->value == 'INVOICE_TYPE_002') {
            //     $invoiceOld->typeable()->delete();
            // }

            $invoice = $this->invoiceRepository->store($post);

            // Build JSON structure
            $jsonData = $this->buildInvoiceJson($invoice->id);

            // Store JSON file
            $this->storeJsonFile($invoice, $jsonData);

            $infoDataExtra = null;
            // Recuperamos informacion extra dependiendo del tipo de factura
            if ($invoice->type->value == 'INVOICE_TYPE_002') {
                $soat = $this->invoiceSoatRepository->find($invoice->typeable_id);
                $infoDataExtra = new InvoiceSoatFormResource($soat);
            }

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

            unset($dataSoat['model']);
            // Store POLICY and invoice
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

    public function validateInvoiceNumber(Request $request)
    {
        return $this->execute(function () use ($request) {

            $request->validate([
                'invoice_number' => 'required|string',
                'service_vendor_id' => 'required|string',
                'entity_id' => 'required|string',
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
        $sexo = $patient->sexo ?? null;
        $tipoUsuario = $patient->rips_tipo_usuario_version2 ?? null;
        $pais_residency = $patient->pais_residency ?? null;
        $pais_origin = $patient->pais_origin ?? null;
        $municipio = $patient->municipio_residency ?? null;
        $zonaVersion2 = $patient->zona_version2 ?? null;
        $tipoIdPisis = $patient->tipo_id_pisi ?? null;
        $tipoNota = $invoice->tipoNota ?? null;
        $serviceVendor = $invoice->serviceVendor ?? null;

        $nit = $serviceVendor->nit ?? '';
        if (! empty($nit)) {
            // Split on hyphen and take the first part (or whole string if no hyphen)
            $nit = explode('-', $nit)[0];
            // Remove dots
            $nit = str_replace('.', '', $nit);
        }

        // Build base invoice data
        $baseData = [
            'numDocumentoIdObligado' => $nit ?? null,
            'numFactura' => $invoice->invoice_number ?? null,
            'tipoNota' => $tipoNota->codigo ?? null,
            'numNota' => $invoice->note_number ?? null,
        ];

        // Build user data
        $users = [
            [
                'tipoDocumentoIdentificacion' => $tipoIdPisis->codigo ?? null,
                'numDocumentoIdentificacion' => $patient->document ?? null,
                'tipoUsuario' => $tipoUsuario->codigo ?? null,
                'fechaNacimiento' => $patient->birth_date ?? null,
                'codSexo' => $sexo->codigo ?? null,
                'codPaisResidencia' => $pais_residency->codigo ?? null,
                'codMunicipioResidencia' => $municipio->codigo ?? null,
                'codZonaTerritorialResidencia' => $zonaVersion2->codigo ?? null,
                'incapacidad' => $patient ? $patient->incapacity == 1 ? 'SI' : 'NO' : '',
                'consecutivo' => 1,
                'codPaisOrigen' => $pais_origin->codigo ?? null,
            ],
        ];

        // Combine base data and users into final JSON structure
        $newData = $baseData;
        $newData['usuarios'] = $users;

        // Define file path
        $nameFile = $invoice->id . '.json';
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
        $nameFile = $invoice->id . '.json';
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
        $fileName = $invoice->id . '.json'; // Nombre del archivo para la descarga

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
                    'service_vendor_nit' => $invoice->serviceVendor?->nit,
                ];

                // Validar datos del XML
                  $infoValidation = validateDataFilesXml($request->file('archiveXml')->path(), $data);

                // Determinar el estado y la ruta del archivo XML
                if ($infoValidation['totalErrorMessages'] == 0) {
                    $finalName = "{$invoice->id}_FILEXML.xml";
                    $finalPath = "companies/company_{$company_id}/invoices/{$invoice->type->value}/invoice_{$invoice->id}/{$invoice->invoice_number}/xml";

                    $path = $file->storeAs($finalPath, $finalName, Constants::DISK_FILES);
                    $invoice->path_xml = $path;
                    $invoice->status_xml = StatusXmlInvoiceEnum::INVOICE_STATUS_XML_003;
                    $invoice->validationXml = null;

                    if (empty($invoice->entity_id)) {
                        $entity = Entity::where("nit", $infoValidation['info']['entity']['nit'])->first();
                        $invoice->entity_id = $entity ? $entity->id : null;
                    }
                    if (empty($invoice->invoice_date)) {
                        $invoice->invoice_date = $infoValidation['info']['invoice']['invoice_date'];
                    }
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
            $services = Service::where('invoice_id', $id)
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
                    'color' => $serviceType->color(),
                    'title' => $serviceType->description(),
                    'value' => $servicesByType->count(), // Cantidad de servicios de este tipo
                    'secondary_data' => null,
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

    public function downloadZip($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);

            // Verificar rutas usando el disco correcto (ajusta 'disco' según tu configuración)
            $disk = Storage::disk(Constants::DISK_FILES); // o el disco donde tengas los archivos

            if (! $disk->exists($invoice->path_xml) || ! $disk->exists($invoice->path_json)) {
                return response()->json([
                    'error' => 'Archivos no encontrados',
                ], 404);
            }

            // Crear directorio temporal si no existe
            $tempPath = storage_path('app/temp_zips');
            if (! File::exists($tempPath)) {
                File::makeDirectory($tempPath, 0755, true);
            }

            $zipFileName = 'factura_' . $invoice->id . '.zip';
            $zipPath = $tempPath . '/' . $zipFileName;

            $zip = new ZipArchive;
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                // Agregar archivos usando rutas absolutas
                $zip->addFile($disk->path($invoice->path_xml), 'factura.xml');
                $zip->addFile($disk->path($invoice->path_json), 'factura.json');

                $zip->close();

                // Verificar que el ZIP se creó
                if (! file_exists($zipPath)) {
                    throw new \Exception('Error al generar el archivo ZIP');
                }

                return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
            }

            return response()->json(['error' => 'Error al crear el ZIP'], 500);
        } catch (\Exception $e) {
            \Log::error('Error en downloadZip: ' . $e->getMessage());

            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function uploadJson(InvoiceUploadJsonRequest $request)
    {
        return $this->execute(function () use ($request) {

            $id = $request->input('id', null);
            $company_id = $request->input('company_id');

            // Paso 1: Validar la estructura del JSON
            $file = $request->file('archiveJson');
            $structureResponse = JsonStructureValidation::initValidation($file->getRealPath());

            // if (!$structureResponse['isValid']) {
            //     return [
            //         'code' => 422,
            //         'isValid' => false,
            //         'message' => $structureResponse['message'],
            //         'errors' => $structureResponse['errors'],
            //     ];
            // }

            // Paso 2: Validar los datos del JSON contra la base de datos
            $jsonData = json_decode(file_get_contents($file->getRealPath()), true);
            $dataResponse = $this->jsonDataValidation->validate($jsonData, $company_id);

            $dataResponse["errors"] = [...$dataResponse["errors"], ...$structureResponse["errors"]];


            if (! $dataResponse['isValid']) {
                return [
                    'code' => 422,
                    'isValid' => false,
                    ...$dataResponse,
                ];
            }

            // Paso 3: pasar la informacion del json al resourse del formulario

            $invoice = InvoiceController::saveJsonToForm($dataResponse['validatedData'], $company_id);

            // Si ambas validaciones pasan, proceder con el procesamiento
            return [
                'code' => 200,
                'invoice' => $invoice,
                ...$dataResponse,
                'message' => 'JSON validado correctamente (estructura y datos)',
            ];
        });
    }

    public function jsonToForm(Request $request)
    {
        return $this->execute(function () use ($request) {

            $json = $request->input('json');
            $company_id = $request->input('company_id');

            $invoice = InvoiceController::saveJsonToForm($json, $company_id);

            return [
                'code' => 200,
                'invoice' => $invoice,

            ];
        });
    }

    public function saveJsonToForm($request, $company_id)
    {

        $json = $request;

        // Factura
        $type = TypeInvoiceEnum::INVOICE_TYPE_001;

        $infoDataExtra = $this->saveDataExtraInvoice($type, $request);

        $post['typeable_type'] = $infoDataExtra['model'];
        $post['typeable_id'] = $infoDataExtra['id'];
        $post['company_id'] = $company_id;
        $post['type'] = $type;

        $post['status_xml'] = StatusXmlInvoiceEnum::INVOICE_STATUS_XML_001;

        $post['service_vendor_id'] = $json['numDocumentoIdObligado_data']['id'] ?? null;
        $post['invoice_number'] = $json['numFactura'];
        $post['tipo_nota_id'] = $json['tipoNota_data']['id'] ?? null;
        $post['note_number'] = $json['numNota'];
        $post['patient_id'] = $json['usuarios'][0]['numDocumentoIdentificacion_data']['id'] ?? null;


        if (empty($post['patient_id'])) {

            $tipoDocumentoIdentificacion = TipoIdPisis::select(["id"])->where("codigo", $json['usuarios'][0]['tipoDocumentoIdentificacion'])->first();
            if ($tipoDocumentoIdentificacion) {
                $tipoDocumentoIdentificacion = $tipoDocumentoIdentificacion->id;
            }

            $tipoUsuario = RipsTipoUsuarioVersion2::select(["id"])->where("codigo", $json['usuarios'][0]['tipoUsuario'])->first();
            if ($tipoUsuario) {
                $tipoUsuario = $tipoUsuario->id;
            }
            $codSexo = Sexo::select(["id"])->where("codigo", $json['usuarios'][0]['codSexo'])->first();
            if ($codSexo) {
                $codSexo = $codSexo->id;
            }
            $codPaisResidencia = Pais::select(["id"])->where("codigo", $json['usuarios'][0]['codPaisResidencia'])->first();
            if ($codPaisResidencia) {
                $codPaisResidencia = $codPaisResidencia->id;
            }
            $codMunicipioResidencia = Municipio::select(["id"])->where("codigo", $json['usuarios'][0]['codMunicipioResidencia'])->first();
            if ($codMunicipioResidencia) {
                $codMunicipioResidencia = $codMunicipioResidencia->id;
            }
            $codZonaTerritorialResidencia = ZonaVersion2::select(["id"])->where("codigo", $json['usuarios'][0]['codZonaTerritorialResidencia'])->first();
            if ($codZonaTerritorialResidencia) {
                $codZonaTerritorialResidencia = $codZonaTerritorialResidencia->id;
            }
            $codPaisOrigen = Pais::select(["id"])->where("codigo", $json['usuarios'][0]['codPaisOrigen'])->first();
            if ($codPaisOrigen) {
                $codPaisOrigen = $codPaisOrigen->id;
            }

            $patient = [
                "company_id" => $company_id,
                "tipo_id_pisi_id" => $tipoDocumentoIdentificacion,
                "document" => $json['usuarios'][0]['numDocumentoIdentificacion'],
                "rips_tipo_usuario_version2_id" => $tipoUsuario,
                "birth_date" => $json['usuarios'][0]['fechaNacimiento'],
                "sexo_id" => $codSexo,
                "pais_residency_id" => $codPaisResidencia,
                "municipio_residency_id" => $codMunicipioResidencia,
                "zona_version2_id" => $codZonaTerritorialResidencia,
                "incapacity" => $json['usuarios'][0]['incapacidad'] == "SI" ? 1 : 0,
                "pais_origin_id" => $codPaisOrigen,
            ];

            $patient = $this->patientRepository->store($patient);


            $this->cacheService->clearByPrefix($this->key_redis_project . 'string:patients*');


            $post['patient_id'] = $patient->id;
        }


        $invoice = $this->invoiceRepository->store($post);

        // Build JSON structure
        $jsonData = $this->buildInvoiceJson($invoice->id);

        // Store JSON file
        $this->storeJsonFile($invoice, $jsonData);

        $post['invoice_id'] = $invoice->id;

        // Consultas
        $consultas = $json['usuarios'][0]['servicios']['consultas'] ?? [];

        if (count($consultas) > 0) {
            foreach ($consultas as $key => $value) {

                // Create MedicalConsultation
                $medicalConsultation = $this->medicalConsultationRepository->store([
                    'fechaInicioAtencion' => $value['fechaInicioAtencion'],
                    'numAutorizacion' => $value['numAutorizacion'],
                    'codConsulta_id' => $value['codConsulta_data']['id'] ?? null,
                    'modalidadGrupoServicioTecSal_id' => $value['modalidadGrupoServicioTecSal_data']['id'] ?? null,
                    'grupoServicios_id' => $value['grupoServicios_data']['id'] ?? null,
                    'codServicio_id' => $value['codServicio_data']['id'] ?? null,
                    'finalidadTecnologiaSalud_id' => $value['finalidadTecnologiaSalud_data']['id'] ?? null,
                    'causaMotivoAtencion_id' => $value['causaMotivoAtencion_data']['id'] ?? null,
                    'codDiagnosticoPrincipal_id' => $value['codDiagnosticoPrincipal_data']['id'] ?? null,
                    'codDiagnosticoRelacionado1_id' => $value['codDiagnosticoRelacionado1_data']['id'] ?? null,
                    'codDiagnosticoRelacionado2_id' => $value['codDiagnosticoRelacionado2_data']['id'] ?? null,
                    'codDiagnosticoRelacionado3_id' => $value['codDiagnosticoRelacionado3_data']['id'] ?? null,
                    'tipoDiagnosticoPrincipal_id' => $value['tipoDiagnosticoPrincipal_data']['id'] ?? null,
                    'valorPagoModerador' => $value['valorPagoModerador'],
                    'vrServicio' => $value['vrServicio'],
                    'conceptoRecaudo_id' => $value['conceptoRecaudo_data']['id'] ?? null,
                    'tipoDocumentoIdentificacion_id' => $value['tipoDocumentoIdentificacion_data']['id'] ?? null,
                    'numDocumentoIdentificacion' => $value['numDocumentoIdentificacion'],
                    'numFEVPagoModerador' => $value['numFEVPagoModerador'],
                ]);

                // Get the next consecutivo
                $consecutivo = getNextConsecutivo($post['invoice_id'], TypeServiceEnum::SERVICE_TYPE_001);

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
                    'unit_value' => $value['vrServicio'],
                    'total_value' => $value['vrServicio'],
                ]);

                // Prepare service data for JSON
                $serviceData = [
                    'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo,
                    'fechaInicioAtencion' => Carbon::parse($value['fechaInicioAtencion'])->format('Y-m-d H:i'),
                    'numAutorizacion' => $value['numAutorizacion'],
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
                    'numDocumentoIdentificacion' => $value['numDocumentoIdentificacion'],
                    'vrServicio' => floatval($value['vrServicio']),
                    'conceptoRecaudo' => $medicalConsultation->conceptoRecaudo?->codigo,
                    'valorPagoModerador' => floatval($value['valorPagoModerador']),
                    'numFEVPagoModerador' => $value['numFEVPagoModerador'],
                    'consecutivo' => $consecutivo,
                ];

                // Update JSON with new service
                updateInvoiceServicesJson(
                    $post['invoice_id'],
                    TypeServiceEnum::SERVICE_TYPE_001,
                    $serviceData,
                    'add'
                );
            }
        }
        // Procedimientos
        $procedimientos = $json['usuarios'][0]['servicios']['procedimientos'] ?? [];

        if (count($procedimientos) > 0) {
            foreach ($procedimientos as $key => $value) {

                // Create Procedure
                $procedure = $this->procedureRepository->store([
                    'fechaInicioAtencion' => Carbon::parse($value['fechaInicioAtencion'])->format('Y-m-d H:i'),
                    'idMIPRES' => $value['idMIPRES'],
                    'numAutorizacion' => $value['numAutorizacion'],
                    'codProcedimiento_id' => $value['codProcedimiento_data']['id'] ?? null,
                    'viaIngresoServicioSalud_id' => $value['viaIngresoServicioSalud_data']['id'] ?? null,
                    'modalidadGrupoServicioTecSal_id' => $value['modalidadGrupoServicioTecSal_data']['id'] ?? null,
                    'grupoServicios_id' => $value['grupoServicios_data']['id'] ?? null,
                    'codServicio_id' => $value['codServicio_data']['id'] ?? null,
                    'finalidadTecnologiaSalud_id' => $value['finalidadTecnologiaSalud_data']['id'] ?? null,
                    'codDiagnosticoPrincipal_id' => $value['codDiagnosticoPrincipal_data']['id'] ?? null,
                    'codDiagnosticoRelacionado_id' => $value['codDiagnosticoRelacionado_data']['id'] ?? null,
                    'codComplicacion_id' => $value['codComplicacion_data']['id'] ?? null,
                    'valorPagoModerador' => $value['valorPagoModerador'],
                    'vrServicio' => $value['vrServicio'],
                    'conceptoRecaudo_id' => $value['conceptoRecaudo_data']['id'] ?? null,
                    'tipoDocumentoIdentificacion_id' => $value['tipoDocumentoIdentificacion_data']['id'] ?? null,
                    'numDocumentoIdentificacion' => $value['numDocumentoIdentificacion'],
                    'numFEVPagoModerador' => $value['numFEVPagoModerador'],
                ]);

                // Get the next consecutivo
                $consecutivo = getNextConsecutivo($post['invoice_id'], TypeServiceEnum::SERVICE_TYPE_002);

                // Create Service
                $service = $this->serviceRepository->store([
                    'company_id' => $post['company_id'],
                    'invoice_id' => $post['invoice_id'],
                    'consecutivo' => $consecutivo,
                    'type' => TypeServiceEnum::SERVICE_TYPE_002,
                    'serviceable_type' => TypeServiceEnum::SERVICE_TYPE_002->model(),
                    'serviceable_id' => $procedure->id,
                    'codigo_servicio' => $procedure->codProcedimiento?->codigo,
                    'nombre_servicio' => $procedure->codProcedimiento?->nombre,
                    'quantity' => 1,
                    'unit_value' => $value['vrServicio'],
                    'total_value' => $value['vrServicio'],
                ]);

                // Prepare service data for JSON
                $serviceData = [
                    'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo,
                    'fechaInicioAtencion' => Carbon::parse($value['fechaInicioAtencion'])->format('Y-m-d H:i'),
                    'idMIPRES' => $value['idMIPRES'],
                    'numAutorizacion' => $value['numAutorizacion'],
                    'codProcedimiento' => $procedure->codProcedimiento?->codigo,
                    'viaIngresoServicioSalud' => $procedure->viaIngresoServicioSalud?->codigo,
                    'modalidadGrupoServicioTecSal' => $procedure->modalidadGrupoServicioTecSal?->codigo,
                    'grupoServicios' => $procedure->grupoServicios?->codigo,
                    'codServicio' => intval($procedure->codServicio?->codigo),
                    'finalidadTecnologiaSalud' => $procedure->finalidadTecnologiaSalud?->codigo,
                    'tipoDocumentoIdentificacion' => $procedure->tipoDocumentoIdentificacion?->codigo,
                    'numDocumentoIdentificacion' => $value['numDocumentoIdentificacion'],
                    'codDiagnosticoPrincipal' => $procedure->codDiagnosticoPrincipal?->codigo,
                    'codDiagnosticoRelacionado' => $procedure->codDiagnosticoRelacionado?->codigo ?? '',
                    'codComplicacion' => $procedure->codComplicacion?->codigo ?? '',
                    'vrServicio' => floatval($value['vrServicio']),
                    'conceptoRecaudo' => $procedure->conceptoRecaudo?->codigo,
                    'valorPagoModerador' => floatval($value['valorPagoModerador']),
                    'numFEVPagoModerador' => $value['numFEVPagoModerador'],
                    'consecutivo' => $consecutivo,
                ];

                // Update JSON with new service
                updateInvoiceServicesJson(
                    $post['invoice_id'],
                    TypeServiceEnum::SERVICE_TYPE_002,
                    $serviceData,
                    'add'
                );
            }
        }

        // Medicamentos
        $medicamentos = $json['usuarios'][0]['servicios']['medicamentos'] ?? [];

        if (count($medicamentos) > 0) {
            foreach ($medicamentos as $key => $value) {

                // Create Medicine
                $medicine = $this->medicineRepository->store([
                    'numAutorizacion' => $value['numAutorizacion'],
                    'idMIPRES' => $value['idMIPRES'],
                    'fechaDispensAdmon' => $value['fechaDispensAdmon'],
                    'codDiagnosticoPrincipal_id' => $value['codDiagnosticoPrincipal_data']['id'] ?? null,
                    'codDiagnosticoRelacionado_id' => $value['codDiagnosticoRelacionado_data']['id'] ?? null,
                    'tipoMedicamento_id' => $value['tipoMedicamento_data']['id'] ?? null,
                    'codTecnologiaSalud' => $value['codTecnologiaSalud'],
                    'nomTecnologiaSalud' => $value['nomTecnologiaSalud'],
                    'concentracionMedicamento' => $value['concentracionMedicamento'],
                    'unidadMedida_id' => $value['unidadMedida_data']['id'] ?? null,
                    'formaFarmaceutica' => $value['formaFarmaceutica'],
                    'unidadMinDispensa' => $value['unidadMinDispensa'],
                    'cantidadMedicamento' => $value['cantidadMedicamento'],
                    'diasTratamiento' => $value['diasTratamiento'],
                    'vrUnitMedicamento' => $value['vrUnitMedicamento'],
                    'valorPagoModerador' => $value['valorPagoModerador'],
                    'vrServicio' => $value['vrServicio'],
                    'conceptoRecaudo_id' => $value['conceptoRecaudo_data']['id'] ?? null,
                    'tipoDocumentoIdentificacion_id' => $value['tipoDocumentoIdentificacion_data']['id'] ?? null,
                    'numDocumentoIdentificacion' => $value['numDocumentoIdentificacion'],
                    'numFEVPagoModerador' => $value['numFEVPagoModerador'],
                ]);

                // Get the next consecutivo
                $consecutivo = getNextConsecutivo($post['invoice_id'], TypeServiceEnum::SERVICE_TYPE_006);

                // Create Service
                $service = $this->serviceRepository->store([
                    'company_id' => $post['company_id'],
                    'invoice_id' => $post['invoice_id'],
                    'consecutivo' => $consecutivo,
                    'type' => TypeServiceEnum::SERVICE_TYPE_006,
                    'serviceable_type' => TypeServiceEnum::SERVICE_TYPE_006->model(),
                    'serviceable_id' => $medicine->id,
                    'codigo_servicio' => $value['codTecnologiaSalud'],
                    'nombre_servicio' => $value['nomTecnologiaSalud'],
                    'quantity' => $value['cantidadMedicamento'],
                    'unit_value' => $value['vrServicio'],
                    'total_value' => $value['vrServicio'],
                ]);

                // Prepare service data for JSON
                $serviceData = [
                    'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo ?? '',
                    'numAutorizacion' => $value['numAutorizacion'] ?? '',
                    'idMIPRES' => $value['idMIPRES'] ?? '',
                    'fechaDispensAdmon' => Carbon::parse($value['fechaDispensAdmon'])->format('Y-m-d H:i'),
                    'codDiagnosticoPrincipal' => $medicine->codDiagnosticoPrincipal?->codigo,
                    'codDiagnosticoRelacionado' => $medicine->codDiagnosticoRelacionado?->codigo ?? '',
                    'tipoMedicamento' => $medicine->tipoMedicamento?->codigo,
                    'codTecnologiaSalud' => $value['codTecnologiaSalud'],
                    'nomTecnologiaSalud' => $value['nomTecnologiaSalud'] ?? '',
                    'concentracionMedicamento' => floatval($value['concentracionMedicamento']) ?? '',
                    'unidadMedida' => floatval($medicine->unidadMedida?->codigo) ?? '',
                    'formaFarmaceutica' => $value['formaFarmaceutica'] ?? '',
                    'unidadMinDispensa' => floatval($value['unidadMinDispensa']),
                    'cantidadMedicamento' => intval($value['cantidadMedicamento']),
                    'diasTratamiento' => intval($value['diasTratamiento']),
                    'tipoDocumentoIdentificacion' => $medicine->tipoDocumentoIdentificacion?->codigo,
                    'numDocumentoIdentificacion' => $value['numDocumentoIdentificacion'],
                    'vrUnitMedicamento' => floatval($value['vrUnitMedicamento']),
                    'vrServicio' => floatval($value['vrServicio']),
                    'conceptoRecaudo' => $medicine->conceptoRecaudo?->codigo,
                    'valorPagoModerador' => floatval($value['valorPagoModerador']),
                    'numFEVPagoModerador' => $value['numFEVPagoModerador'],
                    'consecutivo' => $consecutivo,
                ];

                // Update JSON with new service
                updateInvoiceServicesJson(
                    $post['invoice_id'],
                    TypeServiceEnum::SERVICE_TYPE_006,
                    $serviceData,
                    'add'
                );
            }
        }

        // Otros Servicios
        $otrosServicios = $json['usuarios'][0]['servicios']['otrosServicios'] ?? [];

        if (count($otrosServicios) > 0) {
            foreach ($otrosServicios as $key => $value) {

                // Create OtherService
                $otherService = $this->otherServiceRepository->store([
                    'idMIPRES' => $value['idMIPRES'],
                    'numAutorizacion' => $value['numAutorizacion'],
                    'fechaSuministroTecnologia' => $value['fechaSuministroTecnologia'],
                    'tipoOS_id' => $value['tipoOS_data']['id'] ?? null,
                    'codTecnologiaSalud' => $value['codTecnologiaSalud'],
                    'nomTecnologiaSalud' => $value['nomTecnologiaSalud'],
                    'cantidadOS' => $value['cantidadOS'],
                    'vrUnitOS' => $value['vrUnitOS'],
                    'valorPagoModerador' => $value['valorPagoModerador'],
                    'vrServicio' => $value['vrServicio'],
                    'conceptoRecaudo_id' => $value['conceptoRecaudo_data']['id'] ?? null,
                    'tipoDocumentoIdentificacion_id' => $value['tipoDocumentoIdentificacion_data']['id'] ?? null,
                    'numDocumentoIdentificacion' => $value['numDocumentoIdentificacion'],
                    'numFEVPagoModerador' => $value['numFEVPagoModerador'],
                ]);

                // Get the next consecutivo
                $consecutivo = getNextConsecutivo($post['invoice_id'], TypeServiceEnum::SERVICE_TYPE_007);

                // Create Service
                $service = $this->serviceRepository->store([
                    'company_id' => $post['company_id'],
                    'invoice_id' => $post['invoice_id'],
                    'consecutivo' => $consecutivo,
                    'type' => TypeServiceEnum::SERVICE_TYPE_007,
                    'serviceable_type' => TypeServiceEnum::SERVICE_TYPE_007->model(),
                    'serviceable_id' => $otherService->id,
                    'codigo_servicio' => $value['codTecnologiaSalud'],
                    'nombre_servicio' => $value['nomTecnologiaSalud'],
                    'quantity' => $value['cantidadOS'],
                    'unit_value' => $value['vrUnitOS'],
                    'total_value' => $value['vrServicio'],
                ]);

                // Prepare service data for JSON
                $serviceData = [
                    'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo,
                    'numAutorizacion' => $value['numAutorizacion'],
                    'idMIPRES' => $value['idMIPRES'] ?? '',
                    'fechaSuministroTecnologia' => Carbon::parse($value['fechaSuministroTecnologia'])->format('Y-m-d H:i'),
                    'tipoOS' => $otherService->tipoOtrosServicio?->codigo,
                    'codTecnologiaSalud' => $value['codTecnologiaSalud'],
                    'nomTecnologiaSalud' => $value['nomTecnologiaSalud'],
                    'cantidadOS' => intval($value['cantidadOS']),
                    'tipoDocumentoIdentificacion' => $otherService->tipoDocumentoIdentificacion?->codigo,
                    'numDocumentoIdentificacion' => $value['numDocumentoIdentificacion'],
                    'vrUnitOS' => floatval($value['vrUnitOS']),
                    'vrServicio' => floatval($value['vrServicio']),
                    'conceptoRecaudo' => $otherService->conceptoRecaudo?->codigo,
                    'valorPagoModerador' => floatval($value['valorPagoModerador']),
                    'numFEVPagoModerador' => $value['numFEVPagoModerador'],
                    'consecutivo' => $consecutivo,
                ];

                // Update JSON with new service
                updateInvoiceServicesJson(
                    $post['invoice_id'],
                    TypeServiceEnum::SERVICE_TYPE_007,
                    $serviceData,
                    'add'
                );
            }
        }

        // Urgencias
        $urgencias = $json['usuarios'][0]['servicios']['urgencias'] ?? [];

        if (count($urgencias) > 0) {
            foreach ($urgencias as $key => $value) {

                // Create Urgency
                $urgency = $this->urgencyRepository->store([
                    'fechaInicioAtencion' => $value['fechaInicioAtencion'],
                    'causaMotivoAtencion_id' => $value['causaMotivoAtencion_data']['id'] ?? null,
                    'codDiagnosticoPrincipal_id' => $value['codDiagnosticoPrincipal_data']['id'] ?? null,
                    'codDiagnosticoPrincipalE_id' => $value['codDiagnosticoPrincipalE_data']['id'] ?? null,
                    'codDiagnosticoRelacionadoE1_id' => $value['codDiagnosticoRelacionadoE1_data']['id'] ?? null,
                    'codDiagnosticoRelacionadoE2_id' => $value['codDiagnosticoRelacionadoE2_data']['id'] ?? null,
                    'codDiagnosticoRelacionadoE3_id' => $value['codDiagnosticoRelacionadoE3_data']['id'] ?? null,
                    'condicionDestinoUsuarioEgreso' => $value['condicionDestinoUsuarioEgreso'],
                    'codDiagnosticoCausaMuerte_id' => $value['codDiagnosticoCausaMuerte_data']['id'] ?? null,
                    'fechaEgreso' => $value['fechaEgreso'],
                ]);

                // Get the next consecutivo
                $consecutivo = getNextConsecutivo($post['invoice_id'], TypeServiceEnum::SERVICE_TYPE_003);

                // Create Service
                $service = $this->serviceRepository->store([
                    'company_id' => $post['company_id'],
                    'invoice_id' => $post['invoice_id'],
                    'consecutivo' => $consecutivo,
                    'type' => TypeServiceEnum::SERVICE_TYPE_003,
                    'serviceable_type' => TypeServiceEnum::SERVICE_TYPE_003->model(),
                    'serviceable_id' => $urgency->id,
                    'codigo_servicio' => null,
                    'nombre_servicio' => null,
                    'quantity' => 1,
                    'unit_value' => 0,
                    'total_value' => 0,
                ]);

                // Prepare service data for JSON
                $serviceData = [
                    'codPrestador' => $service->invoice?->serviceVendor?->ipsable?->codigo,
                    'fechaInicioAtencion' => Carbon::parse($value['fechaInicioAtencion'])->format('Y-m-d H:i'),
                    'causaMotivoAtencion' => $urgency->causaMotivoAtencion?->codigo,
                    'codDiagnosticoPrincipal' => $urgency->codDiagnosticoPrincipal?->codigo,
                    'codDiagnosticoPrincipalE' => $urgency->codDiagnosticoPrincipalE?->codigo,
                    'codDiagnosticoRelacionadoE1' => $urgency->codDiagnosticoRelacionadoE1?->codigo ?? '',
                    'codDiagnosticoRelacionadoE2' => $urgency->codDiagnosticoRelacionadoE2?->codigo ?? '',
                    'codDiagnosticoRelacionadoE3' => $urgency->codDiagnosticoRelacionadoE3?->codigo ?? '',
                    'condicionDestinoUsuarioEgreso' => $value['condicionDestinoUsuarioEgreso'],
                    'codDiagnosticoCausaMuerte' => $urgency->codDiagnosticoCausaMuerte?->codigo ?? '',
                    'fechaEgreso' => Carbon::parse($value['fechaEgreso'])->format('Y-m-d H:i'),
                    'consecutivo' => $consecutivo,
                ];

                // Update JSON with new service
                updateInvoiceServicesJson(
                    $post['invoice_id'],
                    TypeServiceEnum::SERVICE_TYPE_003,
                    $serviceData,
                    'add'
                );
            }
        }

        // Hospitalizacion
        $hospitalizacion = $json['usuarios'][0]['servicios']['hospitalizacion'] ?? [];

        if (count($hospitalizacion) > 0) {
            foreach ($hospitalizacion as $key => $value) {

                // Create Hospitalization
                $hospitalization = $this->hospitalizationRepository->store([
                    'viaIngresoServicioSalud_id' => $value['viaIngresoServicioSalud_data']['id'] ?? null,
                    'fechaInicioAtencion' => $value['fechaInicioAtencion'],
                    'numAutorizacion' => $value['numAutorizacion'],
                    'causaMotivoAtencion_id' => $value['causaMotivoAtencion_data']['id'] ?? null,
                    'codDiagnosticoPrincipal_id' => $value['codDiagnosticoPrincipal_data']['id'] ?? null,
                    'codDiagnosticoPrincipalE_id' => $value['codDiagnosticoPrincipalE_data']['id'] ?? null,
                    'codDiagnosticoRelacionadoE1_id' => $value['codDiagnosticoRelacionadoE1_data']['id'] ?? null,
                    'codDiagnosticoRelacionadoE2_id' => $value['codDiagnosticoRelacionadoE2_data']['id'] ?? null,
                    'codDiagnosticoRelacionadoE3_id' => $value['codDiagnosticoRelacionadoE3_data']['id'] ?? null,
                    'codComplicacion_id' => $value['codComplicacion_data']['id'] ?? null,
                    'condicionDestinoUsuarioEgreso_id' => $value['condicionDestinoUsuarioEgreso_data']['id'] ?? null,
                    'codDiagnosticoCausaMuerte_id' => $value['codDiagnosticoCausaMuerte_data']['id'] ?? null,
                    'fechaEgreso' => $value['fechaEgreso'],
                ]);

                // Get the next consecutivo
                $consecutivo = getNextConsecutivo($post['invoice_id'], TypeServiceEnum::SERVICE_TYPE_004);

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
                    'fechaInicioAtencion' => Carbon::parse($value['fechaInicioAtencion'])->format('Y-m-d H:i'),
                    'numAutorizacion' => $value['numAutorizacion'],
                    'causaMotivoAtencion' => $hospitalization->causaMotivoAtencion?->codigo,
                    'codDiagnosticoPrincipal' => $hospitalization->codDiagnosticoPrincipal?->codigo,
                    'codDiagnosticoPrincipalE' => $hospitalization->codDiagnosticoPrincipalE?->codigo,
                    'codDiagnosticoRelacionadoE1' => $hospitalization->codDiagnosticoRelacionadoE1?->codigo ?? '',
                    'codDiagnosticoRelacionadoE2' => $hospitalization->codDiagnosticoRelacionadoE2?->codigo ?? '',
                    'codDiagnosticoRelacionadoE3' => $hospitalization->codDiagnosticoRelacionadoE3?->codigo ?? '',
                    'codComplicacion' => $hospitalization->codComplicacion?->codigo ?? '',
                    'condicionDestinoUsuarioEgreso' => $hospitalization->condicionDestinoUsuarioEgreso?->codigo,
                    'codDiagnosticoCausaMuerte' => $hospitalization->codDiagnosticoCausaMuerte?->codigo ?? '',
                    'fechaEgreso' => Carbon::parse($value['fechaEgreso'])->format('Y-m-d H:i'),
                    'consecutivo' => $consecutivo,
                ];

                // Update JSON with new service
                updateInvoiceServicesJson(
                    $post['invoice_id'],
                    TypeServiceEnum::SERVICE_TYPE_004,
                    $serviceData,
                    'add'
                );
            }
        }

        // Recien Nacidos
        $recienNacidos = $json['usuarios'][0]['servicios']['recienNacidos'] ?? [];

        if (count($recienNacidos) > 0) {
            foreach ($recienNacidos as $key => $value) {

                // Create NewlyBorn
                $newlyBorn = $this->newlyBornRepository->store([
                    'fechaNacimiento' => $value['fechaNacimiento'],
                    'edadGestacional' => $value['edadGestacional'],
                    'numConsultasCPrenatal' => $value['numConsultasCPrenatal'],
                    'codSexoBiologico_id' => $value['codSexoBiologico_data']['id'] ?? null,
                    'peso' => $value['peso'],
                    'codDiagnosticoPrincipal_id' => $value['codDiagnosticoPrincipal_data']['id'] ?? null,
                    'condicionDestinoUsuarioEgreso_id' => $value['condicionDestinoUsuarioEgreso_data']['id'] ?? null,
                    'codDiagnosticoCausaMuerte_id' => $value['codDiagnosticoCausaMuerte_data']['id'] ?? null,
                    'fechaEgreso' => $value['fechaEgreso'],
                    'tipoDocumentoIdentificacion_id' => $value['tipoDocumentoIdentificacion_data']['id'] ?? null,
                    'numDocumentoIdentificacion' => $value['numDocumentoIdentificacion'],
                ]);

                // Get the next consecutivo
                $consecutivo = getNextConsecutivo($post['invoice_id'], TypeServiceEnum::SERVICE_TYPE_005);

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
                    'numDocumentoIdentificacion' => intval($value['numDocumentoIdentificacion']),
                    'fechaNacimiento' => Carbon::parse($value['fechaNacimiento'])->format('Y-m-d H:i'),
                    'edadGestacional' => intval($value['edadGestacional']),
                    'numConsultasCPrenatal' => intval($value['numConsultasCPrenatal']),
                    'codSexoBiologico' => $newlyBorn->codSexoBiologico?->codigo,
                    'peso' => floatval($value['peso']),
                    'codDiagnosticoPrincipal' => $newlyBorn->codDiagnosticoPrincipal?->codigo,
                    'condicionDestinoUsuarioEgreso' => $newlyBorn->condicionDestinoUsuarioEgreso?->codigo,
                    'codDiagnosticoCausaMuerte' => $newlyBorn->codDiagnosticoCausaMuerte?->codigo ?? '',
                    'fechaEgreso' => Carbon::parse($value['fechaEgreso'])->format('Y-m-d H:i'),
                    'consecutivo' => $consecutivo,
                ];

                // Update JSON with new service
                updateInvoiceServicesJson(
                    $post['invoice_id'],
                    TypeServiceEnum::SERVICE_TYPE_005,
                    $serviceData,
                    'add'
                );
            }
        }

        return $invoice;
    }
}

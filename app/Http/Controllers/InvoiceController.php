<?php

namespace App\Http\Controllers;

use App\Enums\Invoice\TypeInvoiceEnum;
use App\Exports\InvoiceExcelExport;
use App\Helpers\Constants;
use App\Http\Requests\Invoice\InvoiceType001StoreRequest;
use App\Http\Requests\Invoice\InvoiceType002StoreRequest;
use App\Http\Resources\Invoice\InvoiceListResource;
use App\Http\Resources\Invoice\InvoiceType001FormResource;
use App\Http\Resources\Invoice\InvoiceType002FormResource;
use App\Http\Resources\InvoiceSoat\InvoiceSoatFormResource;
use App\Repositories\Cie10Repository;
use App\Repositories\ConceptoRecaudoRepository;
use App\Repositories\CondicionyDestinoUsuarioEgresoRepository;
use App\Repositories\CupsRipsRepository;
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
use App\Traits\HttpResponseTrait;
use App\Repositories\TypeEntityRepository;
use App\Repositories\UmmRepository;
use App\Repositories\ViaIngresoUsuarioRepository;
use App\Repositories\ZonaVersion2Repository;
use App\Services\CacheService;
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
        protected Cie10Repository  $cie10Repository,
        protected RipsTipoDiagnosticoPrincipalVersion2Repository $ripsTipoDiagnosticoPrincipalVersion2Repository,
        protected ConceptoRecaudoRepository $conceptoRecaudoRepository,
        protected ViaIngresoUsuarioRepository $viaIngresoUsuarioRepository,
        protected CondicionyDestinoUsuarioEgresoRepository $condicionyDestinoUsuarioEgresoRepository,
        protected TipoMedicamentoPosVersion2Repository $tipoMedicamentoPosVersion2Repository,
        protected UmmRepository $ummRepository,
        protected TipoOtrosServiciosRepository $tipoOtrosServiciosRepository,

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

    public function createType001()
    {
        return $this->execute(function () {
            return [
                'code' => 200,
            ];
        });
    }

    public function storeType001(InvoiceType001StoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except(['entity', 'patient', 'TipoNota', 'serviceVendor']);

            $post['typeable_type'] = 'App\\Models\\' . $post['type'];

            // $post['typeable_id'] = $soat['id'];

            $post['remaining_balance'] = $post['total'];

            $invoice = $this->invoiceRepository->store($post);

            return [
                'code' => 200,
                'message' => 'Factura agregada correctamente',
            ];
        });
    }

    public function editType001($id)
    {
        return $this->execute(function () use ($id) {
            $invoice = $this->invoiceRepository->find($id);
            $form = new InvoiceType001FormResource($invoice);

            return [
                'code' => 200,
                'form' => $form,
            ];
        });
    }

    public function updateType001(InvoiceType001StoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except(['entity', 'patient', 'TipoNota', 'serviceVendor']);

            $post['typeable_type'] = 'App\\Models\\' . $post['type'];

            // $post['typeable_id'] = $soat['id'];


            $invoice = $this->invoiceRepository->store($post);

            if (!$invoice->invoice_payments()->exists()) {
                $post['remaining_balance'] = $post['total'];
            }

            return [
                'code' => 200,
                'message' => 'Factura modificada correctamente',
            ];
        });
    }

    public function createType002()
    {
        return $this->execute(function () {
            return [
                'code' => 200,
            ];
        });
    }

    public function storeType002(InvoiceType002StoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            // Extract and prepare data
            $post = $request->except(['entity', 'patient', 'TipoNota', 'serviceVendor', 'soat']);
            $dataSoat = array_merge($request->input('soat'), ['company_id' => $request->input('company_id')]);

            // Store SOAT and invoice
            $soat = $this->invoiceSoatRepository->store($dataSoat);
            $post['typeable_type'] = 'App\\Models\\' . $post['type'];
            $post['typeable_id'] = $soat['id'];
            $post['remaining_balance'] = $post['total'];
            $invoice = $this->invoiceRepository->store($post);

            // Build JSON structure
            $jsonData = $this->buildInvoiceJson($invoice->id);

            // Store JSON file
            $this->storeJsonFile($invoice, $jsonData);

            return [
                'code' => 200,
                'message' => 'Factura agregada correctamente',
            ];
        }, debug: false);
    }
    /**
     * Build the JSON structure for the invoice
     */
    private function buildInvoiceJson($invoice_id): array
    {
        $invoice = $this->invoiceRepository->find($invoice_id, [
            "tipoNota",
            "serviceVendor",
            "patient",
            "patient.sexo",
            "patient.rips_tipo_usuario_version2",
            "patient.pais_residency",
            "patient.municipio_residency",
            "patient.zona_version2",
            "patient.tipo_id_pisi",
            "patient.pais_origin",
        ]);

        $patient = $invoice->patient;
        $sexo = $invoice->patient->sexo;
        $tipoUsuario = $invoice->patient->rips_tipo_usuario_version2;
        $pais_residency = $invoice->patient->pais_residency;
        $pais_origin = $invoice->patient->pais_origin;
        $municipio = $invoice->patient->municipio_residency;
        $zonaVersion2 = $invoice->patient->zona_version2;
        $tipoIdPisis = $invoice->patient->tipo_id_pisi;
        $tipoNota = $invoice->tipoNota;
        $serviceVendor = $invoice->serviceVendor;

        // Base invoice data
        $baseData = [
            'numDocumentoIdObligado' => $serviceVendor->nit,
            'numFactura' => $invoice->invoice_number,
            'TipoNota' => $tipoNota->codigo,
            'numNota' => $invoice->note_number
        ];

        // Convert null values to empty strings
        $info = convertNullToEmptyString($baseData);

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
                'servicios' => [
                    'consultas' => [],
                    'procedimientos' => [],
                    'medicamentos' => [],
                    'urgencias' => [],
                    'otrosServicios' => [],
                    'hospitalizacion' => [],
                    'recienNacidos' => []
                ]
            ]
        ];

        $info['usuarios'] = $users;

        return $info;
    }

    /**
     * Store or update the JSON file and save path in invoice
     */
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



    public function editType002($id)
    {
        return $this->execute(function () use ($id) {
            $invoice = $this->invoiceRepository->find($id);
            $form = new InvoiceType002FormResource($invoice);

            $soat = $this->invoiceSoatRepository->find($form->typeable_id);

            $soat = new InvoiceSoatFormResource($soat);

            return [
                'code' => 200,
                'form' => $form,
                'soat' => $soat,
            ];
        });
    }

    public function updateType002(InvoiceType002StoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except(['entity', 'patient', 'TipoNota', 'serviceVendor', 'soat']);

            $dataSoat = $request->input('soat');

            $dataSoat['company_id'] = $request->input('company_id');

            $soat = $this->invoiceSoatRepository->store($dataSoat);

            $post['typeable_type'] = 'App\\Models\\' . $post['type'];

            $post['typeable_id'] = $soat['id'];

            $invoice = $this->invoiceRepository->store($post);

            if (!$invoice->invoice_payments()->exists()) {
                $post['remaining_balance'] = $post['total'];
            }

            // Build JSON structure
            $jsonData = $this->buildInvoiceJson($invoice->id);

            // Store JSON file
            $this->storeJsonFile($invoice, $jsonData);

            return [
                'code' => 200,
                'message' => 'Factura modificada correctamente',
            ];
        });
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

    public function changeStatus(Request $request)
    {
        return $this->runTransaction(function () use ($request) {
            $model = $this->invoiceRepository->changeState($request->input('id'), strval($request->input('value')), $request->input('field'));

            ($model->is_active == 1) ? $msg = 'habilitada' : $msg = 'inhabilitada';

            return [
                'code' => 200,
                'message' => 'Factura ' . $msg . ' con éxito',
            ];
        });
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
        return $this->execute(function () use ($request) {

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
    public function getInfoJson(Request $request)
    {
        return $this->execute(function () use ($request) {

            $invoice = $this->invoiceRepository->find($request->input('id'));
            $path = $invoice->path_json;
            $disk = Constants::DISK_FILES;
            $json = Storage::disk($disk)->get($path);
            $json = json_decode($json, true);

            // Verificar si existe el primer elemento de 'usuarios'
            $firstUser = isset($json['usuarios']) && is_array($json['usuarios']) && !empty($json['usuarios'][0])
                ? $json['usuarios'][0]
                : [];

            // return gettype($usuarios);

            return $dataUser = collect([$firstUser])->map(function ($value) {
                $user = $value; // Copia todos los campos del usuario );

                //MAPEO SERVICIOS DE CONSULTAS
                $consultas = collect($user["servicios"]["consultas"])->map(function ($service) {

                    //CODIGO DE CONSULTA
                    $service['codConsulta'] = $this->cupsRipsRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codConsulta"], "code")
                    ], format: 'selectInfinite');

                    //MODALIDAD GRUPO SERVICIO TECSAL
                    $service['modalidadGrupoServicioTecSal'] = $this->modalidadAtencionRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["modalidadGrupoServicioTecSal"], "code")
                    ], format: 'selectInfinite');

                    //GRUPO SERVICIO
                    $service['grupoServicios'] = $this->grupoServicioRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["grupoServicios"], "code")
                    ], format: 'selectInfinite');

                    //SERVICIO
                    $service['codServicio'] = $this->servicioRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codServicio"], "code")
                    ], format: 'selectInfinite');

                    //FINALIDAD TECNOLOGIA DE SALUD
                    $service['finalidadTecnologiaSalud'] = $this->ripsFinalidadConsultaVersion2Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["finalidadTecnologiaSalud"], "code")
                    ], format: 'selectInfinite');

                    //CAUSA MOTIVO DE ATENCION
                    $service['causaMotivoAtencion'] = $this->ripsCausaExternaVersion2Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["causaMotivoAtencion"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO PRINCIPAL
                    $service['codDiagnosticoPrincipal'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoPrincipal"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO RELACIONADO 1
                    $service['codDiagnosticoRelacionado1'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoRelacionado1"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO RELACIONADO 2
                    $service['codDiagnosticoRelacionado2'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoRelacionado2"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO RELACIONADO 3
                    $service['codDiagnosticoRelacionado3'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoRelacionado3"], "code")
                    ], format: 'selectInfinite');

                    //TIPO DIAGNOSTICO PRINCIPAL
                    $service['tipoDiagnosticoPrincipal'] = $this->ripsTipoDiagnosticoPrincipalVersion2Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["tipoDiagnosticoPrincipal"], "code")
                    ], format: 'selectInfinite');

                    //CONCEPTO RECAUDO
                    $service['conceptoRecaudo'] = $this->conceptoRecaudoRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["conceptoRecaudo"], "code")
                    ], format: 'selectInfinite');

                    return $service;
                });

                //MAPEO SERVICIOS DE PROCEDIMIENTOS
                $procedimientos = collect($user["servicios"]["procedimientos"])->map(function ($service) {

                    //CODIGO DE PROCEDIMIENTO
                    $service['codProcedimiento'] = $this->cupsRipsRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codProcedimiento"], "code")
                    ], format: 'selectInfinite');

                    //VIA INGRESO SERVICIO SALUD
                    $service['viaIngresoServicioSalud'] = $this->viaIngresoUsuarioRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["viaIngresoServicioSalud"], "code")
                    ], format: 'selectInfinite');

                    //MODALIDAD GRUPO SERVICIO TECSAL
                    $service['modalidadGrupoServicioTecSal'] = $this->modalidadAtencionRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["modalidadGrupoServicioTecSal"], "code")
                    ], format: 'selectInfinite');

                    //GRUPO SERVICIO
                    $service['grupoServicios'] = $this->grupoServicioRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["grupoServicios"], "code")
                    ], format: 'selectInfinite');

                    //SERVICIO
                    $service['codServicio'] = $this->servicioRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codServicio"], "code")
                    ], format: 'selectInfinite');

                    //FINALIDAD TECNOLOGIA DE SALUD
                    $service['finalidadTecnologiaSalud'] = $this->ripsFinalidadConsultaVersion2Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["finalidadTecnologiaSalud"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO PRINCIPAL
                    $service['codDiagnosticoPrincipal'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoPrincipal"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO RELACIONADO
                    $service['codDiagnosticoRelacionado'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoRelacionado"], "code")
                    ], format: 'selectInfinite');

                    //CODIGO COMPLICACION
                    $service['codComplicacion'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codComplicacion"], "code")
                    ], format: 'selectInfinite');

                    //CONCEPTO RECAUDO
                    $service['conceptoRecaudo'] = $this->conceptoRecaudoRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["conceptoRecaudo"], "code")
                    ], format: 'selectInfinite');

                    return $service;
                });

                //MAPEO SERVICIOS DE URGENCIAS
                $urgencias = collect($user["servicios"]["urgencias"])->map(function ($service) {

                    //CAUSA MOTIVO DE ATENCION
                    $service['causaMotivoAtencion'] = $this->ripsCausaExternaVersion2Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["causaMotivoAtencion"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO PRINCIPAL
                    $service['codDiagnosticoPrincipal'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoPrincipal"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO PRINCIPAL E
                    $service['codDiagnosticoPrincipalE'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoPrincipalE"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO RELACIONADO E1
                    $service['codDiagnosticoRelacionadoE1'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoRelacionadoE1"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO RELACIONADO E2
                    $service['codDiagnosticoRelacionadoE2'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoRelacionadoE2"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO RELACIONADO E3
                    $service['codDiagnosticoRelacionadoE3'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoRelacionadoE3"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO CAUSA DE MUERTE
                    $service['codDiagnosticoCausaMuerte'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoCausaMuerte"], "code")
                    ], format: 'selectInfinite');

                    return $service;
                });
                //MAPEO SERVICIOS DE HOSPITALIZACION
                $hospitalizacion = collect($user["servicios"]["hospitalizacion"])->map(function ($service) {

                    //VIA INGRESO SERVICIO SALUD
                    $service['viaIngresoServicioSalud'] = $this->viaIngresoUsuarioRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["viaIngresoServicioSalud"], "code")
                    ], format: 'selectInfinite');

                    //CAUSA MOTIVO DE ATENCION
                    $service['causaMotivoAtencion'] = $this->ripsCausaExternaVersion2Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["causaMotivoAtencion"], "code")
                    ], format: 'selectInfinite');


                    //DIAGNOSTICO PRINCIPAL
                    $service['codDiagnosticoPrincipal'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoPrincipal"], "code")
                    ], format: 'selectInfinite');


                    //DIAGNOSTICO PRINCIPAL E
                    $service['codDiagnosticoPrincipalE'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoPrincipalE"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO PRINCIPAL E1
                    $service['codDiagnosticoRelacionadoE1'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoRelacionadoE1"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO PRINCIPAL E2
                    $service['codDiagnosticoRelacionadoE2'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoRelacionadoE2"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO PRINCIPAL E3
                    $service['codDiagnosticoRelacionadoE3'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoRelacionadoE3"], "code")
                    ], format: 'selectInfinite');

                    //CODIGO COMPLICACION
                    $service['codComplicacion'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codComplicacion"], "code")
                    ], format: 'selectInfinite');

                    //CONDICION DESTINO USUARIO EGRESO
                    $service['condicionDestinoUsuarioEgreso'] = $this->condicionyDestinoUsuarioEgresoRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["condicionDestinoUsuarioEgreso"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO CAUSA DE MUERTE
                    $service['codDiagnosticoMuerte'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoMuerte"], "code")
                    ], format: 'selectInfinite');


                    return $service;
                });
                //MAPEO SERVICIOS DE RECIEN NACIDOS
                $recienNacidos = collect($user["servicios"]["recienNacidos"])->map(function ($service) {

                    //SEXO
                    $service['codSexoBiologico'] = $this->sexoRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codSexoBiologico"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO PRINCIPAL
                    $service['codDiagnosticoPrincipal'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoPrincipal"], "code")
                    ], format: 'selectInfinite');

                    //CONDICION DESTINO USUARIO EGRESO
                    $service['condicionDestinoUsuarioEgreso'] = $this->condicionyDestinoUsuarioEgresoRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["condicionDestinoUsuarioEgreso"], "code")
                    ], format: 'selectInfinite');

                    //DIAGNOSTICO CAUSA DE MUERTE
                    $service['codDiagnosticoCausaMuerte'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoCausaMuerte"], "code")
                    ], format: 'selectInfinite');

                    return $service;
                });
                //MAPEO SERVICIOS DE MEDICAMENTOS
                $medicamentos = collect($user["servicios"]["medicamentos"])->map(function ($service) {

                    //DIAGNOSTICO PRINCIPAL
                    $service['codDiagnosticoPrincipal'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoPrincipal"], "code")
                    ], format: 'selectInfinite');


                    //DIAGNOSTICO RELACIONADO
                    $service['codDiagnosticoRelacionado'] = $this->cie10Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["codDiagnosticoRelacionado"], "code")
                    ], format: 'selectInfinite');

                    //TIPO MEDICAMENTO
                    $service['tipoMedicamento'] = $this->tipoMedicamentoPosVersion2Repository->searchOne([
                        "codigo" => getValueSelectInfinite($service["tipoMedicamento"], "code")
                    ], format: 'selectInfinite');

                    //UNIDAD DE MEDIDA
                    $service['unidadMedida'] = $this->ummRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["unidadMedida"], "code")
                    ], format: 'selectInfinite');

                    //CONCEPTO RECAUDO
                    $service['conceptoRecaudo'] = $this->conceptoRecaudoRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["conceptoRecaudo"], "code")
                    ], format: 'selectInfinite');

                    return $service;
                });

                //MAPEO SERVICIOS DE OTROS SERVICIOS
                $otrosServicios = collect($user["servicios"]["otrosServicios"])->map(function ($service) {

                    //TIPO OTROS SERVICIOS
                    $service['tipoOS'] = $this->tipoOtrosServiciosRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["tipoOS"], "code")
                    ], format: 'selectInfinite');

                    //CONCEPTO RECAUDO
                    $service['conceptoRecaudo'] = $this->conceptoRecaudoRepository->searchOne([
                        "codigo" => getValueSelectInfinite($service["conceptoRecaudo"], "code")
                    ], format: 'selectInfinite');

                    return $service;
                });

                $user["servicios"]["consultas"] = $consultas;
                $user["servicios"]["procedimientos"] = $procedimientos;
                $user["servicios"]["urgencias"] = $urgencias;
                $user["servicios"]["hospitalizacion"] = $hospitalizacion;
                $user["servicios"]["recienNacidos"] = $recienNacidos;
                $user["servicios"]["medicamentos"] = $medicamentos;
                $user["servicios"]["otrosServicios"] = $otrosServicios;

                return $user;
            });

            return [
                'code' => '200',
                'json' => $json,
            ];
        });
    }
}

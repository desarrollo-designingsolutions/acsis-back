<?php

namespace App\Http\Controllers;

use App\Enums\Invoice\TypeInvoiceEnum;
use App\Exports\EntityExcelExport;
use App\Exports\InvoiceExcelExport;
use App\Helpers\Constants;
use App\Http\Requests\Invoice\InvoiceType001StoreRequest;
use App\Http\Requests\Invoice\InvoiceType002StoreRequest;
use App\Http\Resources\Invoice\InvoiceListResource;
use App\Http\Resources\Invoice\InvoiceType001FormResource;
use App\Http\Resources\Invoice\InvoiceType002FormResource;
use App\Http\Resources\InvoiceSoat\InvoiceSoatFormResource;
use App\Repositories\InvoiceRepository;
use App\Repositories\InvoiceSoatRepository;
use App\Repositories\MunicipioRepository;
use App\Repositories\PaisRepository;
use App\Repositories\PatientRepository;
use App\Repositories\RipsTipoUsuarioVersion2Repository;
use App\Repositories\ServiceVendorRepository;
use App\Repositories\SexoRepository;
use App\Repositories\TipoDocumentoRepository;
use App\Repositories\TipoIdPisisRepository;
use App\Repositories\TipoNotaRepository;
use App\Repositories\TipoUsuarioRepository;
use App\Traits\HttpResponseTrait;
use App\Repositories\TypeEntityRepository;
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
        protected SexoRepository $sexoRepository,
        protected RipsTipoUsuarioVersion2Repository $ripsTipoUsuarioVersion2Repository,
        protected PaisRepository $paisRepository,
        protected MunicipioRepository $municipioRepository,
        protected ZonaVersion2Repository $zonaVersion2Repository,
        protected TipoIdPisisRepository $tipoIdPisisRepository,
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
            $jsonData = $this->buildInvoiceJson($post, $invoice);

            // Store JSON file
            $this->storeJsonFile($post, $invoice, $jsonData);

            //////////////////////////////////////////////////

            // $service_vendor = $this->serviceVendorRepository->find($post['service_vendor_id'], select: ['id', 'nit']);
            // $tipo_nota = $this->tipoNotaRepository->find($post['tipo_nota_id'], select: ['id', 'codigo']);
            // $patient = $invoice->patient;

            // $data = [
            //     "numDocumentoIdObligado" => $service_vendor->nit,
            //     "numFactura" => $post["invoice_number"],
            //     "TipoNota" => $tipo_nota->codigo,
            //     "numNota" => $post["note_number"],
            // ];

            // $info = convertNullToEmptyString([
            //     "numDocumentoIdObligado" => $data["numDocumentoIdObligado"],
            //     "numFactura" => $data["numFactura"],
            //     "TipoNota" => $data["TipoNota"],
            //     "numNota" => $data["numNota"],
            // ]);

            // $users = [];

            // $users[] = [
            //     "codSexo" => "",
            //     "consecutivo" => 1,
            //     "incapacidad" => $patient->incapacity,
            //     "tipoUsuario" => "",
            //     "codPaisOrigen" => "",
            //     "fechaNacimiento" => $patient->birth_date,
            //     "codPaisResidencia" => "",
            //     "codMunicipioResidencia" => "",
            //     "numDocumentoIdentificacion" => $patient->document,
            //     "tipoDocumentoIdentificacion" => "",
            //     "codZonaTerritorialResidencia" => "",
            //     "servicios" => [
            //         'consultas' => [],
            //         'procedimientos' => [],
            //         'medicamentos' => [],
            //         'urgencias' => [],
            //         'otrosServicios' => [],
            //         'hospitalizacion' => [],
            //         'recienNacidos' => []
            //     ]
            // ];

            // $info["usuarios"] = [$users];

            // // Define file name and path for JSON file
            // $nameFile = $post["invoice_number"] . '.json';
            // $ruta = 'companies/company_' . $post["company_id"] . '/invoices/invoice_' . $invoice->id . '/' . $nameFile;

            // // Store JSON contents to the file system
            // Storage::disk('public')->put($ruta, json_encode($info)); //guardo el archivo

            return [
                'code' => 200,
                'message' => 'Factura agregada correctamente',
            ];
        }, debug: false);
    }
    /**
     * Build the JSON structure for the invoice
     */
    private function buildInvoiceJson(array $post, $invoice): array
    {
        $serviceVendor = $this->serviceVendorRepository->find($post['service_vendor_id'], select: ['id', 'nit']);
        $tipoNota = $this->tipoNotaRepository->find($post['tipo_nota_id'], select: ['id', 'codigo']);
        $sexo = $this->sexoRepository->find($post['sexo_id'], select: ['id', 'codigo']);
        $tipoUsuario = $this->ripsTipoUsuarioVersion2Repository->find($post['tipo_usuario_id'], select: ['id', 'codigo']);
        $pais_residency = $this->paisRepository->find($post['pais_residency_id'], select: ['id', 'codigo']);
        $pais_origin = $this->paisRepository->find($post['pais_origin_id'], select: ['id', 'codigo']);
        $municipio = $this->municipioRepository->find($post['municipio_residency_id'], select: ['id', 'codigo']);
        $zonaVersion2 = $this->zonaVersion2Repository->find($post['residency_zone_id'], select: ['id', 'codigo']);
        $tipoIdPisis = $this->tipoIdPisisRepository->find($post['pais_origin_id'], select: ['id', 'codigo']);
        $patient = $invoice->patient;

        // Base invoice data
        $baseData = [
            'numDocumentoIdObligado' => $serviceVendor->nit,
            'numFactura' => $post['invoice_number'],
            'TipoNota' => $tipoNota->codigo,
            'numNota' => $post['note_number'],
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
                'tipoDocumentoIdentificacion' => "",
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
     * Store or update the JSON file
     */
    private function storeJsonFile(array $post, $invoice, array $jsonData): void
    {
        $nameFile = $post['invoice_number'] . '.json';
        $path = "companies/company_{$post['company_id']}/invoices/invoice_{$invoice->id}/{$nameFile}";

        // Check if file exists
        if (Storage::disk(Constants::DISK_FILES)->exists($path)) {
            // Read existing JSON and merge with new data
            $existingData = json_decode(Storage::disk(Constants::DISK_FILES)->get($path), true);
            $mergedData = array_merge($existingData, $jsonData);
        } else {
            $mergedData = $jsonData;
        }

        // Store JSON contents
        Storage::disk(Constants::DISK_FILES)->put($path, json_encode($mergedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
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

    public function validateLicensePlate(Request $request)
    {
        return $this->execute(function () use ($request) {

            $request->validate([
                'invoice_number' => 'required|string',
            ]);

            $exists = $this->invoiceRepository->validateLicensePlate($request->all());

            return [
                'message_licences' => 'El número de factura ya existe.',
                'exists' => $exists,
            ];
        });
    }
}

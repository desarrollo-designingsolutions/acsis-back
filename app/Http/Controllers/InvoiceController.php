<?php

namespace App\Http\Controllers;

use App\Enums\Assignment\TypeInvoiceEnum;
use App\Exports\EntityExcelExport;
use App\Http\Requests\Entity\EntityStoreRequest;
use App\Http\Requests\Invoice\InvoiceStoreRequest;
use App\Http\Resources\Entity\EntityFormResource;
use App\Http\Resources\Invoice\InvoiceListResource;
use App\Http\Resources\TypeEntity\TypeEntitySelectResource;
use App\Repositories\InvoiceRepository;
use App\Traits\HttpResponseTrait;
use App\Repositories\TypeEntityRepository;
use App\Services\CacheService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            return [
                'code' => 200,
            ];
        });
    }

    public function store(InvoiceStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except(['entity', 'serviceVendor']);

            $invoice = $this->invoiceRepository->store($post);

            $this->cacheService->clearByPrefix($this->key_redis_project . 'string:invoices*');

            return [
                'code' => 200,
                'message' => 'Factura agregada correctamente',
            ];
        });
    }

    public function edit($id)
    {
        return $this->execute(function () use ($id) {
            $entity = $this->invoiceRepository->find($id);
            $form = new EntityFormResource($entity);

            $typeEntities = $this->typeEntityRepository->list(['typeData' => 'all']);
            $dataTypeEntities = TypeEntitySelectResource::collection($typeEntities);

            return [
                'code' => 200,
                'form' => $form,
                'typeEntities' => $dataTypeEntities,
            ];
        });
    }

    public function update(EntityStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request, $id) {

            $entity = $this->invoiceRepository->store($request->all(), $id);

            $this->cacheService->clearByPrefix($this->key_redis_project . 'string:entities*');

            return [
                'code' => 200,
                'message' => 'Entidad modificada correctamente',
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

                $this->cacheService->clearByPrefix($this->key_redis_project . 'string:invoices*');

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
                'message' => 'Entidad ' . $msg . ' con éxito',
            ];
        });
    }

    public function excelExport(Request $request)
    {
        return $this->execute(function () use ($request) {

            $request['typeData'] = 'all';

            $entities = $this->invoiceRepository->paginate($request->all());

            $excel = Excel::raw(new EntityExcelExport($entities), \Maatwebsite\Excel\Excel::XLSX);

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
                    'value' => $case->value,
                    'description' => $case->description(),
                ];
            }, TypeInvoiceEnum::cases());

            return [
                'code' => 200,
                'TypeInvoiceEnumValues' => $TypeInvoiceEnumValues,
            ];
        });
    }
}

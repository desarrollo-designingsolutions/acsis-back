<?php

namespace App\Http\Controllers;

use App\Enums\Invoice\TypeInvoiceEnum;
use App\Exports\EntityExcelExport;
use App\Exports\InvoiceExcelExport;
use App\Http\Requests\Invoice\InvoiceType001StoreRequest;
use App\Http\Requests\Invoice\InvoiceType002StoreRequest;
use App\Http\Resources\Invoice\InvoiceListResource;
use App\Http\Resources\Invoice\InvoiceType001FormResource;
use App\Http\Resources\Invoice\InvoiceType002FormResource;
use App\Http\Resources\InvoiceSoat\InvoiceSoatFormResource;
use App\Repositories\InvoiceRepository;
use App\Repositories\InvoiceSoatRepository;
use App\Repositories\PatientRepository;
use App\Traits\HttpResponseTrait;
use App\Repositories\TypeEntityRepository;
use App\Services\CacheService;
use Illuminate\Http\Request;
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

            $post = $request->except(['entity', 'serviceVendor', 'typeDocument', 'type_document_id', 'document', 'first_name', 'first_surname', 'second_name', 'second_surname']);

            if (empty($request['patient_id'])) {

                $post2 = $request->only(['company_id', 'type_document_id', 'document', 'first_name', 'first_surname', 'second_name', 'second_surname']);

                $patient = $this->patientRepository->store($post2);

                $post['patient_id'] = $patient->id;
            }

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

            $post = $request->except(['entity', 'serviceVendor', 'typeDocument', 'type_document_id', 'document', 'first_name', 'first_surname', 'second_name', 'second_surname']);

            if (empty($request['patient_id'])) {

                $post2 = $request->only(['company_id', 'type_document_id', 'document', 'first_name', 'first_surname', 'second_name', 'second_surname']);

                $patient = $this->patientRepository->store($post2);

                $post['patient_id'] = $patient->id;
            }

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

            $post = $request->except(['entity', 'serviceVendor', 'typeDocument', 'type_document_id', 'document', 'first_name', 'first_surname', 'second_name', 'second_surname', 'soat']);

            if (empty($request['patient_id'])) {

                $post2 = $request->only(['company_id', 'type_document_id', 'document', 'first_name', 'first_surname', 'second_name', 'second_surname']);

                $patient = $this->patientRepository->store($post2);

                $post['patient_id'] = $patient->id;
            }

            $dataSoat = $request->input('soat');

            $dataSoat['company_id'] = $request->input('company_id');

            $soat = $this->invoiceSoatRepository->store($dataSoat);

            $post['typeable_type'] = 'App\\Models\\' . $post['type'];

            $post['typeable_id'] = $soat['id'];

            $post['remaining_balance'] = $post['total'];

            $invoice = $this->invoiceRepository->store($post);

            return [
                'code' => 200,
                'message' => 'Factura agregada correctamente',
            ];
        });
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

            $post = $request->except(['entity', 'serviceVendor', 'typeDocument', 'type_document_id', 'document', 'first_name', 'first_surname', 'second_name', 'second_surname', 'soat']);

            if (empty($request['patient_id'])) {

                $post2 = $request->only(['company_id', 'type_document_id', 'document', 'first_name', 'first_surname', 'second_name', 'second_surname']);

                $patient = $this->patientRepository->store($post2);

                $post['patient_id'] = $patient->id;
            }

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
}

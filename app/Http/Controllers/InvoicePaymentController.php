<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Http\Requests\InvoicePayment\InvoicePaymentMasiveStoreRequest;
use App\Http\Requests\InvoicePayment\InvoicePaymentStoreRequest;
use App\Http\Resources\InvoicePayment\InvoicePaymentFormResource;
use App\Http\Resources\InvoicePayment\InvoicePaymentPaginateResource;
use App\Repositories\InvoicePaymentRepository;
use App\Services\CacheService;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoicePaymentController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected InvoicePaymentRepository $InvoicePaymentRepository,
        protected CacheService $cacheService,
    ) {}

    public function paginate(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->InvoicePaymentRepository->paginate($request->all());
            $tableData = InvoicePaymentPaginateResource::collection($data);

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

    public function store(InvoicePaymentStoreRequest $request)
    {

        return $this->runTransaction(function () use ($request) {
            $post = $request->except(["file"]);
            $InvoicePayment = $this->InvoicePaymentRepository->store($post);

            if ($request->file('file')) {
                $file = $request->file('file');
                $ruta = 'companies/company_' . $InvoicePayment->company_id . '/InvoicePayments/InvoicePayment_' . $InvoicePayment->id . $request->input('file');

                $file = $file->store($ruta, Constants::DISK_FILES);
                $InvoicePayment->file = $file;
                $InvoicePayment->save();
            }

            return [
                'code' => 200,
                'message' => 'InvoicePayment agregada correctamente',
            ];
        });
    }

    public function edit($id)
    {
        return $this->execute(function () use ($id) {

            $InvoicePayment = $this->InvoicePaymentRepository->find($id);
            $form = new InvoicePaymentFormResource($InvoicePayment);

            return [
                'code' => 200,
                'form' => $form,
            ];
        });
    }

    public function update(InvoicePaymentStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except(["file"]);
            $InvoicePayment = $this->InvoicePaymentRepository->store($post);

            if ($request->file('file')) {
                $file = $request->file('file');
                $ruta = 'companies/company_' . $InvoicePayment->company_id . '/InvoicePayments/InvoicePayment_' . $InvoicePayment->id . $request->input('file');

                $file = $file->store($ruta, Constants::DISK_FILES);
                $InvoicePayment->file = $file;
                $InvoicePayment->save();
            }

            return [
                'code' => 200,
                'message' => 'InvoicePayment modificada correctamente',
            ];
        });
    }

    public function delete($id)
    {
        return $this->runTransaction(function () use ($id) {
            $InvoicePayment = $this->InvoicePaymentRepository->find($id);
            if ($InvoicePayment) {

                $InvoicePayment->delete();

                $msg = 'Registro eliminado correctamente';
            } else {
                $msg = 'El registro no existe';
            }

            DB::commit();

            return [
                'code' => 200,
                'message' => $msg,
            ];
        }, 200);
    }


    public function createMasive()
    {
        return $this->execute(function () {

            return [
                'code' => 200,
            ];
        });
    }
}

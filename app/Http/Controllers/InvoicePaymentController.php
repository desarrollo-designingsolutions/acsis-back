<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Http\Requests\InvoicePayment\InvoicePaymentStoreRequest;
use App\Http\Resources\InvoicePayment\InvoicePaymentFormResource;
use App\Http\Resources\InvoicePayment\InvoicePaymentPaginateResource;
use App\Repositories\InvoicePaymentRepository;
use App\Repositories\InvoiceRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;

class InvoicePaymentController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected InvoiceRepository $invoiceRepository,
        protected InvoicePaymentRepository $invoicePaymentRepository,
    ) {}

    public function paginate(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->invoicePaymentRepository->paginate($request->all());
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

    public function create($invoice_id)
    {
        return $this->execute(function () use ($invoice_id) {

            $invoice = $this->invoiceRepository->find($invoice_id, select: ['id', 'remaining_balance']);

            return [
                'code' => 200,
                'invoice' => $invoice,
            ];
        });
    }

    public function store(InvoicePaymentStoreRequest $request)
    {

        return $this->runTransaction(function () use ($request) {

            $post = $request->except(['file']);
            $invoicePayment = $this->invoicePaymentRepository->store($post);

            if ($request->file('file')) {
                $file = $request->file('file');
                $ruta = 'companies/company_'.$invoicePayment->company_id.'/InvoicePayments/InvoicePayment_'.$invoicePayment->id.$request->input('file');

                $file = $file->store($ruta, Constants::DISK_FILES);
                $invoicePayment->file = $file;
                $invoicePayment->save();
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

            $invoicePayment = $this->invoicePaymentRepository->find($id);
            $form = new InvoicePaymentFormResource($invoicePayment);

            $invoice = $this->invoiceRepository->find($invoicePayment->invoice_id, select: ['id', 'remaining_balance']);

            return [
                'code' => 200,
                'form' => $form,
                'invoice' => $invoice,
            ];
        });
    }

    public function update(InvoicePaymentStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request, $id) {

            $invoicePayment_old = $this->invoicePaymentRepository->find($id, select: ['id', 'value_paid']);

            $post = $request->except(['file']);
            $invoicePayment = $this->invoicePaymentRepository->store($post);

            if ($request->file('file')) {
                $file = $request->file('file');
                $ruta = 'companies/company_'.$invoicePayment->company_id.'/InvoicePayments/InvoicePayment_'.$invoicePayment->id.$request->input('file');

                $file = $file->store($ruta, Constants::DISK_FILES);
                $invoicePayment->file = $file;
                $invoicePayment->save();
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
            $invoicePayment = $this->invoicePaymentRepository->find($id);
            if ($invoicePayment) {

                $invoicePayment->delete();

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
}

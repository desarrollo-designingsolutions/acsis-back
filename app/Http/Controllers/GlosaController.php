<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Http\Requests\Glosa\GlosaMasiveStoreRequest;
use App\Http\Requests\Glosa\GlosaStoreRequest;
use App\Http\Resources\Glosa\GlosaFormResource;
use App\Http\Resources\Glosa\GlosaPaginateResource;
use App\Repositories\CodeGlosaRepository;
use App\Repositories\GlosaRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\ServiceRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class GlosaController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected CodeGlosaRepository $codeGlosaRepository,
        protected GlosaRepository $glosaRepository,
        protected ServiceRepository $serviceRepository,
        protected QueryController $queryController,
        protected InvoiceRepository $invoiceRepository,
    ) {}

    public function paginate(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->glosaRepository->paginate($request->all());
            $tableData = GlosaPaginateResource::collection($data);

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

    public function create(Request $request)
    {
        return $this->execute(function () use ($request) {

            $filter = new Request([
                'type_code_glosa_id' => 1,
            ]);
            $codeGlosa1 = $this->queryController->selectInfiniteCodeGlosa($filter);
            $filter = new Request([
                'type_code_glosa_id' => 2,
            ]);
            $codeGlosa2 = $this->queryController->selectInfiniteCodeGlosa($filter);

            $typeCodeGlosa = $this->queryController->selectInfiniteTypeCodeGlosa(request());

            $codeGlosa = [
                'codeGlosa1' => $codeGlosa1,
                'codeGlosa2' => $codeGlosa2,
            ];

            $invoice = $this->invoiceRepository->find($request->input('invoice_id'));

            $radication_date = null;
            if ($invoice->radication_date) {
                $radication_date = Carbon::parse($invoice->radication_date)->format('Y-m-d H:i');
            }

            return [
                'code' => 200,
                ...$codeGlosa,
                ...$typeCodeGlosa,
                'radication_date' => $radication_date,
            ];
        });
    }

    public function store(GlosaStoreRequest $request)
    {

        return $this->runTransaction(function () use ($request) {
            $post = $request->except(['file']);
            $glosa = $this->glosaRepository->store($post);

            if ($request->file('file')) {
                $file = $request->file('file');
                $ruta = 'companies/company_' . $glosa->company_id . '/glosas/glosa_' . $glosa->id . $request->input('file');

                $file = $file->store($ruta, Constants::DISK_FILES);
                $glosa->file = $file;
                $glosa->save();
            }

            return [
                'code' => 200,
                'message' => 'Glosa agregada correctamente',
            ];
        });
    }

    public function edit(Request $request, $id)
    {
        return $this->execute(function () use ($request, $id) {

            $glosa = $this->glosaRepository->find($id);
            $form = new GlosaFormResource($glosa);

            $filter = new Request([
                'type_code_glosa_id' => 1,
            ]);
            $codeGlosa1 = $this->queryController->selectInfiniteCodeGlosa($filter);
            $filter = new Request([
                'type_code_glosa_id' => 2,
            ]);
            $codeGlosa2 = $this->queryController->selectInfiniteCodeGlosa($filter);

            $codeGlosa = [
                'codeGlosa1' => $codeGlosa1,
                'codeGlosa2' => $codeGlosa2,
            ];

            $typeCodeGlosa = $this->queryController->selectInfiniteTypeCodeGlosa(request());

            $invoice = $this->invoiceRepository->find($request->input('invoice_id'));

            $radication_date = null;
            if ($invoice->radication_date) {
                $radication_date = Carbon::parse($invoice->radication_date)->format('Y-m-d H:i');
            }

            return [
                'code' => 200,
                'form' => $form,
                'type_code_glosa_id' => $glosa->code_glosa->generalCodeGlosa->type_code_glosa_id,
                ...$codeGlosa,
                ...$typeCodeGlosa,
                'radication_date' => $radication_date,
            ];
        });
    }

    public function show($id)
    {
        return $this->execute(function () use ($id) {

            $glosa = $this->glosaRepository->find($id);
            $form = new GlosaFormResource($glosa);

            return [
                'code' => 200,
                'form' => $form,
            ];
        });
    }

    public function update(GlosaStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except(['file']);
            $glosa = $this->glosaRepository->store($post);

            if ($request->file('file')) {
                $file = $request->file('file');
                $ruta = 'companies/company_' . $glosa->company_id . '/glosas/glosa_' . $glosa->id . $request->input('file');

                $file = $file->store($ruta, Constants::DISK_FILES);
                $glosa->file = $file;
                $glosa->save();
            }

            return [
                'code' => 200,
                'message' => 'Glosa modificada correctamente',
            ];
        });
    }

    public function delete($id)
    {
        return $this->runTransaction(function () use ($id) {
            $glosa = $this->glosaRepository->find($id);
            if ($glosa) {

                $glosa->delete();

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

    public function createMasive()
    {
        return $this->execute(function () {

            return [
                'code' => 200,
            ];
        });
    }

    public function storeMasive(GlosaMasiveStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            $servicesIDs = $request->input('servicesIds');
            $glosas = $request->input('glosas');
            $company_id = $request->input('company_id');

            foreach ($servicesIDs as $key => $serviceId) {
                $service = $this->serviceRepository->find($serviceId);

                foreach ($glosas as $key => $value) {
                    $data = [
                        'user_id' => $value['user_id'],
                        'company_id' => $company_id,
                        'service_id' => $service->id,
                        'code_glosa_id' => $value['code_glosa_id'],
                        'glosa_value' => $value['partialValue'] * $service->total_value / 100,
                        'observation' => $value['observation'],
                        'date' => $value['date'],
                    ];

                    $glosa = $this->glosaRepository->store($data);

                    if ($request->file('file_file' . $key)) {
                        $file = $request->file('file_file' . $key);
                        $ruta = 'companies/company_' . $glosa->company_id . '/glosas/glosa_' . $glosa->id . $request->input('file_file' . $key);

                        $file = $file->store($ruta, Constants::DISK_FILES);
                        $glosa->file = $file;
                        $glosa->save();
                    }
                }
            }

            return [
                'code' => 200,
                'message' => 'Glosa/s agregada/s correctamente',
            ];
        }, debug: false);
    }
}

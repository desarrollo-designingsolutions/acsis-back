<?php

namespace App\Http\Controllers;

use App\Exports\ServiceVendorExcelExport;
use App\Http\Requests\ServiceVendor\ServiceVendorStoreRequest;
use App\Http\Resources\ServiceVendor\ServiceVendorFormResource;
use App\Http\Resources\ServiceVendor\ServiceVendorPaginateResource;
use App\Repositories\ServiceVendorRepository;
use App\Repositories\TypeVendorRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ServiceVendorController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected ServiceVendorRepository $serviceVendorRepository,
        protected TypeVendorRepository $typeVendorRepository,
        protected QueryController $queryController,
    ) {}

    public function paginate(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->serviceVendorRepository->paginate($request->all());
            $tableData = ServiceVendorPaginateResource::collection($data);

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

            $request['is_active'] = true;
            $type_vendors = $this->typeVendorRepository->selectList($request->all());

            $ipsCodHabilitacion = $this->queryController->selectInfiniteIpsCodHabilitacion($request);
            $ipsNoReps = $this->queryController->selectInfiniteIpsNoReps($request);

            $ipsables = [
                [
                    'value' => "App\Models\IpsCodHabilitacion",
                    'label' => 'IPS con código de habilitación',
                    'url' => '/selectInfiniteIpsCodHabilitacion',
                    'arrayInfo' => 'ipsCodHabilitacion',
                    'itemsData' => $ipsCodHabilitacion['ipsCodHabilitacion_arrayInfo'],
                ],
                [
                    'value' => "App\Models\IpsNoReps",
                    'label' => 'IPS No REPS',
                    'url' => '/selectInfiniteIpsNoReps',
                    'arrayInfo' => 'ipsNoReps',
                    'itemsData' => $ipsNoReps['ipsNoReps_arrayInfo'],
                ],
            ];

            return [
                'code' => 200,
                'type_vendors' => $type_vendors,
                'ipsables' => $ipsables,
            ];
        });
    }

    public function store(ServiceVendorStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except([]);
            $serviceVendor = $this->serviceVendorRepository->store($post);

            return [
                'code' => 200,
                'message' => 'Proveedor agregado correctamente',
                'form' => $serviceVendor,
            ];
        });
    }

    public function edit(Request $request, $id)
    {
        return $this->execute(function () use ($id, $request) {

            $request['is_active'] = true;
            $type_vendors = $this->typeVendorRepository->selectList($request->all());

            $serviceVendor = $this->serviceVendorRepository->find($id);
            $form = new ServiceVendorFormResource($serviceVendor);

            $ipsCodHabilitacion = $this->queryController->selectInfiniteIpsCodHabilitacion($request);
            $ipsNoReps = $this->queryController->selectInfiniteIpsNoReps($request);

            $ipsables = [
                [
                    'value' => "App\Models\IpsCodHabilitacion",
                    'label' => 'IPS con código de habilitación',
                    'url' => '/selectInfiniteIpsCodHabilitacion',
                    'arrayInfo' => 'ipsCodHabilitacion',
                    'itemsData' => $ipsCodHabilitacion['ipsCodHabilitacion_arrayInfo'],
                ],
                [
                    'value' => "App\Models\IpsNoReps",
                    'label' => 'IPS No REPS',
                    'url' => '/selectInfiniteIpsNoReps',
                    'arrayInfo' => 'ipsNoReps',
                    'itemsData' => $ipsNoReps['ipsNoReps_arrayInfo'],
                ],
            ];

            return [
                'code' => 200,
                'form' => $form,
                'type_vendors' => $type_vendors,
                'ipsables' => $ipsables,
                ...$ipsCodHabilitacion,
                ...$ipsNoReps,
            ];
        });
    }

    public function update(ServiceVendorStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request, $id) {

            $post = $request->except([]);
            $serviceVendor = $this->serviceVendorRepository->store($post, $id);

            return [
                'code' => 200,
                'message' => 'Proveedor modificado correctamente',
                'form' => $serviceVendor,
            ];
        });
    }

    public function delete($id)
    {
        return $this->runTransaction(function () use ($id) {
            $serviceVendor = $this->serviceVendorRepository->find($id);
            if ($serviceVendor) {
                // Verificar si hay registros relacionados
                if ($serviceVendor->invoices()->exists()) {
                    throw new \Exception(json_encode([
                        'message' => 'No se puede eliminar el registro, porque tiene relación de datos en otros módulos',
                    ]));
                }

                $serviceVendor->delete();
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
            $model = $this->serviceVendorRepository->changeState($request->input('id'), strval($request->input('value')), $request->input('field'));

            ($model->is_active == 1) ? $msg = 'habilitada' : $msg = 'inhabilitada';

            return [
                'code' => 200,
                'message' => 'Proveedor ' . $msg . ' con éxito',
            ];
        });
    }

    public function excelExport(Request $request)
    {
        return $this->execute(function () use ($request) {
            $request['typeData'] = 'all';

            $data = $this->serviceVendorRepository->paginate($request->all());

            $excel = Excel::raw(new ServiceVendorExcelExport($data), \Maatwebsite\Excel\Excel::XLSX);

            $excelBase64 = base64_encode($excel);

            return [
                'code' => 200,
                'excel' => $excelBase64,
            ];
        });
    }
}

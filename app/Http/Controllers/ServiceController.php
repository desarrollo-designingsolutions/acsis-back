<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\ServiceStoreRequest;
use App\Http\Resources\Service\ServiceFormResource;
use App\Http\Resources\Service\ServicePaginateResource;
use App\Repositories\ServiceRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected ServiceRepository $serviceRepository,
    ) {}

    public function paginate(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->serviceRepository->paginate($request->all());
            $tableData = ServicePaginateResource::collection($data);

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

            return [
                'code' => 200,
            ];
        });
    }

    public function store(ServiceStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except([]);

            $post["total_value"] = $post["quantity"] * $post["unit_value"];

            $service = $this->serviceRepository->store($post);

            return [
                'code' => 200,
                'message' => 'Servicio agregado correctamente',
            ];
        });
    }

    public function edit(Request $request, $id)
    {
        return $this->execute(function () use ($id, $request) {

            $service = $this->serviceRepository->find($id);
            $form = new ServiceFormResource($service);

            return [
                'code' => 200,
                'form' => $form,
            ];
        });
    }

    public function update(ServiceStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request, $id) {

            $post = $request->except([]);
            $post["total_value"] = $post["quantity"] * $post["unit_value"];

            $service = $this->serviceRepository->store($post, $id);

            return [
                'code' => 200,
                'message' => 'Servicio modificado correctamente',
            ];
        });
    }

    public function delete($id)
    {
        return $this->runTransaction(function () use ($id) {
            $service = $this->serviceRepository->find($id);
            if ($service) {
                // Verificar si hay registros relacionados
                // if ($service->users()->exists()) {
                //     throw new \Exception(json_encode([
                //         'message' => 'No se puede eliminar la compañía, porque tiene relación de datos en otros módulos',
                //     ]));
                // }

                $service->delete();
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
            $model = $this->serviceRepository->changeState($request->input('id'), strval($request->input('value')), $request->input('field'));

            ($model->is_active == 1) ? $msg = 'habilitada' : $msg = 'inhabilitada';

            return [
                'code' => 200,
                'message' => 'Servicio ' . $msg . ' con éxito',
            ];
        });
    }
}

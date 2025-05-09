<?php

namespace App\Http\Controllers;

use App\Enums\Service\TypeServiceEnum;
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
                $service->serviceable()->delete();
                $service->glosas()->delete();
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


    public function loadBtnCreate(Request $request)
    {
        return $this->execute(function () use ($request) {

            $typeServiceEnumValues = array_map(function ($case) {
                return [
                    'type' => $case->value,
                    'name' => $case->description(),
                ];
            }, TypeServiceEnum::cases());

            return [
                'code' => 200,
                'typeServiceEnumValues' => $typeServiceEnumValues,
            ];
        });
    }
}

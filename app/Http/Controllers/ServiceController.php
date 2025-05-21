<?php

namespace App\Http\Controllers;

use App\Enums\Service\TypeServiceEnum;
use App\Http\Resources\Service\ServicePaginateResource;
use App\Models\Service;
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

                $service->serviceable()->delete();
                $service->glosas()->delete();
                $service->delete();

                $serviceType = $service->type;
                // Update JSON to remove service and reindex consecutivos
                updateInvoiceServicesJson(
                    $service->invoice_id,
                    $serviceType,
                    [],
                    'delete',
                    $service->consecutivo
                );

                // Reindex consecutivos in the database
                reindexConsecutivos($service->invoice_id, $serviceType);

                $msg = 'Registro eliminado correctamente';
            } else {
                $msg = 'El registro no existe';
            }

            return [
                'code' => 200,
                'message' => $msg,
            ];
        }, 200, debug: false);
    }

    public function loadBtnCreate(Request $request)
    {
        return $this->execute(function () {
            // Define the values to exclude
            $excludedTypes = [
                TypeServiceEnum::SERVICE_TYPE_003->value,
                TypeServiceEnum::SERVICE_TYPE_004->value,
                TypeServiceEnum::SERVICE_TYPE_005->value,
            ];

            // Filter out the excluded types and map the remaining cases
            $typeServiceEnumValues = array_map(function ($case) {
                return [
                    'type' => $case->value,
                    'name' => $case->description(),
                    'icon' => $case->icon(),
                ];
            }, array_filter(TypeServiceEnum::cases(), function ($case) use ($excludedTypes) {
                return ! in_array($case->value, $excludedTypes);
            }));

            return [
                'code' => 200,
                'typeServiceEnumValues' => $typeServiceEnumValues,
            ];
        });
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\EntityExcelExport;
use App\Http\Requests\Entity\EntityStoreRequest;
use App\Http\Resources\Entity\EntityFormResource;
use App\Http\Resources\Entity\EntityListResource;
use App\Http\Resources\TypeEntity\TypeEntitySelectResource;
use App\Repositories\EntityRepository;
use App\Traits\HttpResponseTrait;
use App\Repositories\TypeEntityRepository;
use App\Services\CacheService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EntityController extends Controller
{
    use HttpResponseTrait;

    private $key_redis_project;

    public function __construct(
        protected EntityRepository $entityRepository,
        protected QueryController $queryController,
        protected TypeEntityRepository $typeEntityRepository,
        protected CacheService $cacheService,
    ) {
        $this->key_redis_project = env('KEY_REDIS_PROJECT');
    }

    public function paginate(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->entityRepository->paginate($request->all());
            $tableData = EntityListResource::collection($data);

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
            $typeEntities = $this->typeEntityRepository->list(['typeData' => 'all']);
            $dataTypeEntities = TypeEntitySelectResource::collection($typeEntities);

            return [
                'code' => 200,
                'typeEntities' => $dataTypeEntities,
            ];
        });
    }

    public function store(EntityStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {
            $entity = $this->entityRepository->store($request->all());

            $this->cacheService->clearByPrefix($this->key_redis_project . 'string:entities*');

            return [
                'code' => 200,
                'message' => 'Entidad agregada correctamente',
            ];
        });
    }

    public function edit($id)
    {
        return $this->execute(function () use ($id) {
            $entity = $this->entityRepository->find($id);
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

            $entity = $this->entityRepository->store($request->all(), $id);

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
            $entity = $this->entityRepository->find($id);
            if ($entity) {

                $this->cacheService->clearByPrefix($this->key_redis_project . 'string:entities*');

                $entity->delete();
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
            $model = $this->entityRepository->changeState($request->input('id'), strval($request->input('value')), $request->input('field'));

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

            $entities = $this->entityRepository->paginate($request->all());

            $excel = Excel::raw(new EntityExcelExport($entities), \Maatwebsite\Excel\Excel::XLSX);

            $excelBase64 = base64_encode($excel);

            return [
                'code' => 200,
                'excel' => $excelBase64,
            ];
        });
    }

    public function getNit($id)
    {
        return $this->execute(function () use ($id) {

            $entities = $this->entityRepository->find($id);

            return [
                'code' => 200,
                'nit' => $entities->nit,
            ];
        });
    }
}

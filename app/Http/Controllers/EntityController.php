<?php

namespace App\Http\Controllers;

use App\Exports\EntityExcelExport;
use App\Http\Requests\Entity\EntityStoreRequest;
use App\Http\Resources\Entity\EntityFormResource;
use App\Http\Resources\Entity\EntityListResource;
use App\Http\Resources\TypeEntity\TypeEntitySelectResource;
use App\Repositories\EntityRepository;
use App\Repositories\TypeEntityRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EntityController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected EntityRepository $entityRepository,
        protected QueryController $queryController,
        protected TypeEntityRepository $typeEntityRepository,
    ) {}

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

            return [
                'code' => 200,
                'message' => 'Entidad agregada correctamente',
                'form' => $entity,

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

            return [
                'code' => 200,
                'message' => 'Entidad modificada correctamente',
                'form' => $entity,
            ];
        });
    }

    public function delete($id)
    {
        return $this->runTransaction(function () use ($id) {
            $entity = $this->entityRepository->find($id);
            if ($entity) {
                // Verificar si hay registros relacionados
                if ($entity->invoices()->exists()) {
                    throw new \Exception(json_encode([
                        'message' => 'No se puede eliminar el registro, porque tiene relación de datos en otros módulos',
                    ]));
                }
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

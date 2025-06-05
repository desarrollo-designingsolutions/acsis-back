<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Http\Resources\TypeDocument\TypeDocumentSelectResource;
use App\Models\Pais;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PaisRepository extends BaseRepository
{
    public function __construct(Pais $modelo)
    {
        parent::__construct($modelo);
    }

    public function paginate($request = [])
    {
        $cacheKey = $this->cacheService->generateKey("{$this->model->getTable()}_paginate", $request, 'string');

        return $this->cacheService->remember($cacheKey, function () use ($request) {
            $query = QueryBuilder::for($this->model->query())
                ->select(['id', 'codigo', 'nombre', 'extra_II'])
                ->allowedFilters([
                    AllowedFilter::callback('inputGeneral', function ($query, $value) {
                        $query->where(function ($subQuery) use ($value) {
                            $subQuery->orWhere('codigo', 'like', "%$value%");
                            $subQuery->orWhere('nombre', 'like', "%$value%");
                        });
                    }),
                ])
                ->allowedSorts([])
                ->where(function ($query) use ($request) {
                    if (isset($request['searchQueryInfinite']) && ! empty($request['searchQueryInfinite'])) {
                        $query->orWhere('codigo', 'like', '%'.$request['searchQueryInfinite'].'%');
                        $query->orWhere('nombre', 'like', '%'.$request['searchQueryInfinite'].'%');
                    }
                });

            if (empty($request['typeData'])) {
                $query = $query->paginate(request()->perPage ?? Constants::ITEMS_PER_PAGE);
            } else {
                $query = $query->get();
            }

            return $query;
        }, Constants::REDIS_TTL);
    }

    public function store(array $request, $id = null)
    {
        $request = $this->clearNull($request);

        // Determinar el ID a utilizar para buscar o crear el modelo
        $idToUse = ($id === null || $id === 'null') && ! empty($request['id']) && $request['id'] !== 'null' ? $request['id'] : $id;

        if (! empty($idToUse)) {
            $data = $this->model->find($idToUse);
        } else {
            $data = $this->model::newModelInstance();
        }

        foreach ($request as $key => $value) {
            $data[$key] = is_array($request[$key]) ? $request[$key]['value'] : $request[$key];
        }

        $data->save();

        return $data;
    }

    public function selectList($request = [], $with = [], $select = [], $fieldValue = 'id', $fieldTitle = 'name', $limit = null)
    {
        $query = $this->model->with($with)->where(function ($query) use ($request) {
            if (! empty($request['idsAllowed'])) {
                $query->whereIn('id', $request['idsAllowed']);
            }
            if (! empty($request['company_id'])) {
                $query->where('company_id', $request['company_id']);
            }
        });

        $query->where(function ($query) use ($request) {
            if (! empty($request['string'])) {
                $value = strval($request['string']);
                $query->orWhere('document', 'like', '%'.$value.'%');
                $query->orWhere('first_name', 'like', '%'.$value.'%');
                $query->orWhere('second_name', 'like', '%'.$value.'%');
                $query->orWhere('first_surname', 'like', '%'.$value.'%');
                $query->orWhere('second_surname', 'like', '%'.$value.'%');
            }
        });
        // Aplica el límite si está definido
        if ($limit !== null) {
            $query->limit($limit);
        }

        $data = $query->get()->map(function ($value) use ($with, $select, $fieldValue, $fieldTitle) {
            $data = [
                'value' => $value->$fieldValue,
                'title' => $value->document.' - '.$value->$fieldTitle,
                'id' => $value->id,
                'type_document' => new TypeDocumentSelectResource($value->typeDocument),
                'document' => $value->document,
                'first_name' => $value->first_name,
                'second_name' => $value->second_name,
                'first_surname' => $value->first_surname,
                'second_surname' => $value->second_surname,
            ];

            if (count($select) > 0) {
                foreach ($select as $s) {
                    $data[$s] = $value->$s;
                }
            }
            if (count($with) > 0) {
                foreach ($with as $s) {
                    $data[$s] = $value->$s;
                }
            }

            return $data;
        });

        return $data;
    }

    public function searchOne($request = [], $with = [], $select = ['*'], $format = null)
    {
        $params = [
            'request' => $request,
            'with' => $with,
            'select' => $select,
            'format' => $format,
        ];
        $cacheKey = $this->cacheService->generateKey("{$this->model->getTable()}_searchOne", $params, 'string');

        return $this->cacheService->remember($cacheKey, function () use ($request, $with, $select, $format) {

            $query = $this->model->query();

            if ($format) {
                switch ($format) {
                    case 'selectInfinite':
                        $query = $query->select(['id', 'codigo', 'nombre']);
                        break;
                    default:
                        $query = $query->select($select);
                        break;
                }
            } else {
                $query = $query->select($select);
            }

            // Construcción de la consulta
            $query = $query->with($with)->where(function ($query) use ($request) {
                if (! empty($request['codigo'])) {
                    $query->where('codigo', $request['codigo']);
                }
            });

            // Obtener el primer resultado
            $data = $query->first();

            // Formatear el resultado según el formato especificado
            if ($data && $format) {
                switch ($format) {
                    case 'selectInfinite':
                        return [
                            'value' => $data->id,
                            'title' => $data->codigo.' - '.$data->nombre,
                            'code' => $data->codigo,
                        ];
                    default:
                        // Si el formato no es reconocido, retorna el objeto original
                        return $data;
                }
            }

            return $data;
        }, Constants::REDIS_TTL);
    }
}

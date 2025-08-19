<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Models\CodeGlosaAnswer;

class CodeGlosaAnswerRepository extends BaseRepository
{
    public function __construct(CodeGlosaAnswer $modelo)
    {
        parent::__construct($modelo);
    }

    public function list($request = [], $with = [], $select = ['*'], $idsAllowed = [], $idsNotAllowed = [])
    {
        $cacheKey = $this->cacheService->generateKey("{$this->model->getTable()}_list", $request, 'string');

        return $this->cacheService->remember($cacheKey, function () use ($request, $with) {

            $data = $this->model->with($with)->where(function ($query) {})
                ->where(function ($query) use ($request) {
                    if (isset($request['searchQueryInfinite']) && ! empty($request['searchQueryInfinite'])) {
                        $query->where('code', 'like', '%'.$request['searchQueryInfinite'].'%');
                        $query->orWhere('name', 'like', '%'.$request['searchQueryInfinite'].'%');
                    }
                });

            if (empty($request['typeData'])) {
                $data = $data->paginate($request['perPage'] ?? 10);
            } else {
                $data = $data->get();
            }

            return $data;
        }, Constants::REDIS_TTL);
    }

    public function store(array $request)
    {
        $request = $this->clearNull($request);

        if (! empty($request['id'])) {
            $data = $this->model->find($request['id']);
        } else {
            $data = $this->model::newModelInstance();
        }

        foreach ($request as $key => $value) {
            $data[$key] = $request[$key];
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
            if (! empty($request['string'])) {
                $value = strval($request['string']);
                $query->where('code', 'like', '%'.$value.'%');
                $query->orWhere('name', 'like', '%'.$value.'%');
            }
        });

        // Aplica el límite si está definido
        if ($limit !== null) {
            $query->limit($limit);
        }

        $data = $query->get()->map(function ($value) use ($with, $select, $fieldValue) {
            $data = [
                'value' => $value->$fieldValue,
                'title' => $value->code.' - '.$value->name,
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
                        $query = $query->select(['id', 'code', 'name']);
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
                if (! empty($request['code'])) {
                    $query->where('code', $request['code']);
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
                            'title' => $data->code.' - '.$data->name,
                            'code' => $data->code,
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

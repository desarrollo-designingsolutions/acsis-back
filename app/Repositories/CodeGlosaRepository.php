<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Models\CodeGlosa;

class CodeGlosaRepository extends BaseRepository
{
    public function __construct(CodeGlosa $modelo)
    {
        parent::__construct($modelo);
    }

    public function list($request = [], $with = [], $select = ['*'], $idsAllowed = [], $idsNotAllowed = [])
    {
        $cacheKey = $this->cacheService->generateKey("{$this->model->getTable()}_list", $request, 'string');

        return $this->cacheService->remember($cacheKey, function () use ($request, $with) {

            $data = $this->model->with($with)->where(function ($query) {})
                ->where(function ($query) use ($request) {

                    if (! empty($request['is_active'])) {
                        $query->where('is_active', $request['is_active']);
                    }
                })
                ->where(function ($query) use ($request) {
                    if (isset($request['searchQueryInfinite']) && ! empty($request['searchQueryInfinite'])) {
                        $query->where('code', 'like', '%'.$request['searchQueryInfinite'].'%');
                        $query->orWhere('description', 'like', '%'.$request['searchQueryInfinite'].'%');
                    }
                })
                ->where(function ($query) use ($request) {

                    if (isset($request['type_code_glosa_id']) && ! empty($request['type_code_glosa_id'])) {
                        $query->whereHas('generalCodeGlosa', function ($subQuery) use ($request) {
                            $subQuery->where('type_code_glosa_id', $request['type_code_glosa_id']);
                        });
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
                $query->where('description', 'like', '%'.$request['string'].'%');
                $query->orWhere('code', 'like', '%'.$request['string'].'%');
            }
        });

        // Aplica el límite si está definido
        if ($limit !== null) {
            $query->limit($limit);
        }

        $data = $query->get()->map(function ($value) use ($with, $select, $fieldValue) {
            $data = [
                'value' => $value->$fieldValue,
                'title' => $value->code.' - '.$value->description,
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
}

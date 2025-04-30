<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Models\Company;
use App\QueryBuilder\Sort\IsActiveSort;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyRepository extends BaseRepository
{
    public function __construct(Company $modelo)
    {
        parent::__construct($modelo);
    }

    public function paginate($request = [])
    {
        $cacheKey = $this->cacheService->generateKey("{$this->model->getTable()}_paginate", $request, 'string');

        return $this->cacheService->remember($cacheKey, function () {
            $query = QueryBuilder::for($this->model->query())
                ->select(['id', 'logo', 'name', 'nit', 'address', 'phone', 'email', 'is_active', 'created_at', 'final_date'])
                ->allowedFilters([
                    'is_active',
                    'nit',
                    AllowedFilter::callback('inputGeneral', function ($query, $value) {
                        $query->where(function ($q) use ($value) {
                            $q->orWhere('name', 'like', "%$value%");
                            $q->orWhere('nit', 'like', "%$value%");
                            $q->orWhere('email', 'like', "%$value%");
                            $q->orWhere('phone', 'like', "%$value%");
                        });
                    }),
                ])
                ->allowedSorts([
                    'name',
                    'nit',
                    'email',
                    'phone',
                    AllowedSort::custom('is_active', new IsActiveSort),
                ])
                ->paginate(request()->perPage ?? Constants::ITEMS_PER_PAGE);

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

    public function selectList($request = [], $with = [], $select = [], $fieldValue = 'id', $fieldTitle = 'name')
    {
        $data = $this->model->with($with)->where(function ($query) use ($request) {
            if (! empty($request['idsAllowed'])) {
                $query->whereIn('id', $request['idsAllowed']);
            }
        })->get()->map(function ($value) use ($with, $select, $fieldValue, $fieldTitle) {
            $data = [
                'value' => $value->$fieldValue,
                'title' => $value->$fieldTitle,
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

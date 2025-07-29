<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Models\ServiceVendor;
use App\QueryBuilder\Filters\QueryFilters;
use App\QueryBuilder\Sort\IsActiveSort;
use App\QueryBuilder\Sort\RelatedTableSort;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceVendorRepository extends BaseRepository
{
    public function __construct(ServiceVendor $modelo)
    {
        parent::__construct($modelo);
    }

    public function paginate($request = [])
    {
        $cacheKey = $this->cacheService->generateKey("{$this->model->getTable()}_paginate", $request, 'string');

        // return $this->cacheService->remember($cacheKey, function ($request) {
        $query = QueryBuilder::for($this->model->query())
            ->with(['type_vendor:id,name'])
            ->select(['service_vendors.id', 'service_vendors.name', 'service_vendors.company_id', 'nit', 'address', 'phone', 'email', 'service_vendors.is_active', 'type_vendor_id'])
            ->allowedFilters([
                'is_active',
                'nit',
                AllowedFilter::callback('inputGeneral', function ($query, $value) {
                    $query->where(function ($subQuery) use ($value) {
                        $subQuery->orWhere('service_vendors.name', 'like', "%$value%");
                        $subQuery->orWhere('nit', 'like', "%$value%");
                        $subQuery->orWhere('email', 'like', "%$value%");
                        $subQuery->orWhere('phone', 'like', "%$value%");

                        $subQuery->orWhereHas('type_vendor', function ($subQuery2) use ($value) {
                            $subQuery2->where('name', 'like', "%$value%");
                        });

                        QueryFilters::filterByText($subQuery, $value, 'is_active', [
                            'activo' => 1,
                            'inactivo' => 0,
                        ]);
                    });
                }),
            ])
            ->allowedSorts([
                'name',
                'nit',
                'email',
                'phone',
                AllowedSort::custom('type_vendor_name', new RelatedTableSort('service_vendors', 'type_vendors', 'name', 'type_vendor_id')),
                AllowedSort::custom('is_active', new IsActiveSort),
            ])->where(function ($query) use ($request) {

                if (isset($request['searchQueryInfinite']) && ! empty($request['searchQueryInfinite'])) {
                    $query->orWhere('name', 'like', '%' . $request['searchQueryInfinite'] . '%');
                    $query->orWhere('nit', 'like', '%' . $request['searchQueryInfinite'] . '%');
                }
            })->where(function ($query) use ($request) {
                if (! empty($request['company_id'])) {
                    $query->where('service_vendors.company_id', $request['company_id']);
                }
            });

        if (empty($request['typeData'])) {
            $query = $query->paginate(request()->perPage ?? Constants::ITEMS_PER_PAGE);
        } else {
            $query = $query->get();
        }

        return $query;
        // }, Constants::REDIS_TTL);
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

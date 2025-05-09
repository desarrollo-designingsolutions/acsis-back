<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Models\Service;
use App\QueryBuilder\Sort\RelatedTableSort;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceRepository extends BaseRepository
{
    public function __construct(Service $modelo)
    {
        parent::__construct($modelo);
    }

    public function paginate($request = [])
    {
        $cacheKey = $this->cacheService->generateKey("{$this->model->getTable()}_paginate", $request, 'string');

        return $this->cacheService->remember($cacheKey, function () use ($request) {
            $query = QueryBuilder::for($this->model->query())
                ->allowedFilters([
                    AllowedFilter::callback('inputGeneral', function ($query, $value) use ($request) {
                        $query->where(function ($subQuery) use ($value) {
                            $subQuery->orWhere('quantity', 'like', "%$value%");

                            $subQuery->orWhere(function ($subQuery) use ($value) {
                                $normalizedValue = preg_replace('/[\$\s\.,]/', '', $value);
                                $subQuery->orWhere('unit_value', 'like', "%$normalizedValue%");
                                $subQuery->orWhere('total_value', 'like', "%$normalizedValue%");
                            });


                            $subQuery->orWhereHas('cups_rip', function ($subQuery2) use ($value) {
                                $subQuery2->where('nombre', 'like', "%$value%");
                                $subQuery2->orWhere('codigo', 'like', "%$value%");
                            });
                        });
                    }),
                ])
                ->allowedSorts([
                    "quantity",
                    "unit_value",
                    "total_value",
                    AllowedSort::custom('cups_rip_codigo', new RelatedTableSort(
                        'services',
                        'cups_rips',
                        'codigo',
                        'cups_rip_id',
                    )),
                    AllowedSort::custom('cups_rip_nombre', new RelatedTableSort(
                        'services',
                        'cups_rips',
                        'nombre',
                        'cups_rip_id',
                    )),
                ])
                ->where(function ($query) use ($request) {
                    if (isset($request['invoice_id']) && ! empty($request['invoice_id'])) {
                        $query->where('invoice_id', $request['invoice_id']);
                    }
                    if (isset($request['company_id']) && ! empty($request['company_id'])) {
                        $query->where('company_id', $request['company_id']);
                    }
                })
                ->paginate(request()->perPage ?? Constants::ITEMS_PER_PAGE);

            return $query;
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

    public function selectList($request = [], $with = [], $select = [], $fieldValue = 'id', $fieldTitle = 'name')
    {
        $data = $this->model->with($with)->where(function ($query) use ($request) {
            if (! empty($request['idsAllowed'])) {
                $query->whereIn('id', $request['idsAllowed']);
            }
            if (! empty($request['company_id'])) {
                $query->where('company_id', $request['company_id']);
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

<?php

namespace App\Repositories;

use App\Enums\Service\TypeServiceEnum;
use App\Helpers\Constants;
use App\Models\Service;
use App\QueryBuilder\Filters\QueryFilters;
use Spatie\QueryBuilder\AllowedFilter;
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
                    AllowedFilter::callback('inputGeneral', function ($query, $value) {
                        $query->where(function ($subQuery) use ($value) {
                            $subQuery->orWhere('quantity', 'like', "%$value%");
                            $subQuery->orWhere('codigo_servicio', 'like', "%$value%");
                            $subQuery->orWhere('nombre_servicio', 'like', "%$value%");

                            $subQuery->orWhere(function ($subQuery) use ($value) {
                                $normalizedValue = preg_replace('/[\$\s\.,]/', '', $value);
                                $subQuery->orWhere('unit_value', 'like', "%$normalizedValue%");
                                $subQuery->orWhere('total_value', 'like', "%$normalizedValue%");
                            });
                            QueryFilters::filterByText($subQuery, $value, 'type', [
                                TypeServiceEnum::SERVICE_TYPE_001->description() => TypeServiceEnum::SERVICE_TYPE_001,
                                TypeServiceEnum::SERVICE_TYPE_002->description() => TypeServiceEnum::SERVICE_TYPE_002,
                                TypeServiceEnum::SERVICE_TYPE_006->description() => TypeServiceEnum::SERVICE_TYPE_006,
                                TypeServiceEnum::SERVICE_TYPE_007->description() => TypeServiceEnum::SERVICE_TYPE_007,
                            ]);
                        });
                    }),
                ])
                ->allowedSorts([
                    'quantity',
                    'unit_value',
                    'total_value',
                    'codigo_servicio',
                    'nombre_servicio',
                    'type',
                ])
                ->where(function ($query) use ($request) {
                    if (isset($request['invoice_id']) && ! empty($request['invoice_id'])) {
                        $query->where('invoice_id', $request['invoice_id']);
                    }
                    if (isset($request['company_id']) && ! empty($request['company_id'])) {
                        $query->where('company_id', $request['company_id']);
                    }
                })
                // Excluir los tipos de servicio no deseados
                ->whereNotIn('type', [
                    TypeServiceEnum::SERVICE_TYPE_003->value,
                    TypeServiceEnum::SERVICE_TYPE_004->value,
                    TypeServiceEnum::SERVICE_TYPE_005->value,
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

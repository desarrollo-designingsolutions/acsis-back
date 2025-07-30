<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Http\Resources\TypeDocument\TypeDocumentSelectResource;
use App\Models\Patient;
use App\QueryBuilder\Sort\DynamicConcatSort;
use Illuminate\Support\Facades\Redis;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PatientRepository extends BaseRepository
{
    public function __construct(Patient $modelo)
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
                            $subQuery->orWhere('document', 'like', "%$value%");
                            $subQuery->orWhereRaw("CONCAT_WS(' ', first_name, second_name, first_surname, second_surname) LIKE ?", ["%{$value}%"]);
                        });
                    }),
                ])
                ->allowedSorts([
                    'document',
                    AllowedSort::custom('full_name', new DynamicConcatSort("first_name, ' ', second_name, ' ', first_surname, ' ', second_surname")),
                ])
                ->where(function ($query) use ($request) {
                    if (isset($request['searchQueryInfinite']) && !empty($request['searchQueryInfinite'])) {
                        $searchValue = $request['searchQueryInfinite'];
                        $query->orWhere('document', 'like', "%$searchValue%");
                        $query->orWhereRaw("CONCAT_WS(' ', first_name, second_name, first_surname, second_surname) LIKE ?", ["%{$searchValue}%"]);
                    }
                })
                ->where(function ($query) use ($request) {
                    if (!empty($request['company_id'])) {
                        $query->where('company_id', $request['company_id']);
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
                $query->orWhere('document', 'like', '%' . $value . '%');
                $query->orWhere('first_name', 'like', '%' . $value . '%');
                $query->orWhere('second_name', 'like', '%' . $value . '%');
                $query->orWhere('first_surname', 'like', '%' . $value . '%');
                $query->orWhere('second_surname', 'like', '%' . $value . '%');
            }
        });
        // Aplica el límite si está definido
        if ($limit !== null) {
            $query->limit($limit);
        }

        $data = $query->get()->map(function ($value) use ($with, $select, $fieldValue, $fieldTitle) {
            $data = [
                'value' => $value->$fieldValue,
                'title' => $value->document . ' - ' . $value->$fieldTitle,
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

    public function getValidationsErrorMessages($user_id)
    {
        // Recuperar y mostrar los errores almacenados en Redis
        $errorListKey = "paginate:patients_import_errors_{$user_id}";
        $errors = Redis::lrange($errorListKey, 0, -1); // Obtener todos los elementos de la lista
        $errorsFormatted = [];

        if (! empty($errors)) {
            foreach ($errors as $index => $errorJson) {
                $errorsFormatted[] = json_decode($errorJson, true); // Decodificar el JSON
            }
        }

        return $errorsFormatted;
    }
}

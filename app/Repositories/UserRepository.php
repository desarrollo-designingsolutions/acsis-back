<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Models\User;
use App\QueryBuilder\Sort\IsActiveSort;
use App\QueryBuilder\Sort\RelatedTableSort;
use App\QueryBuilder\Sort\UserFullNameSort;
use App\Traits\FilterManager;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository extends BaseRepository
{
    use FilterManager;

    public function __construct(User $modelo)
    {
        parent::__construct($modelo);
    }

    public function paginate($request = [])
    {
        $cacheKey = $this->cacheService->generateKey("{$this->model->getTable()}_paginate", $request, 'string');

        return $this->cacheService->remember($cacheKey, function () use ($request) {
            $query = QueryBuilder::for($this->model->query())
                ->select('users.id', 'users.name', 'users.surname', 'users.email', 'users.role_id', 'users.is_active')
                ->allowedFilters([
                    'is_active',
                    AllowedFilter::callback('inputGeneral', function ($query, $value) {
                        $query->where(function ($q) use ($value) {
                            $q->orWhere('name', 'like', "%$value%");
                            $q->orWhere('surname', 'like', "%$value%");
                            $q->orWhereRaw("CONCAT_WS(' ', name, surname) LIKE ?", ["%{$value}%"]);
                            $q->orWhere('email', 'like', "%$value%");
                            $q->orWhereHas('role', function ($subQuery) use ($value) {
                                $subQuery->where('description', 'like', "%$value%");
                            });
                        });
                    }),
                ])
                ->allowedSorts([
                    'name',
                    'surname',
                    'email',
                    AllowedSort::custom('is_active', new IsActiveSort),
                    AllowedSort::custom('role_description', new RelatedTableSort(
                        'users',
                        'roles',
                        'description',
                        'role_id',
                    )),
                    AllowedSort::custom('full_name', new UserFullNameSort),
                ])
                ->where(function ($query) use ($request) {
                    if (! empty($request['company_id'])) {
                        $query->where('users.company_id', $request['company_id']);
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

    public function list($request = [])
    {
        $invalidResult = $this->handleInvalidFilters(['nit']);
        if ($invalidResult) {
            return $invalidResult;
        }

        $this->removeInvalidFilters(['nit']);

        $cacheKey = $this->cacheService->generateKey("{$this->model->getTable()}_list", $request);

        return $this->cacheService->remember($cacheKey, function () use ($request) {
            $query = QueryBuilder::for($this->model->query())
                ->select('users.id', 'users.name', 'users.surname', 'users.email', 'users.role_id', 'users.is_active')
                ->allowedFilters([
                    'is_active',
                    AllowedFilter::callback('inputGeneral', function ($query, $value) {
                        $query->where(function ($q) use ($value) {
                            $q->orWhere('name', 'like', "%$value%");
                            $q->orWhere('surname', 'like', "%$value%");
                            $q->orWhere('email', 'like', "%$value%");
                            $q->orWhereHas('role', function ($subQuery) use ($value) {
                                $subQuery->where('description', 'like', "%$value%");
                            });
                        });
                    }),
                ])
                ->allowedSorts([
                    'name',
                    'surname',
                    'email',
                    AllowedSort::custom('is_active', new IsActiveSort),
                    AllowedSort::custom('role_description', new RelatedTableSort(
                        'users',
                        'roles',
                        'description',
                        'role_id',
                    )),
                    AllowedSort::custom('full_name', new UserFullNameSort),
                ])
                ->where(function ($query) use ($request) {
                    if (! empty($request['company_id'])) {
                        $query->where('users.company_id', $request['company_id']);
                    }
                })
                ->paginate(request()->perPage ?? Constants::ITEMS_PER_PAGE);

            return $query;
        }, Constants::REDIS_TTL);
    }

    public function store($request, $id = null, $withCompany = true)
    {
        $validatedData = $this->clearNull($request);

        $idToUse = $id ?? ($validatedData['id'] ?? null);

        if ($idToUse) {
            $data = $this->model->find($idToUse);
        } else {
            $data = $this->model::newModelInstance();
            if ($withCompany) {
                $data->company_id = auth()->user()->company_id;
            }
        }

        foreach ($request as $key => $value) {
            $data[$key] = is_array($request[$key]) ? $request[$key]['value'] : $request[$key];
        }

        if (! empty($validatedData['password'])) {
            $data->password = $validatedData['password'];
        } else {
            unset($data->password);
        }

        $data->save();

        return $data;
    }

    public function register($request)
    {
        $data = $this->model;

        foreach ($request as $key => $value) {
            $data[$key] = $request[$key];
        }

        $data->save();

        return $data;
    }

    public function findByEmail($email)
    {
        return $this->model::where('email', $email)->first();
    }

    public function selectList($request = [], $with = [], $select = [], $fieldValue = 'id', $fieldTitle = 'name')
    {
        $data = $this->model->with($with)->where(function ($query) use ($request) {
            if (! empty($request['idsAllowed'])) {
                $query->whereIn('id', $request['idsAllowed']);
            }

            $query->where('is_active', true);
            $query->where('company_id', auth()->user()->company_id);
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

    public function countData($request = [])
    {
        $data = $this->model->where(function ($query) use ($request) {
            if (! empty($request['status_id'])) {
                $query->where('status_id', $request['status_id']);
            }

            // rol_in_id
            if (isset($request['rol_in_id']) && count($request['rol_in_id']) > 0) {
                $query->whereIn('role_id', $request['rol_in_id']);
            }
            // divisio_in_id
            if (isset($request['division_in_id']) && count($request['division_in_id']) > 0) {
                $query->whereIn('branch_division_id', $request['division_in_id']);
            }
            $query->where('company_id', Auth::user()->company_id);
            $query->where('role_id', '!=', 1);
        })->count();

        return $data;
    }
}

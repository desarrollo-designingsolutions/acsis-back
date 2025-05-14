<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Models\File;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FileRepository extends BaseRepository
{
    public function __construct(File $modelo)
    {
        parent::__construct($modelo);
    }

    public function list($request = [], $with = [], $select = ['*'])
    {
        $data = $this->model->select($select)
            ->with($with)
            ->where(function ($query) use ($request) {
                if (! empty($request['name'])) {
                    $query->where('id', 'like', '%'.$request['name'].'%');
                }
                if (! empty($request['fileable_id'])) {
                    $query->where('fileable_id', $request['fileable_id']);
                }
                if (! empty($request['fileable_type'])) {
                    $query->where('fileable_type', 'App\\Models\\'.$request['fileable_type']);
                }
            })
            ->where(function ($query) use ($request) {
                if (! empty($request['searchQuery'])) {
                    $query->where('name', 'like', '%'.$request['searchQuery'].'%');
                }
            });
        if (empty($request['typeData'])) {
            $data = $data->paginate($request['perPage'] ?? Constants::ITEMS_PER_PAGE);
        } else {
            $data = $data->get();
        }

        return $data;
    }

    public function paginate($request = [])
    {
        $cacheKey = $this->cacheService->generateKey("{$this->model->getTable()}_paginate", $request, 'string');

        return $this->cacheService->remember($cacheKey, function () use ($request) {

            $query = QueryBuilder::for($this->model->query())
                ->allowedFilters([
                    AllowedFilter::callback('inputGeneral', function ($query, $value) {
                        $query->where(function ($query) use ($value) {
                            $query->orWhere('filename', 'like', "%$value%");
                        });
                    }),
                ])
                ->allowedSorts([
                    'filename',
                    'created_at',
                ])
                ->where(function ($query) use ($request) {
                    if (isset($request['company_id']) && ! empty($request['company_id'])) {
                        $query->where('company_id', $request['company_id']);
                    }
                    if (! empty($request['fileable_id'])) {
                        $query->where('fileable_id', $request['fileable_id']);
                    }
                    if (! empty($request['fileable_type'])) {
                        $query->where('fileable_type', 'App\\Models\\'.$request['fileable_type']);
                    }
                })
                ->paginate(request()->perPage ?? Constants::ITEMS_PER_PAGE);

            return $query;
        }, Constants::REDIS_TTL);
    }

    public function store($request)
    {
        $request = $this->clearNull($request);

        if (! empty($request['id'])) {
            $data = $this->model->find($request['id']);
        } else {
            $data = $this->model::newModelInstance();
        }

        foreach ($request as $key => $value) {
            $data[$key] = is_array($request[$key]) ? $request[$key]['value'] : $request[$key];
        }

        $data->save();

        return $data;
    }
}

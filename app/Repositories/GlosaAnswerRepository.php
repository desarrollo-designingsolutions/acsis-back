<?php

namespace App\Repositories;

use App\Enums\GlosaAnswer\StatusGlosaAnswerEnum;
use App\Helpers\Constants;
use App\Models\GlosaAnswer;
use App\QueryBuilder\Filters\QueryFilters;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GlosaAnswerRepository extends BaseRepository
{
    public function __construct(GlosaAnswer $modelo)
    {
        parent::__construct($modelo);
    }

    public function paginate($request = [])
    {
        $cacheKey = $this->cacheService->generateKey("{$this->model->getTable()}_paginate", $request, 'string');

        return $this->cacheService->remember($cacheKey, function () use ($request) {

            $query = QueryBuilder::for($this->model->query())
                ->select('glosa_answers.*')
                ->allowedFilters([
                    AllowedFilter::callback('inputGeneral', function ($query, $value) {
                        $query->where(function ($subQuery) use ($value) {
                            $subQuery->orWhere('glosa_answers.observation', 'like', "%$value%");

                            QueryFilters::filterByDMYtoYMD($subQuery, $value, 'date_answer');

                            $subQuery->orWhere(function ($subQuery2) use ($value) {
                                $normalizedValue = preg_replace('/[\$\s\.,]/', '', $value);
                                $subQuery2->orWhere('value_approved', 'like', "%$normalizedValue%");
                                $subQuery2->orWhere('value_accepted', 'like', "%$normalizedValue%");
                            });

                            QueryFilters::filterByText($subQuery, $value, 'status', [
                                StatusGlosaAnswerEnum::GLOSA_ANSWER_STATUS_001->description() => StatusGlosaAnswerEnum::GLOSA_ANSWER_STATUS_001,
                                StatusGlosaAnswerEnum::GLOSA_ANSWER_STATUS_002->description() => StatusGlosaAnswerEnum::GLOSA_ANSWER_STATUS_002,
                            ]);
                        });
                    }),
                ])
                ->allowedSorts([
                    'date_answer',
                    'observation',
                    'value_approved',
                    'value_accepted',
                    'status',
                ])
                ->where(function ($query) use ($request) {
                    if (isset($request['glosa_id']) && ! empty($request['glosa_id'])) {
                        $query->where('glosa_id', $request['glosa_id']);
                    }
                })
                ->paginate(request()->perPage ?? Constants::ITEMS_PER_PAGE);

            return $query;
        }, Constants::REDIS_TTL);
    }

    public function list($request = [], $with = [], $select = ['*'], $idsAllowed = [], $idsNotAllowed = [])
    {
        $data = $this->model->with($with)->where(function ($query) {})
            ->where(function ($query) use ($request) {

                if (! empty($request['company_id'])) {
                    $query->where('company_id', $request['company_id']);
                }
            })
            ->where(function ($query) use ($request) {
                if (isset($request['searchQueryInfinite']) && ! empty($request['searchQueryInfinite'])) {
                    $query->orWhere('name', 'like', '%'.$request['searchQueryInfinite'].'%');
                }
            });

        if (empty($request['typeData'])) {
            $data = $data->paginate($request['perPage'] ?? 10);
        } else {
            $data = $data->get();
        }

        return $data;
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

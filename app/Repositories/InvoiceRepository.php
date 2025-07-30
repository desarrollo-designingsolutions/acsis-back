<?php

namespace App\Repositories;

use App\Enums\Invoice\StatusInvoiceEnum;
use App\Enums\Invoice\StatusXmlInvoiceEnum;
use App\Enums\Invoice\TypeInvoiceEnum;
use App\Helpers\Constants;
use App\Models\Invoice;
use App\QueryBuilder\Filters\DataSelectFilter;
use App\QueryBuilder\Filters\DateRangeFilter;
use App\QueryBuilder\Filters\QueryFilters;
use App\QueryBuilder\Sort\RelatedTableSort;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class InvoiceRepository extends BaseRepository
{
    public function __construct(Invoice $modelo)
    {
        parent::__construct($modelo);
    }

    public function paginate($request = [])
    {
        $cacheKey = $this->cacheService->generateKey("{$this->model->getTable()}_paginate", $request, 'string');

        return $this->cacheService->remember($cacheKey, function () use ($request) {
            $query = QueryBuilder::for($this->model->query())
                ->with(['patient', 'entity', 'serviceVendor', 'furips1', 'furtran'])
                ->select(['invoices.id', 'invoices.entity_id', 'invoices.type', 'invoices.patient_id', 'invoices.invoice_number', 'invoices.radication_number', 'invoices.value_glosa', 'invoices.value_paid', 'invoices.invoice_date', 'invoices.radication_date', 'invoices.is_active', 'invoices.status', 'invoices.status_xml', 'invoices.path_xml', 'invoices.service_vendor_id'])
                ->allowedFilters([

                    AllowedFilter::callback('inputGeneral', function ($query, $value) {
                        $query->where(function ($subQuery) use ($value) {
                            $subQuery->orWhere('invoice_number', 'like', "%$value%");

                            $subQuery->orWhere(function ($subQuery2) use ($value) {
                                $normalizedValue = preg_replace('/[\$\s\.,]/', '', $value);
                                $subQuery2->orWhere('value_glosa', 'like', "%$normalizedValue%");
                                $subQuery2->orWhere('value_paid', 'like', "%$normalizedValue%");
                            });

                            $subQuery->orWhereHas('patient', function ($subQuery2) use ($value) {
                                $subQuery2->whereRaw("CONCAT_WS(' ', patients.first_name, patients.second_name, patients.first_surname, patients.second_surname) LIKE ?", ["%{$value}%"]);
                            });

                            $subQuery->orWhereHas('entity', function ($subQuery2) use ($value) {
                                $subQuery2->where('entities.corporate_name', 'like', "%$value%");
                            });

                            QueryFilters::filterByDMYtoYMD($subQuery, $value, 'radication_date');
                            QueryFilters::filterByText($subQuery, $value, 'type', [
                                TypeInvoiceEnum::INVOICE_TYPE_001->description() => TypeInvoiceEnum::INVOICE_TYPE_001,
                                TypeInvoiceEnum::INVOICE_TYPE_002->description() => TypeInvoiceEnum::INVOICE_TYPE_002,
                            ]);
                            QueryFilters::filterByText($subQuery, $value, 'status', [
                                StatusInvoiceEnum::INVOICE_STATUS_001->description() => StatusInvoiceEnum::INVOICE_STATUS_001,
                                StatusInvoiceEnum::INVOICE_STATUS_002->description() => StatusInvoiceEnum::INVOICE_STATUS_002,
                                StatusInvoiceEnum::INVOICE_STATUS_003->description() => StatusInvoiceEnum::INVOICE_STATUS_003,
                                StatusInvoiceEnum::INVOICE_STATUS_004->description() => StatusInvoiceEnum::INVOICE_STATUS_004,
                                StatusInvoiceEnum::INVOICE_STATUS_005->description() => StatusInvoiceEnum::INVOICE_STATUS_005,
                                StatusInvoiceEnum::INVOICE_STATUS_006->description() => StatusInvoiceEnum::INVOICE_STATUS_006,
                                StatusInvoiceEnum::INVOICE_STATUS_007->description() => StatusInvoiceEnum::INVOICE_STATUS_007,
                            ]);
                            QueryFilters::filterByText($subQuery, $value, 'status_xml', [
                                StatusXmlInvoiceEnum::INVOICE_STATUS_XML_001->description() => StatusXmlInvoiceEnum::INVOICE_STATUS_XML_001,
                                StatusXmlInvoiceEnum::INVOICE_STATUS_XML_002->description() => StatusXmlInvoiceEnum::INVOICE_STATUS_XML_002,
                                StatusXmlInvoiceEnum::INVOICE_STATUS_XML_003->description() => StatusXmlInvoiceEnum::INVOICE_STATUS_XML_003,
                            ]);
                        });
                    }),
                    AllowedFilter::custom('status', new DataSelectFilter),
                    AllowedFilter::custom('invoices.radication_date', new DateRangeFilter),
                ])
                ->allowedSorts([
                    'radication_date',
                    'invoice_number',
                    'type',
                    'value_glosa',
                    'value_paid',
                    'status',
                    'status_xml',
                    AllowedSort::custom('entity_name', new RelatedTableSort('invoices', 'entities', 'corporate_name', 'entity_id')),
                    AllowedSort::custom('patient_name', new RelatedTableSort('invoices', 'patients', 'first_name', 'patient_id')),
                ])->where(function ($query) use ($request) {
                    if (! empty($request['company_id'])) {
                        $query->where('invoices.company_id', $request['company_id']);
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

    public function validateInvoiceNumber($request = []): bool
    {
        $data = $this->model
            ->where(function ($query) use ($request) {
                if (! empty($request['id'])) {
                    $query->where('id', $request['id']);
                }
                if (! empty($request['company_id'])) {
                    $query->where('company_id', $request['company_id']);
                }
                if (! empty($request['invoice_number'])) {
                    $query->where('invoice_number', $request['invoice_number']);
                }
                if (! empty($request['service_vendor_id'])) {
                    $query->where('service_vendor_id', $request['service_vendor_id']);
                }
                if (! empty($request['entity_id'])) {
                    $query->where('entity_id', $request['entity_id']);
                }
            })->first();

        return $data !== null; // Retorna true si la licencia cumple con ambas condiciones
    }

    public function countData($request = [])
    {
        $query = $this->model->where(function ($query) use ($request) {
            if (! empty($request['company_id'])) {
                $query->where('company_id', $request['company_id']);
            }
            if (! empty($request['start_date']) && ! empty($request['end_date'])) {
                $query->whereBetween('invoice_date', [$request['start_date'], $request['end_date']]);
            }
            if (! empty($request['service_vendor_id'])) {
                $query->where('service_vendor_id', $request['service_vendor_id']);
            }
        });

        // Datos del período actual
        $totalSum = $query->sum('total');
        $invoiceCount = $query->count();

        $title = 'Valor total facturación';
        $value = formatNumber($totalSum);

        return [
            'icon' => 'tabler-currency-dollar',
            'color' => 'success',
            'title' => $title,
            'value' => $value,
            'secondary_data' => $invoiceCount.' facturas',
            'isHover' => false,
            'type' => 1,
            'to' => [],
        ];
    }

    public function countApprovedVsGlosa($request = [])
    {
        $query = $this->model->where(function ($query) use ($request) {
            if (! empty($request['company_id'])) {
                $query->where('company_id', $request['company_id']);
            }
            if (! empty($request['start_date']) && ! empty($request['end_date'])) {
                $query->whereBetween('invoice_date', [$request['start_date'], $request['end_date']]);
            }
            if (! empty($request['service_vendor_id'])) {
                $query->where('service_vendor_id', $request['service_vendor_id']);
            }
        });

        // Sumatorias de valores aprobados y glosados
        $approvedSum = $query->sum('value_paid');
        $glosaSum = $query->sum('value_glosa');
        $totalSum = $query->sum('total');

        // Calcular porcentajes
        $approvedPercentage = $totalSum > 0 ? ($approvedSum / $totalSum) * 100 : 0;
        $glosaPercentage = $totalSum > 0 ? ($glosaSum / $totalSum) * 100 : 0;

        $value = round($approvedPercentage, 2).'% / '.round($glosaPercentage, 2).'%';
        $secondary_data = formatNumber($approvedSum).' aprobados / '.formatNumber($glosaSum).' glosados';

        return [
            'title' => 'Facturación Aprobada vs Glosada',
            'value' => $value,
            'secondary_data' => $secondary_data,
            'icon' => 'tabler-percentage',
            'color' => 'success',
            'isHover' => false,
            'type' => 2,
            'to' => [],
        ];
    }

    public function countInReviewVsPending($request = [])
    {
        $query = $this->model->where(function ($query) use ($request) {
            if (! empty($request['company_id'])) {
                $query->where('company_id', $request['company_id']);
            }
            if (! empty($request['start_date']) && ! empty($request['end_date'])) {
                $query->whereBetween('invoice_date', [$request['start_date'], $request['end_date']]);
            }
            if (! empty($request['service_vendor_id'])) {
                $query->where('service_vendor_id', $request['service_vendor_id']);
            }
        });

        // Facturas en estado "Radicado" (en revisión)
        $inReviewQuery = (clone $query)->where('status', \App\Enums\Invoice\StatusInvoiceEnum::INVOICE_STATUS_002->value);
        $inReviewCount = $inReviewQuery->count();
        $inReviewSum = $inReviewQuery->sum('total');

        // Facturas en estado "Pendiente"
        $pendingQuery = (clone $query)->where('status', \App\Enums\Invoice\StatusInvoiceEnum::INVOICE_STATUS_008->value);
        $pendingCount = $pendingQuery->count();
        $pendingSum = $pendingQuery->sum('total');

        $value = $inReviewCount.' / '.$pendingCount;
        $secondary_data = formatNumber($inReviewSum).'  en revisión / '.formatNumber($pendingSum).'pendientes';

        return [
            'title' => 'Facturas en revisión / Pendientes de radicación',
            'value' => $value,
            'secondary_data' => $secondary_data,
            'icon' => 'tabler-file-search',
            'color' => 'warning',
            'isHover' => false,
            'to' => [],
        ];
    }

    public function countPendingPayments($request = [],$title = 'Montos pendientes de pago')
    {
        $query = $this->model->where(function ($query) use ($request) {
            if (! empty($request['company_id'])) {
                $query->where('company_id', $request['company_id']);
            }
            if (! empty($request['start_date']) && ! empty($request['end_date'])) {
                $query->whereBetween('invoice_date', [$request['start_date'], $request['end_date']]);
            }
            if (! empty($request['status'])) {
                $query->where('status', $request['status']);
            }
            if (! empty($request['service_vendor_id'])) {
                $query->where('service_vendor_id', $request['service_vendor_id']);
            }
        });

        // Facturas con saldo pendiente mayor a 0
        $pendingQuery = (clone $query)->where('remaining_balance', '>', 0);

        // Sumatoria del valor pendiente
        $pendingTotal = $pendingQuery->sum('remaining_balance');

        // Cantidad de facturas con saldo pendiente
        $pendingCount = $pendingQuery->count();

        // Formatear los datos como espera el frontend
        $value = formatNumber($pendingTotal);
        $secondary_data = "$pendingCount facturas pendientes de pago";

        return [
            'title' => $title,
            'value' => $value,
            'secondary_data' => $secondary_data,
            'icon' => 'tabler-currency-dollar',
            'color' => 'error',
            'isHover' => false,
            'to' => [],
        ];
    }

    public function countAverageResponseTime($request = [])
    {

        // Formatear los datos como espera el frontend
        $value = '15.3 días';
        $secondary_data = 'Tiempo de respuesta de aseguradoras';

        return [
            'title' => 'Tiempo promedio de respuesta',
            'value' => $value,
            'secondary_data' => $secondary_data,
            'icon' => 'tabler-clock',
            'color' => 'success',
            'isHover' => false,
            'to' => [],
        ];
    }

    public function countRecoveredGlosas($request = [])
    {
        // Formatear los datos como espera el frontend
        $value = '68.5%';
        $secondary_data = '$184,679,381 recuperados de $269,604,936 glosados';

        return [
            'title' => '% Glosas recuperadas',
            'value' => $value,
            'secondary_data' => $secondary_data,
            'icon' => 'tabler-percentage',
            'color' => 'success',
            'isHover' => false,
            'to' => [],
        ];
    }
}

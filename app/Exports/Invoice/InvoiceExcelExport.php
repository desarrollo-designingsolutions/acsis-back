<?php

namespace App\Exports\Invoice;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class InvoiceExcelExport implements FromView, ShouldAutoSize, WithEvents
{
    use Exportable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $data = collect($this->data)->map(function ($value) {

            return [
                'entity_name' => $value->entity?->corporate_name,
                'invoice_number' => $value->invoice_number,
                'type_name' => $value->type?->description() ?? 'Desconocido',
                'value_paid' => formatNumber($value->value_paid),
                'value_glosa' => formatNumber($value->value_glosa),
                'radication_date' => $value->radication_date,
                'patient_name' => $value->patient?->full_name,
                'status' => $value->status?->description() ?? 'Desconocido',
            ];
        });

        return view('Exports.Invoice.InvoiceExportExcel', ['data' => $data]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Obtener el objeto hoja de cálculo
                $sheet = $event->sheet;

                // Obtener el rango de celdas con datos
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();
                $range = 'A1:'.$highestColumn.$highestRow;

                // Establecer el filtro automático en el rango de celdas
                $sheet->setAutoFilter($range);
            },
        ];
    }
}

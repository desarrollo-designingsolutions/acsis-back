<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EntityExcelExport implements FromView, ShouldAutoSize, WithEvents
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
                'id' => $value->id,
                'corporate_name' => $value->corporate_name,
                'nit' => $value->nit,
                'email' => $value->email,
                'type_entity_name' => $value->typeEntity?->name,
                'is_active' => $value->is_active ? 'Activo' : 'Inactivo',
            ];
        });

        return view('Exports.Entity.EntityExportExcel', ['data' => $data]);
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

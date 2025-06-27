<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PatientExcelExport implements FromView, ShouldAutoSize, WithEvents
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
                'tipo_id_pisi_nombre' => $value->tipo_id_pisi?->nombre,
                'document' => $value->document,
                'rips_tipo_usuario_version2_nombre' => $value->rips_tipo_usuario_version2?->nombre,
                'birth_date' => $value->birth_date,
                'sexo_nombre' => $value->sexo?->nombre,
                'pais_residency_nombre' => $value->pais_residency?->nombre,
                'municipio_residency_nombre' => $value->municipio_residency?->nombre,
                'zona_version2_nombre' => $value->zona_version2?->nombre,
                'incapacity' => $value->incapacity ? 'Sí' : 'No',
                'pais_origin_nombre' => $value->pais_origin?->nombre,
                'first_name' => $value->first_name,
                'second_name' => $value->second_name,
                'first_surname' => $value->first_surname,
                'second_surname' => $value->second_surname,
            ];
        });

        return view('Exports.Patient.PatientExportExcel', ['data' => $data]);
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

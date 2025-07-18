<?php

namespace App\Exports\Patient;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PatientFormatExcelExport implements FromView, ShouldAutoSize, WithEvents
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
                'tipo_id_pisi_id' => $value['tipo_id_pisi_id'],
                'document' => $value['document'],
                'rips_tipo_usuario_version2_id' => $value['rips_tipo_usuario_version2_id'],
                'birth_date' => $value['birth_date'],
                'sexo_id' => $value['sexo_id'],
                'pais_residency_id' => $value['pais_residency_id'],
                'municipio_residency_id' => $value['municipio_residency_id'],
                'zona_version2_id' => $value['zona_version2_id'],
                'incapacity' => $value['incapacity'],
                'pais_origin_id' => $value['pais_origin_id'],
                'first_name' => $value['first_name'],
                'second_name' => $value['second_name'],
                'first_surname' => $value['first_surname'],
                'second_surname' => $value['second_surname'],
            ];
        });

        return view('Exports.Patient.PatientFormatExcelExport', ['data' => $data]);
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

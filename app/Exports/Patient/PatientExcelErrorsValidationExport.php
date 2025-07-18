<?php

namespace App\Exports\Patient;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class PatientExcelErrorsValidationExport implements FromCollection
{
    use Exportable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // Convertir $this->data en una colección y extraer solo los valores
        return collect($this->data)->map(function ($item) {
            // Si $item es un arreglo asociativo, devolver solo los valores
            return array_values($item);
        });
    }
}

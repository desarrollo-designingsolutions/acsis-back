<?php

namespace App\Exports\Patient;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class PatientInformationExcelExport implements WithMultipleSheets
{
    use Exportable;

    protected $tipoIdPisis;

    protected $ripsTipoUsuarioVersion2;

    protected $sexos;

    protected $paises;

    protected $municipios;

    protected $zonaVersion2;

    protected $incapacity;

    protected $request;

    public function __construct($tipoIdPisis, $ripsTipoUsuarioVersion2, $sexos, $paises, $municipios, $zonaVersion2, $incapacity, $request)
    {
        $this->tipoIdPisis = $tipoIdPisis;
        $this->ripsTipoUsuarioVersion2 = $ripsTipoUsuarioVersion2;
        $this->sexos = $sexos;
        $this->paises = $paises;
        $this->municipios = $municipios;
        $this->zonaVersion2 = $zonaVersion2;
        $this->incapacity = $incapacity;
        $this->request = $request;
    }

    public function sheets(): array
    {
        return [
            $this->createSheet('Tipo de documento', $this->tipoIdPisis, 'Exports.Patient.PatientInfoTipoIdPisisExcelExport'),
            $this->createSheet('Tipo de usuario', $this->ripsTipoUsuarioVersion2, 'Exports.Patient.PatientInfoRipsTipoUsuarioVersion2ExcelExport'),
            $this->createSheet('Sexo', $this->sexos, 'Exports.Patient.PatientInfoSexoExcelExport'),
            $this->createSheet('Pais de Residencia', $this->paises, 'Exports.Patient.PatientInfoPaisResidenciaExcelExport'),
            $this->createSheet('Municipio de Residencia', $this->municipios, 'Exports.Patient.PatientInfoMunicipioResidenciaExcelExport'),
            $this->createSheet('Zona Territorial de Residencia', $this->zonaVersion2, 'Exports.Patient.PatientInfoZonaVersion2ExcelExport'),
            $this->createSheet('Incapacidad', $this->incapacity, 'Exports.Patient.PatientInfoIncapacidadExcelExport'),
            $this->createSheet('Pais de Origen', $this->paises, 'Exports.Patient.PatientInfoPaisOrigenExcelExport'),
        ];
    }

    protected function createSheet($title, $data, $view)
    {
        $request = $this->request;

        return new class($title, $data, $view, $request) implements FromView, ShouldAutoSize, WithEvents, WithTitle
        {
            protected $title;

            protected $data;

            protected $view;

            protected $request;

            public function __construct($title, $data, $view, $request)
            {
                $this->title = $title;
                $this->data = $data;
                $this->view = $view;
                $this->request = $request;
            }

            public function view(): View
            {
                return \Illuminate\Support\Facades\View::make($this->view, [
                    'data' => $this->data,
                    'request' => $this->request,
                ]);
            }

            public function title(): string
            {
                return $this->title;
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        $sheet = $event->sheet;
                        $highestColumn = $sheet->getHighestColumn();
                        $highestRow = $sheet->getHighestRow();
                        $range = 'A1:'.$highestColumn.$highestRow;
                        $sheet->setAutoFilter($range);
                    },
                ];
            }
        };
    }
}

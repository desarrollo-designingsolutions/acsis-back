<?php

namespace Database\Seeders;

use App\Models\RipsFinalidadConsultaVersion2;
use App\Services\ExcelService;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class RipsFinalidadConsultaVersion2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $excelService = new ExcelService();
        $sheet = null;

        try {
            $sheet = $excelService
                ->getSpreadsheetFromExcel(database_path('db/TablaReferencia_RIPSFinalidadConsultaVersion2__1 (1).xlsx'))
                ->getSheetByName('Table')
                ->toArray();
        } catch (Exception $e) {
            //$this->error('Error al leer el excel');
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            //$this->error('Error al obtener la hoja');
        }

        if ($sheet) {
            unset($sheet[0]);
            foreach ($sheet as $dataSheet) {
                RipsFinalidadConsultaVersion2::updateOrCreate(
                    ['codigo' => $dataSheet[1]], // condiciones para buscar el registro
                    [
                        'nombre' => $dataSheet[2],
                        'descripcion' => $dataSheet[3],
                    ]
                );
            }
        }
    }
}

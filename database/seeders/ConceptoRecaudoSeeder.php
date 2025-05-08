<?php

namespace Database\Seeders;

use App\Models\ConceptoRecaudo;
use App\Services\ExcelService;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class ConceptoRecaudoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $excelService = new ExcelService;
        $sheet = null;

        try {
            $sheet = $excelService
                ->getSpreadsheetFromExcel(database_path('db/TablaReferencia_conceptoRecaudo__1 (3).xlsx'))
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
                ConceptoRecaudo::updateOrCreate(
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

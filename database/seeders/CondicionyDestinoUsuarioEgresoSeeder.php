<?php

namespace Database\Seeders;

use App\Models\CondicionyDestinoUsuarioEgreso;
use App\Services\ExcelService;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class CondicionyDestinoUsuarioEgresoSeeder extends Seeder
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
                ->getSpreadsheetFromExcel(database_path('db/20-TablaReferencia_CondicionyDestinoUsuarioEgreso__1.xlsx'))
                ->getSheetByName('Table')
                ->toArray();
        } catch (Exception $e) {
            // $this->error('Error al leer el excel');
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            // $this->error('Error al obtener la hoja');
        }

        if ($sheet) {
            unset($sheet[0]);

            // Inicializar la barra de progreso
            $this->command->info('Starting Seed Data ...');
            $bar = $this->command->getOutput()->createProgressBar(count($sheet));

            foreach ($sheet as $dataSheet) {
                CondicionyDestinoUsuarioEgreso::updateOrCreate(
                    ['codigo' => $dataSheet[1]], // condiciones para buscar el registro
                    [
                        'nombre' => $dataSheet[2],
                        'descripcion' => $dataSheet[3],
                    ]
                );
                $bar->advance();
            }
            $bar->finish(); // Finalizar la barra
        }
    }
}

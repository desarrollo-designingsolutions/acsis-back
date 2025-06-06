<?php

namespace Database\Seeders;

use App\Models\RipsCausaExternaVersion2;
use App\Services\ExcelService;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class RipsCausaExternaVersion2Seeder extends Seeder
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
                ->getSpreadsheetFromExcel(database_path('db/13-TablaReferencia_RIPSCausaExternaVersion2__1.xlsx'))
                ->getSheetByName('Table')
                ->toArray();
        } catch (Exception $e) {
            // $this->error('Error al leer el excel');
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            // $this->error('Error al obtener la hoja');
        }

        if ($sheet) {
            // Inicializar la barra de progreso
            $this->command->info('Starting Seed Data ...');
            $bar = $this->command->getOutput()->createProgressBar(count($sheet));

            unset($sheet[0]);
            foreach ($sheet as $dataSheet) {
                RipsCausaExternaVersion2::updateOrCreate(
                    ['codigo' => $dataSheet[1]], // condiciones para buscar el registro
                    [
                        'nombre' => $dataSheet[2],
                        'descripcion' => $dataSheet[3],
                        'habilitado' => $dataSheet[3],
                        'aplicacion' => $dataSheet[4],
                        'isStandardGEL' => $dataSheet[5],
                        'isStandardMSPS' => $dataSheet[6],
                        'extra_I' => $dataSheet[7],
                        'extra_II' => $dataSheet[8],
                        'extra_III' => $dataSheet[9],
                        'extra_IV' => $dataSheet[10],
                        'extra_V' => $dataSheet[11],
                        'extra_VI' => $dataSheet[12],
                        'extra_VII' => $dataSheet[13],
                        'extra_VIII' => $dataSheet[14],
                        'extra_IX' => $dataSheet[15],
                        'extra_X' => $dataSheet[16],
                        'valorRegistro' => $dataSheet[17],
                        'usuarioResponsable' => $dataSheet[18],
                        'fecha_actualizacion' => $dataSheet[19],
                        'isPublicPrivate' => $dataSheet[20],
                    ]
                );
                $bar->advance();
            }
            $bar->finish(); // Finalizar la barra
        }
    }
}

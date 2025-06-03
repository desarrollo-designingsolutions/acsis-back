<?php

namespace Database\Seeders;

use App\Models\IpsCodHabilitacion;
use App\Services\ExcelService;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class IpsCodHabilitacionSeeder2 extends Seeder
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
                ->getSpreadsheetFromExcel(database_path('db/TablaReferencia_IpsCodHabilitacions__2.xlsx'))
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
                IpsCodHabilitacion::updateOrCreate(
                    ['codigo' => $dataSheet[1]], // condiciones para buscar el registro
                    [
                        'nombre' => $dataSheet[2],
                        'descripcion' => $dataSheet[3],
                        'habilitado' => $dataSheet[4],
                        'aplicacion' => $dataSheet[5],
                        'isStandardGEL' => $dataSheet[6],
                        'isStandardMSPS' => $dataSheet[7],
                        'tipoIDPrestador' => $dataSheet[8],
                        'nroIDPrestador' => $dataSheet[9],
                        'codigoPrestador' => $dataSheet[10],
                        'codMpioSede' => $dataSheet[11],
                        'nombreMpioSede' => $dataSheet[12],
                        'nombreDptoSede' => $dataSheet[13],
                        'clasePrestador' => $dataSheet[14],
                        'nomClasePrestador' => $dataSheet[15],
                        'extra_IX' => $dataSheet[16],
                        'extra_X' => $dataSheet[17],
                        'valorRegistro' => $dataSheet[18],
                        'usuarioResponsable' => $dataSheet[19],
                        'fecha_actualizacion' => $dataSheet[20],
                        'isPublicPrivate' => $dataSheet[21],
                    ]
                );
                $bar->advance();
            }
            $bar->finish(); // Finalizar la barra
        }
    }
}

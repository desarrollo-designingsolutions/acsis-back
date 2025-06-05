<?php

namespace Database\Seeders;

use App\Models\IpsNoReps;
use App\Services\ExcelService;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class IpsNoRepsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $excelService = new ExcelService;
        $batchSize = 1000; // Tamaño del lote para inserciones

        try {
            // Cargar el archivo Excel
            $spreadsheet = $excelService->getSpreadsheetFromExcel(database_path('db/09-TablaReferencia_IPSnoREPS__1.xlsx'));
            $sheet = $spreadsheet->getSheetByName('Table');

            if (! $sheet) {
                $this->command->error('No se encontró la hoja "Table" en el archivo Excel.');

                return;
            }

            // Obtener el número total de filas
            $totalRows = $sheet->getHighestRow();
            $this->command->info("Procesando {$totalRows} filas...");

            // Inicializar barra de progreso
            $bar = $this->command->getOutput()->createProgressBar($totalRows - 1); // Restar 1 por la cabecera

            // Iterar por filas en lotes
            $batch = [];

            foreach ($sheet->getRowIterator(2) as $row) { // Empezar desde la fila 2 para omitir cabecera
                $cells = [];
                foreach ($row->getCellIterator() as $cell) {
                    $cells[] = $cell->getValue(); // Obtener el valor de cada celda
                }

                // Preparar datos para inserción
                $batch[] = [
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'codigo' => $cells[1] ?? null,
                    'nombre' => $cells[2] ?? null,
                    'descripcion' => $cells[3] ?? null,
                    'habilitado' => $cells[4] ?? null,
                    'aplicacion' => $cells[5] ?? null,
                    'isStandardGEL' => $cells[6] ?? null,
                    'isStandardMSPS' => $cells[7] ?? null,
                    'telefono' => $cells[8] ?? null,
                    'gerente' => $cells[9] ?? null,
                    'regimen' => $cells[10] ?? null,
                    'codDepartamento' => $cells[11] ?? null,
                    'departamento' => $cells[12] ?? null,
                    'codMunicipio' => $cells[13] ?? null,
                    'municipio' => $cells[14] ?? null,
                    'tipoIPS' => $cells[15] ?? null,
                    'nivelAtencion' => $cells[16] ?? null,
                    'nit' => $cells[17] ?? null,
                    'valorRegistro' => $cells[18] ?? null,
                    'usuarioResponsable' => $cells[19] ?? null,
                    'fecha_actualizacion' => $cells[20] ?? null,
                    'isPublicPrivate' => $cells[21] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Insertar en lotes
                if (count($batch) >= $batchSize) {
                    IpsNoReps::upsert(
                        $batch,
                        ['codigo'], // Clave única para actualización
                        [
                            'nombre',
                            'descripcion',
                            'habilitado',
                            'aplicacion',
                            'isStandardGEL',
                            'isStandardMSPS',
                            'telefono',
                            'gerente',
                            'regimen',
                            'codDepartamento',
                            'departamento',
                            'codMunicipio',
                            'municipio',
                            'tipoIPS',
                            'nivelAtencion',
                            'nit',
                            'valorRegistro',
                            'usuarioResponsable',
                            'fecha_actualizacion',
                            'isPublicPrivate',
                            'updated_at',
                        ]
                    );
                    $bar->advance(count($batch));
                    $batch = []; // Limpiar el lote
                }
            }

            // Insertar las filas restantes
            if (! empty($batch)) {
                IpsNoReps::upsert(
                    $batch,
                    ['codigo'],
                    [
                        'nombre',
                        'descripcion',
                        'habilitado',
                        'aplicacion',
                        'isStandardGEL',
                        'isStandardMSPS',
                        'telefono',
                        'gerente',
                        'regimen',
                        'codDepartamento',
                        'departamento',
                        'codMunicipio',
                        'municipio',
                        'tipoIPS',
                        'nivelAtencion',
                        'nit',
                        'valorRegistro',
                        'usuarioResponsable',
                        'fecha_actualizacion',
                        'isPublicPrivate',
                        'updated_at',
                    ]
                );
                $bar->advance(count($batch));
            }

            $bar->finish();
            $this->command->info("\nSeeding completado.");
        } catch (Exception $e) {
            $this->command->error('Error al leer el Excel: '.$e->getMessage());
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            $this->command->error('Error al procesar la hoja: '.$e->getMessage());
        }
    }
}

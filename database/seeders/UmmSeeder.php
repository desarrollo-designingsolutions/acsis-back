<?php

namespace Database\Seeders;

use App\Imports\Seeders\UmmImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UmmSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $filesPath = [
                database_path('db/25-TablaReferencia_UMM__1.xlsx'),
            ];

            foreach ($filesPath as $key => $path) {
                // Determine maxRecords (e.g., 10 for limited, null for all)
                $maxRecords = null; // Change to null to process all records

                // Count total rows for progress bar if processing all records
                $totalRows = $maxRecords ?? $this->countExcelRows($path);
                $this->command->info('Procesando hasta '.($maxRecords ?? 'todos los').' registros...');

                // Initialize progress bar
                $bar = $this->command->getOutput()->createProgressBar($totalRows);
                $bar->start();

                // Instantiate import with maxRecords
                $import = new UmmImport($maxRecords);
                $import->withProgressBar($bar);

                Excel::import($import, $path, null, \Maatwebsite\Excel\Excel::XLSX, [
                    'sheet' => 'Table',
                ]);

                $bar->finish();
                $this->command->info("\nSeeding completed.");
            }
        } catch (\Exception $e) {
            $this->command->error('Error importing Excel: '.$e->getMessage());
        }
    }

    /**
     * Count the number of rows in the Excel file's "Table" sheet, excluding header.
     */
    protected function countExcelRows($filePath): int
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getSheetByName('Table');
        if (! $sheet) {
            throw new \Exception('No se encontró la hoja "Table" en el archivo Excel.');
        }

        return $sheet->getHighestRow() - 1; // Subtract 1 for header
    }
}

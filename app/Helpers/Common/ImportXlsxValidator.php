<?php

namespace App\Helpers\Common;

use App\Events\ProgressCircular;
use App\Helpers\Common\ErrorCollector;
use Illuminate\Support\Facades\Redis;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportXlsxValidator
{
    /**
     * Valida las columnas de un archivo XLSX.
     *
     * @param  string  $user_id
     * @param  string  $keyErrorRedis
     * @param  string  $filePath
     * @param  int     $expectedColumns
     * @param  string  $prefix
     * @return bool
     */

    protected static array $expectedHeaders = [
        'tipo de documento',
        'documento',
        'tipo de usuario',
        'fecha de nacimiento',
        'sexo',
        'pais de residencia',
        'municipio de residencia',
        'zona territorial de residencia',
        'incapacidad',
        'pais de origen',
        'primer nombre',
        'segundo nombre',
        'primer apellido',
        'segundo apellido',
    ];

    public static function validate(
        string $user_id,
        string $keyErrorRedis,
        string $filePath,
        int $expectedColumns = 5,
        string $prefix,
    ): bool {
        Redis::del($keyErrorRedis);

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, false, false, false);
        } catch (\Exception $e) {
            ErrorCollector::addError(
                $keyErrorRedis,
                'XLSX_ERROR_001',
                'R',
                null,
                basename($filePath),
                null,
                null,
                null,
                'No se pudo leer el archivo Excel. Verifique que el archivo esté bien formado.'
            );
            return false;
        }

        // ✅ Validar encabezados antes de continuar
        $headers = array_map(
            fn($value) => is_string($value) ? trim(mb_strtolower($value)) : '',
            $rows[0] ?? []
        );

        $expected = self::$expectedHeaders;
        $missingHeaders = array_diff($expected, $headers);
        $extraHeaders = array_diff($headers, $expected);

        if (!empty($missingHeaders) || !empty($extraHeaders)) {
            $errorMessage = '';

            if (!empty($missingHeaders)) {
                $errorMessage .= 'Faltan las siguientes columnas: ' . implode(', ', $missingHeaders) . '. ';
            }
            if (!empty($extraHeaders)) {
                $errorMessage .= 'Estas columnas no son reconocidas: ' . implode(', ', $extraHeaders) . '.';
            }

            ErrorCollector::addError(
                $keyErrorRedis,
                'XLSX_ERROR_003',
                'R',
                null,
                basename($filePath),
                1,
                implode(';', $headers),
                null,
                trim($errorMessage)
            );
            return false;
        }

        $totalLines = count($rows) - 1;
        $processedLines = 0;
        $hasError = false;

        foreach (array_slice($rows, 1) as $index => $row) {
            $rowNum = $index + 1;
            // Saltar filas vacías completamente
            if (count(array_filter($row, fn($value) => trim($value) !== '')) === 0) {
                continue;
            }

            // Normalizar el array a la longitud esperada
            $row = array_pad($row, $expectedColumns, null);

            // Si tiene más columnas de las esperadas, es un error
            if (count($row) > $expectedColumns) {
                ErrorCollector::addError(
                    $keyErrorRedis,
                    'XLSX_ERROR_002',
                    'R',
                    null,
                    basename($filePath),
                    $rowNum + 1,
                    implode(';', $row),
                    null,
                    "Se esperaban máximo {$expectedColumns} columnas, pero se encontraron " . count($row) . "."
                );
                $hasError = true;
            }

            $processedLines++;
            $progress = ($processedLines / $totalLines) * 100;
            ProgressCircular::dispatch("xlsx_import_progress_{$prefix}.{$user_id}", $progress);
        }

        return !$hasError;
    }
}

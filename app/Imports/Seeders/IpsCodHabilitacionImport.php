<?php

namespace App\Imports\Seeders;

use App\Models\IpsCodHabilitacion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class IpsCodHabilitacionImport implements ToCollection, WithChunkReading
{
    use Importable;

    protected $maxRecords; // Maximum number of records to process (null for all)

    protected $processedRecords = 0; // Counter for processed records

    protected $progressBar; // Store the progress bar instance

    /**
     * Constructor to set maxRecords.
     *
     * @param  int|null  $maxRecords  Number of records to process, or null for all
     */
    public function __construct(?int $maxRecords = null)
    {
        $this->maxRecords = $maxRecords;
    }

    /**
     * Set the progress bar instance.
     */
    public function withProgressBar($progressBar)
    {
        $this->progressBar = $progressBar;

        return $this;
    }

    public function collection(Collection $rows)
    {
        $batch = [];

        // Skip the first row if it contains headers
        $rows = $rows->skip(1);

        foreach ($rows as $row) {
            // Stop if we've processed the maximum number of records (if set)
            if ($this->maxRecords !== null && $this->processedRecords >= $this->maxRecords) {
                break;
            }

            if (empty($row[1])) {
                continue; // Skip rows with missing 'codigo'
            }

            $batch[] = [
                'codigo' => $row[1] ?? null,
                'nombre' => $row[2] ?? null,
                'descripcion' => $row[3] ?? null,
                'habilitado' => $row[4] ?? null,
                'aplicacion' => $row[5] ?? null,
                'isStandardGEL' => $row[6] ?? null,
                'isStandardMSPS' => $row[7] ?? null,
                'tipoIDPrestador' => $row[8] ?? null,
                'nroIDPrestador' => $row[9] ?? null,
                'codigoPrestador' => $row[10] ?? null,
                'codMpioSede' => $row[11] ?? null,
                'nombreMpioSede' => $row[12] ?? null,
                'nombreDptoSede' => $row[13] ?? null,
                'clasePrestador' => $row[14] ?? null,
                'nomClasePrestador' => $row[15] ?? null,
                'extra_IX' => $row[16] ?? null,
                'extra_X' => $row[17] ?? null,
                'valorRegistro' => $row[18] ?? null,
                'usuarioResponsable' => $row[19] ?? null,
                'fecha_actualizacion' => $row[20] ?? null,
                'isPublicPrivate' => $row[21] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $this->processedRecords++;

            // Advance the progress bar
            if ($this->progressBar) {
                $this->progressBar->advance();
            }
        }

        if (! empty($batch)) {
            IpsCodHabilitacion::upsert(
                $batch,
                ['codigo'],
                [
                    'nombre',
                    'descripcion',
                    'habilitado',
                    'aplicacion',
                    'isStandardGEL',
                    'isStandardMSPS',
                    'tipoIDPrestador',
                    'nroIDPrestador',
                    'codigoPrestador',
                    'codMpioSede',
                    'nombreMpioSede',
                    'nombreDptoSede',
                    'clasePrestador',
                    'nomClasePrestador',
                    'extra_IX',
                    'extra_X',
                    'valorRegistro',
                    'usuarioResponsable',
                    'fecha_actualizacion',
                    'isPublicPrivate',
                    'updated_at',
                ]
            );
        }
    }

    public function chunkSize(): int
    {
        return 500; // Process 500 rows at a time
    }
}

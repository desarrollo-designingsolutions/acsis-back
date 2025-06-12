<?php

namespace App\Imports\Seeders;

use App\Models\Umm;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UmmImport implements ToCollection, WithChunkReading
{
    use Importable;

    private $isFirstChunk = true;

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
        if ($this->isFirstChunk) {
            $rows = $rows->skip(1);
            $this->isFirstChunk = false;
        }

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
                'extra_I' => $row[8] ?? null,
                'extra_II' => $row[9] ?? null,
                'extra_III' => $row[10] ?? null,
                'extra_IV' => $row[11] ?? null,
                'extra_V' => $row[12] ?? null,
                'extra_VI' => $row[13] ?? null,
                'extra_VII' => $row[14] ?? null,
                'extra_VIII' => $row[15] ?? null,
                'extra_IX' => $row[16] ?? null,
                'extra_X' => $row[17] ?? null,
                'valorRegistro' => $row[18] ?? null,
                'usuarioResponsable' => $row[19] ?? null,
                'fecha_Actualizacion' => $row[20] ?? null,
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
            Umm::upsert(
                $batch,
                ['codigo'],
                [
                    'nombre',
                    'descripcion',
                    'habilitado',
                    'aplicacion',
                    'isStandardGEL',
                    'isStandardMSPS',
                    'extra_I',
                    'extra_II',
                    'extra_III',
                    'extra_IV',
                    'extra_V',
                    'extra_VI',
                    'extra_VII',
                    'extra_VIII',
                    'extra_IX',
                    'extra_X',
                    'valorRegistro',
                    'usuarioResponsable',
                    'fecha_Actualizacion',
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

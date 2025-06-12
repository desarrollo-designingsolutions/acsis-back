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

            $data = IpsCodHabilitacion::updateOrCreate(
                ['codigo' => $row[1]],
                [
                    'nombre' => $row[2],
                    'descripcion' => $row[3],
                    'habilitado' => $row[4],
                    'aplicacion' => $row[5],
                    'isStandardGEL' => $row[6],
                    'isStandardMSPS' => $row[7],
                    'tipoIDPrestador' => $row[8],
                    'nroIDPrestador' => $row[9],
                    'codigoPrestador' => $row[10],
                    'codMpioSede' => $row[11],
                    'nombreMpioSede' => $row[12],
                    'nombreDptoSede' => $row[13],
                    'clasePrestador' => $row[14],
                    'nomClasePrestador' => $row[15],
                    'extra_IX' => $row[16],
                    'extra_X' => $row[17],
                    'valorRegistro' => $row[18],
                    'usuarioResponsable' => $row[19],
                    'fecha_actualizacion' => $row[20],
                    'isPublicPrivate' => $row[21],
                ]
            );

            $this->processedRecords++;

            // Advance the progress bar
            if ($this->progressBar) {
                $this->progressBar->advance();
            }
        }
    }

    public function chunkSize(): int
    {
        return 500; // Process 500 rows at a time
    }
}

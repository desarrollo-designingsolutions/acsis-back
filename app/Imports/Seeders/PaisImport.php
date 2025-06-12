<?php

namespace App\Imports\Seeders;

use App\Models\Pais;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PaisImport implements ToCollection, WithChunkReading
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

            $data = Pais::where('codigo', $row[1])->first();

            if (! $data) {
                $data = new Pais;
            }

            $data->codigo = $row[1];
            $data->nombre = $row[2];
            $data->descripcion = $row[3];
            $data->habilitado = $row[4];
            $data->aplicacion = $row[5];
            $data->isStandardGEL = $row[6];
            $data->isStandardMSPS = $row[7];
            $data->extra_I = $row[8];
            $data->extra_II = $row[9];
            $data->extra_III = $row[10];
            $data->extra_IV = $row[11];
            $data->extra_V = $row[12];
            $data->extra_VI = $row[13];
            $data->extra_VII = $row[14];
            $data->extra_VIII = $row[15];
            $data->extra_IX = $row[16];
            $data->extra_X = $row[17];
            $data->valorRegistro = $row[18];
            $data->usuarioResponsable = $row[19];
            $data->fecha_actualizacion = $row[20];
            $data->isPublicPrivate = $row[21];
            $data->save();

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

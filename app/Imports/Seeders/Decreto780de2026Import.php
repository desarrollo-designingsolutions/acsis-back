<?php

namespace App\Imports\Seeders;

use App\Models\Decreto780de2026;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class Decreto780de2026Import implements ToCollection, WithChunkReading
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

            $data = Decreto780de2026::updateOrCreate(
                ['codigo' => $row[0]],
                [
                    'descripcion' => $row[1],
                    'grupo' => $row[2],
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

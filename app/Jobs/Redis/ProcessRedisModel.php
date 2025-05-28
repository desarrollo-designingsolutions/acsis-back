<?php

namespace App\Jobs\Redis;

use App\Models\Company;
use App\Services\CacheService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;

class ProcessRedisModel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $modelClass;

    protected $channel;

    public function __construct($modelClass, $channel)
    {
        $this->modelClass = $modelClass;
        $this->channel = $channel;
    }

    public function handle(CacheService $cacheService): void
    {
        try {
            $model = new $this->modelClass;
            $table = $model->getTable();
            $lastRunKey = $cacheService->generateKey("{$table}:last_date_job_run", [], 'string');

            $hasCompany = Schema::hasColumn($table, 'company_id');

            // Crear una única clave de almacenamiento Redis para todo el modelo
            $mainCacheKey = $cacheService->generateKey("{$table}_table", [], 'string');
            Redis::del($mainCacheKey); // limpiar datos previos

            $totalRecords = 0;

            // Contar primero todos los registros que se van a procesar
            Company::select('id')->cursor()->each(function ($company) use ($hasCompany, &$totalRecords) {
                $query = $this->modelClass::query();
                if ($hasCompany) {
                    $query->where('company_id', $company->id);
                }

                $totalRecords += $query->count();
            });

            // Guardar el total en Redis para seguimiento
            if ($this->channel) {
                Redis::set("integer:progress_total:{$this->channel}", $totalRecords);
                Redis::set("integer:progress_processed:{$this->channel}", 0);
            }

            // Ahora procesar los registros y guardarlos
            Company::select('id')->cursor()->each(function ($company) use ($hasCompany) {
                $query = $this->modelClass::query();
                if ($hasCompany) {
                    $query->where('company_id', $company->id);
                }

                $query->chunkById(50, function ($elements) use (
                    $company
                ) {

                    ProcessRedisBatch::dispatch($company->id, $this->modelClass, $elements, $this->channel)->onQueue('batches');

                });
            });

            Redis::set($lastRunKey, Carbon::now());

            if ($this->channel) {
                Redis::del("integer:progress_total:{$this->channel}");
                Redis::del("integer:progress_processed:{$this->channel}");
            }
        } catch (\Throwable $e) {
            \Log::error("Error in ProcessRedisModel for {$this->modelClass}: ".$e->getMessage(), [
                'model' => $this->modelClass,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}

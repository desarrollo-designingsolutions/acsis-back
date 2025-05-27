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
            $table = (new $this->modelClass)->getTable();
            $lastRunKey = $cacheService->generateKey("{$table}:last_date_job_run", [], 'string');
            $lastRun = Redis::get($lastRunKey) ? Carbon::parse(Redis::get($lastRunKey)) : null;

            Company::select('id')->cursor()->each(function ($company) use ($lastRun, $cacheService, $table) {
                $query = $this->modelClass::query();

                if (Schema::hasColumn($table, 'company_id')) {
                    $query->where('company_id', $company->id);
                }
                if ($lastRun) {
                    $query->where('created_at', '>=', $lastRun);
                }

                // 🔢 Guardar el total de registros que se procesarán para este canal
                $total = $query->count();
                if ($this->channel) {
                    Redis::set("integer:progress_total:{$this->channel}", $total);
                    Redis::set("integer:progress_processed:{$this->channel}", 0);
                }

                $query->chunkById(50, function ($elements) use ($table, $company) {
                    ProcessRedisBatch::dispatch($company->id, $this->modelClass, $elements, $this->channel)->onQueue('batches');
                });
                gc_collect_cycles();
            });

            Redis::set($lastRunKey, Carbon::now());
        } catch (\Throwable $e) {
            \Log::error("Error in ProcessRedisModel for {$this->modelClass}: " . $e->getMessage(), [
                'model' => $this->modelClass,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}

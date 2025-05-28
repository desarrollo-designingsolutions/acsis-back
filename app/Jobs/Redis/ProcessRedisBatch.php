<?php

namespace App\Jobs\Redis;

use App\Events\ProgressCircular;
use App\Helpers\Constants;
use App\Models\Company;
use App\Services\CacheService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ProcessRedisBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $companyId;

    protected $modelClass;

    protected $elements;

    protected $channel; // nuevo atributo para emitir progreso

    public function __construct($companyId, $modelClass, $elements, $channel = null)
    {
        $this->companyId = $companyId;
        $this->modelClass = $modelClass;
        $this->elements = $elements;
        $this->channel = $channel;
    }

    public function handle(CacheService $cacheService): void
    {
        try {
            $table = (new $this->modelClass)->getTable();

            $mainCacheKey = $cacheService->generateKey("{$table}_table", [], 'string');

            foreach ($this->elements as $element) {
                $serviceData = $element->toArray();

                Redis::hset($mainCacheKey, $element->id, json_encode($serviceData));

                if ($this->channel) {
                    $processed = Redis::incr("integer:progress_processed:{$this->channel}");
                    $total = Redis::get("integer:progress_total:{$this->channel}") ?: 1;
                    $percentage = ($processed / $total) * 100;
                    ProgressCircular::dispatch($this->channel, $percentage);
                }
            }

            // foreach ($this->elements as $element) {
            //     $serviceData = $element->toArray();
            //     $request = [
            //         'company_id' => $this->companyId,
            //         'element_id' => $element->id,
            //     ];

            //     $cacheKey = $cacheService->generateKey("{$table}_table", $request, 'string');

            //     $cacheService->remember($cacheKey, function () use ($table) {
            //         $record = DB::table($table)->get();
            //         return $record ? (array) $record : false;
            //     }, Constants::REDIS_TTL);

            //     // $cacheKey = $cacheService->generateKey("{$table}:company_{$this->companyId}:cronjob", $request, 'hash');
            //     // Redis::hmset($cacheKey, $serviceData);

            //     // $cacheKey2 = $cacheService->generateKey("{$table}:company_{$this->companyId}:ids_set_cronjob", $request, 'set');
            //     // Redis::sadd($cacheKey2, $element->id);

            //     // 🔄 Progreso por elemento
            //     if ($this->channel) {
            //         $processed = Redis::incr("integer:progress_processed:{$this->channel}");
            //         $total = Redis::get("integer:progress_total:{$this->channel}") ?: 1;

            //         $percentage = ($processed / $total) * 100;

            //         ProgressCircular::dispatch($this->channel, $percentage);
            //     }
            // }
        } catch (\Throwable $e) {
            \Log::error("Error in ProcessRedisBatch for {$this->modelClass}, company {$this->companyId}: ".$e->getMessage(), [
                'company_id' => $this->companyId,
                'model' => $this->modelClass,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}

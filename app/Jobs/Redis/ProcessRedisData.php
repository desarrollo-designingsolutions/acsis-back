<?php

namespace App\Jobs\Redis;

use App\Services\CacheService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRedisData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $model;

    public $channel;

    public function __construct($model, $channel = null)
    {
        $this->model = $model;
        $this->channel = $channel;
    }

    public function handle(CacheService $cacheService): void
    {
        try {
            $models = $this->model;

            foreach ($models as $model) {
                logger('Processing model: '.$model);
                ProcessRedisModel::dispatch($model, $this->channel)->onQueue('models');
            }
        } catch (\Throwable $e) {
            \Log::error('Error in ProcessRedisData: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Jobs\Redis\ProcessRedisData;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CacheMetricsController extends Controller
{
    use HttpResponseTrait;

    /**
     * Obtiene métricas de caché desde Redis y las agrupa por intervalos de tiempo.
     *
     * @param  int  $limit  Número máximo de intervalos a devolver
     * @return array Métricas agrupadas por intervalo de tiempo
     */
    private function getMetricsFromRedis(int $limit = 30): array
    {
        $rawMetrics = Redis::lrange('list:cache_metrics', 0, -1);
        $metrics = array_map(fn ($item) => json_decode($item, true), $rawMetrics);

        $grouped = [];
        foreach ($metrics as $metric) {
            $timestamp = strtotime($metric['created_at']);
            $timeSlot = date('Y-m-d H:i:s', floor($timestamp / 20) * 20); // Intervalo de 20 segundos
            $grouped[$timeSlot][$metric['source']][] = $metric['response_time'];
        }

        krsort($grouped);

        return array_slice($grouped, 0, $limit, true);
    }

    /**
     * Devuelve el porcentaje de aciertos del caché (hit rate) en intervalos de tiempo.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function hitRate(Request $request)
    {
        $interval = $request->input('interval', '20seconds');
        $limit = $request->input('limit', 30);

        if ($interval !== '20seconds') {
            return response()->json(['error' => 'Intervalo no soportado'], 400);
        }

        $groupedMetrics = $this->getMetricsFromRedis($limit);

        $labels = [];
        $hitRates = [];

        foreach ($groupedMetrics as $timeSlot => $sources) {
            $redisHits = count($sources['redis'] ?? []);
            $dbHits = count($sources['database'] ?? []);
            $total = $redisHits + $dbHits;
            $labels[] = $timeSlot;
            $hitRates[] = $total > 0 ? round(($redisHits / $total) * 100, 2) : 0;
        }

        $labels = array_reverse($labels);
        $hitRates = array_reverse($hitRates);

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Cache Hit Rate (%)',
                    'data' => $hitRates,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 3,
                    'pointBackgroundColor' => 'rgba(75, 192, 192, 1)',
                ],
            ],
        ]);
    }

    /**
     * Devuelve el tiempo de respuesta promedio para Redis y la base de datos.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseTime(Request $request)
    {
        $interval = $request->input('interval', '20seconds');
        $limit = $request->input('limit', 30);

        if ($interval !== '20seconds') {
            return response()->json(['error' => 'Intervalo no soportado'], 400);
        }

        $groupedMetrics = $this->getMetricsFromRedis($limit);

        $labels = [];
        $redisData = [];
        $dbData = [];

        foreach ($groupedMetrics as $timeSlot => $sources) {
            $redisTimes = $sources['redis'] ?? [];
            $dbTimes = $sources['database'] ?? [];
            $redisAvg = ! empty($redisTimes) ? array_sum($redisTimes) / count($redisTimes) : 0;
            $dbAvg = ! empty($dbTimes) ? array_sum($dbTimes) / count($dbTimes) : 0;

            $labels[] = $timeSlot;
            $redisData[] = $redisAvg;
            $dbData[] = $dbAvg;
        }

        $labels = array_reverse($labels);
        $redisData = array_reverse($redisData);
        $dbData = array_reverse($dbData);

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Redis Response Time (ms)',
                    'data' => $redisData,
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'fill' => false,
                    'tension' => 0.4,
                    'pointRadius' => 3,
                    'pointBackgroundColor' => 'rgba(54, 162, 235, 1)',
                ],
                [
                    'label' => 'Database Response Time (ms)',
                    'data' => $dbData,
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'fill' => false,
                    'tension' => 0.4,
                    'pointRadius' => 3,
                    'pointBackgroundColor' => 'rgba(255, 99, 132, 1)',
                ],
            ],
        ]);
    }

    /**
     * Devuelve el volumen de peticiones a Redis y la base de datos.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestVolume(Request $request)
    {
        $interval = $request->input('interval', '20seconds');
        $limit = $request->input('limit', 30);

        if ($interval !== '20seconds') {
            return response()->json(['error' => 'Intervalo no soportado'], 400);
        }

        $groupedMetrics = $this->getMetricsFromRedis($limit);

        $labels = [];
        $redisRequests = [];
        $dbRequests = [];

        foreach ($groupedMetrics as $timeSlot => $sources) {
            $redisCount = count($sources['redis'] ?? []);
            $dbCount = count($sources['database'] ?? []);
            $labels[] = $timeSlot;
            $redisRequests[] = $redisCount;
            $dbRequests[] = $dbCount;
        }

        $labels = array_reverse($labels);
        $redisRequests = array_reverse($redisRequests);
        $dbRequests = array_reverse($dbRequests);

        $maxRequests = max(max(array_filter($redisRequests, fn ($v) => $v > 0)), max(array_filter($dbRequests, fn ($v) => $v > 0)));
        $maxRequests = $maxRequests > 0 ? ceil($maxRequests * 1.1) : 10;

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Redis Requests',
                    'data' => $redisRequests,
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 3,
                    'pointBackgroundColor' => 'rgba(54, 162, 235, 1)',
                ],
                [
                    'label' => 'Database Requests',
                    'data' => $dbRequests,
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 3,
                    'pointBackgroundColor' => 'rgba(255, 99, 132, 1)',
                ],
            ],
            'max_requests' => $maxRequests,
        ]);
    }

    /**
     * Devuelve información general y estadísticas de Redis para el dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function redisStats(Request $request)
    {
        try {
            $redis = Redis::connection();

            // Obtener todas las claves con el prefijo configurado
            $keys = $redis->keys('*');

            // Contadores por tipo
            $typeCounts = [
                'string' => 0,
                'set' => 0,
                'list' => 0,
                'hash' => 0,
                'zset' => 0,
            ];

            // Procesar cada clave para determinar su tipo
            foreach ($keys as $key) {
                $cleanKey = str_replace(config('database.redis.options.prefix', 'laravel_database_'), '', $key);

                // Omitir la clave 'list:cache_metrics'
                if ($cleanKey === 'list:cache_metrics') {
                    continue;
                }

                $type = $this->getTypeFromKey($cleanKey);
                $typeCounts[$type]++;
            }

            // Obtener información general de Redis con el comando INFO
            $info = $redis->info();

            // Extraer métricas útiles
            $stats = [
                'total_keys' => count($keys), // Total de claves
                'keys_by_type' => $typeCounts, // Claves por tipo
                'memory_used' => [
                    'human' => $info['Memory']['used_memory_human'] ?? 'N/A',
                    'bytes' => $info['Memory']['used_memory'] ?? 0,
                ],
                'memory_peak' => [
                    'human' => $info['Memory']['used_memory_peak_human'] ?? 'N/A',
                    'bytes' => $info['Memory']['used_memory_peak'] ?? 0,
                ],
                'uptime' => [
                    'seconds' => $info['Server']['uptime_in_seconds'] ?? 0,
                    'days' => $info['Server']['uptime_in_days'] ?? 0,
                ],
                'clients_connected' => $info['Clients']['connected_clients'] ?? 0,
                'commands_processed' => $info['Stats']['total_commands_processed'] ?? 0,
                'hits' => $info['Stats']['keyspace_hits'] ?? 0,
                'misses' => $info['Stats']['keyspace_misses'] ?? 0,
            ];

            // Calcular el hit rate global si hay datos suficientes
            $totalAccesses = $stats['hits'] + $stats['misses'];
            $stats['hit_rate'] = $totalAccesses > 0 ? round(($stats['hits'] / $totalAccesses) * 100, 2) : 0;

            return response()->json([
                'code' => 200,
                'message' => 'Redis stats retrieved successfully',
                'data' => $stats,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Error retrieving Redis stats',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
            ]);
        }
    }

    /**
     * Determina el tipo de dato según el prefijo de la clave.
     *
     * @param  string  $key  Clave de caché
     * @return string Tipo de dato (string, set, list, hash, zset)
     */
    private function getTypeFromKey(string $key): string
    {
        if (strpos($key, 'set:') === 0) {
            return 'set';
        } elseif (strpos($key, 'list:') === 0) {
            return 'list';
        } elseif (strpos($key, 'hash:') === 0) {
            return 'hash';
        } elseif (strpos($key, 'zset:') === 0) {
            return 'zset';
        }

        return 'string';
    }

    /**
     * Recupera datos de Redis según el tipo de dato.
     *
     * @param  mixed  $redis  Instancia de conexión a Redis
     * @param  string  $key  Clave de caché
     * @param  string  $type  Tipo de dato
     * @return mixed Valor recuperado
     */
    private function getDataFromRedis($redis, string $key, string $type)
    {
        switch ($type) {
            case 'set':
                return $redis->smembers($key);
            case 'list':
                return $redis->lrange($key, 0, -1);
            case 'hash':
                return $redis->hgetall($key);
            case 'zset':
                return $redis->zrange($key, 0, -1, true);
            case 'string':
            default:
                return $redis->get($key);
        }
    }

    /**
     * Procesa el valor según el tipo (deserializa strings si es necesario).
     *
     * @param  mixed  $value  Valor crudo de Redis
     * @param  string  $type  Tipo de dato
     * @return mixed Valor procesado
     */
    private function processValue($value, string $type)
    {
        if ($value === null || $value === false) {
            return null;
        }

        if ($type === 'string') {
            $unserialized = @unserialize($value);
            if ($unserialized !== false) {
                return $unserialized;
            }
            $decodedJson = json_decode($value, true);

            return $decodedJson !== null ? $decodedJson : $value;
        }

        return $value;
    }

    public function getTables(Request $request)
    {
        return $this->execute(function () use ($request) {
            $filter = $request->input('filter.inputGeneral');
            $sort = $request->input('sort', 'title'); // default sort by 'title'

            $tables = DB::select('SHOW TABLES');

            $bd = env('DB_DATABASE'); // Ajusta si tu base de datos no es "acsis"
            $tableKey = 'Tables_in_'.$bd; // Ajusta si tu base de datos no es "acsis"
            $results = [];

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;

                // Filtrar si hay filtro activo y no coincide
                if ($filter && stripos($tableName, $filter) === false) {
                    continue;
                }

                try {
                    $count = DB::table($tableName)->count();
                } catch (\Exception $e) {
                    $count = 'Error: '.$e->getMessage();
                }

                $results[] = [
                    'title' => $tableName,
                    'count_records' => $count,
                ];
            }

            // Ordenar por título (alfabéticamente ascendente o descendente)
            $sortDirection = strtolower($sort) === 'desc' ? SORT_DESC : SORT_ASC;
            usort($results, function ($a, $b) use ($sortDirection) {
                return $sortDirection === SORT_ASC
                    ? strcmp($a['title'], $b['title'])
                    : strcmp($b['title'], $a['title']);
            });

            return [
                'code' => 200,
                'total' => count($results),
                'tables' => $results,
            ];
        });
    }

    public function synchronizeTables(Request $request)
    {
        return $this->execute(function () use ($request) {
            $table = $request->input('table_name');

            // Debe devolver algo como: App\Models\Invoice
            $model = getModelByTableName($table);

            if (! $model) {
                return [
                    'code' => 404,
                    'message' => 'Modelo no encontrado para la tabla: '.$table,
                ];
            }

            // Ahora construyes el array como en tu ejemplo
            $models = [$model];

            // Despachar con array de clases
            ProcessRedisData::dispatch($models, "synchronize_table.{$table}");

            return [
                'code' => 200,
                'message' => 'Sincronización iniciada',
            ];
        });
    }
}

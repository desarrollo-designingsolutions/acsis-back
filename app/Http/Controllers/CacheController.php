<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CacheController extends Controller
{
    public function listCacheKeys(Request $request)
    {
        try {
            $redis = Redis::connection();
            $prefix = $request->input('prefix', '*');
            $keys = $redis->keys($prefix);
            $cacheData = [];

            foreach ($keys as $key) {
                $cachePrefix = config('database.redis.options.prefix', 'laravel_database_');
                $cleanKey = str_replace($cachePrefix, '', $key); // Quitar el prefijo global

                // Determinar el tipo de dato según el prefijo
                $type = $this->getTypeFromKey($cleanKey);
                $value = $this->getDataFromRedis($redis, $cleanKey, $type);

                // Procesar el valor según el tipo
                $decodedValue = $this->processValue($value, $type);

                $cacheData[] = [
                    'key' => $cleanKey,
                    'value' => $decodedValue,
                    'ttl' => $redis->ttl($cleanKey),
                    'type' => $type, // Opcional: incluir el tipo para mayor claridad
                ];
            }

            return response()->json([
                'code' => 200,
                'message' => 'Cache keys retrieved successfully',
                'prefix_used' => $prefix,
                'keys_found' => count($keys),
                'data' => $cacheData,
                'total' => count($cacheData),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Error retrieving cache keys',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
            ]);
        }
    }

    /**
     * Determina el tipo de dato según el prefijo de la clave.
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

        return 'string'; // Por defecto
    }

    /**
     * Recupera datos de Redis según el tipo.
     */
    private function getDataFromRedis($redis, string $key, string $type)
    {
        switch ($type) {
            case 'set':
                return $redis->smembers($key);
            case 'list':
                return $redis->lrange($key, 0, -1); // Todos los elementos
            case 'hash':
                return $redis->hgetall($key);
            case 'zset':
                return $redis->zrange($key, 0, -1, true); // Incluye puntajes
            case 'string':
            default:
                return $redis->get($key);
        }
    }

    /**
     * Procesa el valor según el tipo (deserializa strings si es necesario).
     */
    private function processValue($value, string $type)
    {
        if ($value === null || $value === false) {
            return null;
        }

        if ($type === 'string') {
            // Intentar deserializar primero (si falla, asumir JSON o texto plano)
            $unserialized = @unserialize($value);
            if ($unserialized !== false) {
                return $unserialized;
            }
            // Si no es serializable, intentar decodificar como JSON
            $decodedJson = json_decode($value, true);

            return $decodedJson !== null ? $decodedJson : $value;
        }

        return $value; // Para set, list, hash, zset, devolver tal cual
    }

    /**
     * Limpiar todo el caché, una clave específica o claves con un prefijo
     */
    public function clearAllCache(Request $request)
    {
        try {
            $redis = Redis::connection();

            $ping = $redis->ping();

            // Caso 1: Borrar una clave específica
            $specificKey = $request->input('key');
            if ($specificKey) {
                $deleted = $redis->del($specificKey);

                if ($deleted === 0) {
                    return response()->json([
                        'code' => 404,
                        'message' => "Cache key '{$specificKey}' not found. Use the full key or 'prefix' to delete by pattern.",
                    ]);
                }

                return response()->json([
                    'code' => 200,
                    'message' => "Cache key '{$specificKey}' cleared successfully",
                    'keys_deleted' => $deleted,
                ]);
            }

            // Caso 2: Borrar claves con un prefijo
            $prefix = $request->input('prefix');
            if ($prefix) {
                $keys = $redis->keys($prefix.'*');
                $keysDeleted = 0;

                if (! empty($keys)) {
                    $keysDeleted = $redis->del($keys);
                }

                return response()->json([
                    'code' => 200,
                    'message' => "Cache keys with prefix '{$prefix}' cleared successfully",
                    'keys_deleted' => $keysDeleted,
                ]);
            }

            // Caso 3: Borrar todo el caché
            $redis->flushdb();

            return response()->json([
                'code' => 200,
                'message' => 'All Redis cache cleared successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Error clearing Redis cache',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
            ]);
        }
    }
}

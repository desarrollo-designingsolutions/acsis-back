<?php

namespace App\Services\JsonValidation;

use App\Helpers\Constants;
use App\Services\CacheService;
use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JsonDataValidation
{
    private const TYPE_VALIDATION = "Información";

    protected $errors = [];

    protected $validatedData = [];

    protected $company_id = '';

    public function __construct(protected CacheService $cacheService)
    {
        // Aquí podrías inicializar cualquier otro servicio necesario
    }

    public function validate(array $jsonData, string $company_id): array
    {
        $this->company_id = $company_id;
        try {
            $this->errors = [];
            $this->validatedData = $jsonData; // Inicializar con el JSON original
            $rules = JsonDataValidationConfig::getValidationRules();
            // Log::info('Reglas de validación cargadas', ['rules' => $rules]);

            foreach ($rules as $fieldPath => $rule) {
                $this->validateField($jsonData, $fieldPath, $rule);
            }

            return [
                'isValid' => empty($this->errors),
                'message' => empty($this->errors) ? 'Validación de datos exitosa' : 'La validación de datos falló',
                'errors' => $this->errors,
                'validatedData' => $this->validatedData,
            ];
        } catch (\Exception $e) {
            Log::error('Error en la validación de datos del JSON: ' . $e->getMessage(), ['jsonData' => $jsonData]);

            return [
                'isValid' => false,
                'message' => 'Error durante la validación de datos',
                'errors' => ['Error interno del servidor'],
                'validatedData' => $jsonData,
            ];
        }
    }

    protected function validateField(array $jsonData, string $fieldPath, array $rule): void
    {
        // Separar el camino del campo
        $pathParts = explode('.*.', $fieldPath);

        if (count($pathParts) === 3) {
            // Manejo de campos en usuarios.*.<servicio>.*.<campo>
            $parentPath = $pathParts[0]; // usuarios
            $middlePath = $pathParts[1]; // servicios.<servicio>
            $childField = $pathParts[2]; // codPrestador, codProcedimiento, etc.
            $middleParts = explode('.', $middlePath); // ['servicios', '<servicio>']

            if (count($middleParts) !== 2 || $middleParts[0] !== 'servicios') {

                $this->errors[] = [
                    'type' => self::TYPE_VALIDATION,
                    'level' => 'Archivo de validación',
                    'key' => '',
                    'data' => '',
                    'message' => "Formato de regla inválido para {$fieldPath}: se esperaba usuarios.*.servicios.<servicio>.*.<campo>.",
                ];

                return;
            }

            $serviceType = $middleParts[1]; // consultas, procedimientos, medicamentos
            $parentValues = Arr::get($jsonData, $parentPath, []);

            if (! is_array($parentValues)) {

                $this->errors[] = [
                    'type' => self::TYPE_VALIDATION,
                    'level' => $parentPath,
                    'key' => $fieldPath,
                    'data' => "",
                    'message' => "Campo {$fieldPath}: Se esperaba un array en {$parentPath}.",
                ];

                Arr::set($this->validatedData, $parentPath, null);

                return;
            }

            foreach ($parentValues as $userIndex => $parentValue) {
                $serviceItems = Arr::get($parentValue, "{$middleParts[0]}.{$serviceType}", []);

                if (! is_array($serviceItems)) {
                    $this->errors[] = [
                        'type' => self::TYPE_VALIDATION,
                        'level' => "{$parentPath}[{$userIndex}].{$middlePath}",
                        'key' => $serviceType,
                        'data' => "",
                        'message' => "Campo {$parentPath}[{$userIndex}].{$middlePath}: Se esperaba un array en {$serviceType}.",
                    ];
                    Arr::set($this->validatedData, "{$parentPath}.{$userIndex}.{$middleParts[0]}.{$serviceType}", null);

                    return;
                }

                foreach ($serviceItems as $itemIndex => $itemValue) {
                    $value = Arr::get($itemValue, $childField);
                    // Log::info("Validando campo {$fieldPath}[{$userIndex}][{$itemIndex}]", ['value' => $value, 'rule' => $rule]);

                    if ($value === null) {

                        $this->errors[] = [
                            'type' => self::TYPE_VALIDATION,
                            'level' => "{$parentPath}[{$userIndex}].{$middlePath}[{$itemIndex}].{$childField}",
                            'key' => $childField,
                            'data' => $value,
                            'message' => "Campo {$parentPath}[{$userIndex}].{$middlePath}[{$itemIndex}].{$childField}: No se encontró el campo en el JSON.",
                        ];

                        Arr::set($this->validatedData, "{$parentPath}.{$userIndex}.{$middleParts[0]}.{$serviceType}.{$itemIndex}.{$childField}", null);
                        if ($rule['type'] === 'exists') {
                            Arr::set($this->validatedData, "{$parentPath}.{$userIndex}.{$middleParts[0]}.{$serviceType}.{$itemIndex}.{$childField}_data", null);
                        }

                        continue;
                    }

                    if ($rule['type'] === 'exists') {
                        $result = $this->existsInDatabase($value, $rule['table'], $rule['column'], $rule['select'] ?? ['id'], $rule["withCompanyId"]);
                        if ($result === false) {
                            $errorMessage = is_callable($rule['error_message'])
                                ? $rule['error_message']($rule, $value)
                                : $rule['error_message'];

                            $this->errors[] = [
                                'type' => self::TYPE_VALIDATION,
                                'level' => "{$parentPath}[{$userIndex}].{$middlePath}[{$itemIndex}].{$childField}",
                                'key' => "{$childField}",
                                'data' => $value,
                                'message' => "Campo {$parentPath}[{$userIndex}].{$middlePath}[{$itemIndex}].{$childField}: {$errorMessage}",
                            ];

                            Arr::set($this->validatedData, "{$parentPath}.{$userIndex}.{$middleParts[0]}.{$serviceType}.{$itemIndex}.{$childField}_data", null);
                        } else {
                            Arr::set($this->validatedData, "{$parentPath}.{$userIndex}.{$middleParts[0]}.{$serviceType}.{$itemIndex}.{$childField}_data", $result);
                        }
                    } else {
                        $isValid = $this->validateValue($value, $rule);
                        if (! $isValid) {
                            $errorMessage = is_callable($rule['error_message'])
                                ? $rule['error_message']($rule, $value)
                                : $rule['error_message'];

                            $this->errors[] = [
                                'type' => self::TYPE_VALIDATION,
                                'level' => "{$parentPath}[{$userIndex}].{$middlePath}[{$itemIndex}].{$childField}",
                                'key' => "{$childField}",
                                'data' => $value,
                                'message' => "Campo {$parentPath}[{$userIndex}].{$middlePath}[{$itemIndex}].{$childField}: {$errorMessage}",
                            ];

                            Arr::set($this->validatedData, "{$parentPath}.{$userIndex}.{$middleParts[0]}.{$serviceType}.{$itemIndex}.{$childField}", null);
                        }
                    }
                }
            }
        } elseif (count($pathParts) === 2) {
            // Manejo de campos en usuarios.*.<campo>
            $parentPath = $pathParts[0];
            $childField = $pathParts[1];
            $parentValues = Arr::get($jsonData, $parentPath, []);

            if (! is_array($parentValues)) {

                $this->errors[] = [
                    'type' => self::TYPE_VALIDATION,
                    'level' => $parentPath,
                    'key' => $fieldPath,
                    'data' => "",
                    'message' => "Campo {$fieldPath}: Se esperaba un array en {$parentPath}.",
                ];

                Arr::set($this->validatedData, $parentPath, null);

                return;
            }

            foreach ($parentValues as $index => $parentValue) {
                $value = Arr::get($parentValue, $childField);
                // Log::info("Validando campo {$fieldPath}[{$index}]", ['value' => $value, 'rule' => $rule]);

                if ($value === null) {

                    $this->errors[] = [
                        'type' => self::TYPE_VALIDATION,
                        'level' => "{$parentPath}[{$index}].{$childField}",
                        'key' => $childField,
                        'data' => $value,
                        'message' => "Campo {$parentPath}[{$index}].{$childField}: No se encontró el campo en el JSON.",
                    ];

                    Arr::set($this->validatedData, "{$parentPath}.{$index}.{$childField}", null);
                    if ($rule['type'] === 'exists') {
                        Arr::set($this->validatedData, "{$parentPath}.{$index}.{$childField}_data", null);
                    }

                    continue;
                }

                if ($rule['type'] === 'exists') {
                    $result = $this->existsInDatabase($value, $rule['table'], $rule['column'], $rule['select'] ?? ['id'], $rule["withCompanyId"]);
                    if ($result === false) {
                        $errorMessage = is_callable($rule['error_message'])
                            ? $rule['error_message']($rule, $value)
                            : $rule['error_message'];

                        $this->errors[] = [
                            'type' => self::TYPE_VALIDATION,
                            'level' => "{$parentPath}[{$index}].{$childField}",
                            'key' => $childField,
                            'data' => $value,
                            'message' => "Campo {$parentPath}[{$index}].{$childField}: {$errorMessage}",
                        ];

                        Arr::set($this->validatedData, "{$parentPath}.{$index}.{$childField}_data", null);
                    } else {
                        Arr::set($this->validatedData, "{$parentPath}.{$index}.{$childField}_data", $result);
                    }
                } else {
                    $isValid = $this->validateValue($value, $rule);
                    if (! $isValid) {
                        $errorMessage = is_callable($rule['error_message'])
                            ? $rule['error_message']($rule, $value)
                            : $rule['error_message'];

                        $this->errors[] = [
                            'type' => self::TYPE_VALIDATION,
                            'level' => "{$parentPath}[{$index}].{$childField}",
                            'key' => $childField,
                            'data' => $value,
                            'message' => "Campo {$parentPath}[{$index}].{$childField}: {$errorMessage}",
                        ];

                        Arr::set($this->validatedData, "{$parentPath}.{$index}.{$childField}", null);
                    }
                }
            }
        } else {
            // Manejo de campos no anidados
            $value = Arr::get($jsonData, $fieldPath);
            // Log::info("Validando campo {$fieldPath}", ['value' => $value, 'rule' => $rule]);

            if ($value === null) {

                $this->errors[] = [
                    'type' => self::TYPE_VALIDATION,
                    'level' => "/",
                    'key' => $fieldPath,
                    'data' => $value,
                    'message' => "Campo {$fieldPath}: No se encontró el campo en el JSON.",
                ];

                Arr::set($this->validatedData, $fieldPath, null);
                if ($rule['type'] === 'exists') {
                    Arr::set($this->validatedData, "{$fieldPath}_data", null);
                }

                return;
            }

            if ($rule['type'] === 'exists') {
                $result = $this->existsInDatabase($value, $rule['table'], $rule['column'], $rule['select'] ?? ['id'], $rule["withCompanyId"]);
                if ($result === false) {
                    $errorMessage = is_callable($rule['error_message'])
                        ? $rule['error_message']($rule, $value)
                        : $rule['error_message'];

                    $this->errors[] = [
                        'type' => self::TYPE_VALIDATION,
                        'level' => '/',
                        'key' => "{$fieldPath}",
                        'data' => $value,
                        'message' => "Campo {$fieldPath}: {$errorMessage}",
                    ];

                    Arr::set($this->validatedData, "{$fieldPath}_data", null);
                } else {
                    Arr::set($this->validatedData, "{$fieldPath}_data", $result);
                }
            } else {
                $isValid = $this->validateValue($value, $rule);
                if (! $isValid) {
                    $errorMessage = is_callable($rule['error_message'])
                        ? $rule['error_message']($rule, $value)
                        : $rule['error_message'];

                    $this->errors[] = [
                        'type' => self::TYPE_VALIDATION,
                        'level' => '/',
                        'key' => "{$fieldPath}",
                        'data' => $value,
                        'message' => "Campo {$fieldPath}: {$errorMessage}",
                    ];

                    Arr::set($this->validatedData, $fieldPath, null);
                }
            }
        }
    }

    protected function validateValue($value, array $rule): bool
    {
        switch ($rule['type']) {
            case 'exists':
                return $this->existsInDatabase($value, $rule['table'], $rule['column'], $rule['select'] ?? ['id'], $rule["withCompanyId"]) !== false;
            case 'in':
                return $this->validateIn($value, $rule['values']);
            case 'regex':
                return preg_match($rule['pattern'], $value) === 1;
            case 'date':
                return $this->validateDate($value);
            case 'numeric':
                return $this->validateNumeric($value);
            default:
                Log::warning("Tipo de validación desconocido: {$rule['type']}");

                return false;
        }
    }

    protected function existsInDatabase($value, string $table, string $column, array $select = ['id'], $withCompanyId)
    {
        $params = [
            'value' => $value,
            'table' => $table,
            'column' => $column,
            'select' => $select,
            'withCompanyId' => $withCompanyId,
        ];

        $cacheKey = $this->cacheService->generateKey("{$table}_existsInDatabase", $params, 'string');

        return $this->cacheService->remember($cacheKey, function () use ($table, $column, $value, $select, $withCompanyId) {
            if ($withCompanyId) {
                $query = DB::table($table)->where($column, $value)->where("company_id", $this->company_id)->whereNull("deleted_at");
            } else {
                $query = DB::table($table)->where($column, $value)->whereNull("deleted_at");
            }
            $record = $query->select($select)->first();

            return $record ? (array) $record : false;
        }, Constants::REDIS_TTL);
    }

    protected function validateIn($value, array $allowedValues): bool
    {
        return in_array($value, $allowedValues, true);
    }

    protected function validateDate($value): bool
    {
        try {
            new DateTime($value);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function validateNumeric($value): bool
    {
        // Verifica si el valor es numérico (entero o decimal)
        return is_numeric($value);
    }
}

<?php

namespace App\Imports\Patient;

use App\Events\ModalError;
use App\Events\ProgressCircular;
use App\Exports\Patient\PatientExcelErrorsValidationExport;
use App\Helpers\Constants;
use App\Jobs\BrevoProcessSendEmail;
use App\Jobs\Glosa\ProcessGlosasServiceJob;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\BellNotification;
use App\Services\CacheService;
use Illuminate\Contracts\Queue\ShouldQueue;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Facades\Excel;


class PatientMasiveImport implements ShouldQueue, SkipsOnFailure, ToModel, WithChunkReading, WithEvents, WithHeadingRow
{
    use Importable, SkipsFailures;

    public $services_id;
    private $key_redis_project;
    private $cacheService;

    public function __construct(
        protected $user_id,
        protected $company_id,
        protected $tipoIdPisis,
        protected $ripsTipoUsuarioVersion2,
        protected $sexos,
        protected $paises,
        protected $municipios,
        protected $zonaVersion2,
    ) {
        $this->cacheService = new CacheService();
        $this->key_redis_project = env('KEY_REDIS_PROJECT');
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                // Limpiar errores
                Redis::del("paginate:patients_import_errors_{$this->user_id}");

                // Obtener total de filas (ajusta si hay encabezados)
                $totalRows = $event->getReader()->getTotalRows()['Worksheet'];
                $totalRows = max($totalRows, 1) - 1;

                Redis::set("integer:patients_import_total_{$this->user_id}", $totalRows);
                Redis::set("integer:patients_import_processed_{$this->user_id}", 0);
            },
            AfterImport::class => function (AfterImport $event) {

                // Limpiar cache de Redis de los pacientes
                $this->cacheService->clearByPrefix($this->key_redis_project . 'string:patients*');

                // Limpiar cache al finalizar
                Redis::del("integer:patients_import_total_{$this->user_id}");
                Redis::del("integer:patients_import_processed_{$this->user_id}");


                // Recuperar y mostrar los errores almacenados en Redis
                $errorListKey = "paginate:patients_import_errors_{$this->user_id}";
                $errors = Redis::lrange($errorListKey, 0, -1); // Obtener todos los elementos de la lista
                $errorsFormatted = [];

                if (! empty($errors)) {
                    // logger('Errores encontrados durante la importación:');
                    foreach ($errors as $index => $errorJson) {
                        $errorsFormatted[] = json_decode($errorJson, true); // Decodificar el JSON
                        // logger("Error #" . ($index + 1) . ": " . json_encode($errorData));
                    }
                } else {
                    // logger('No se encontraron errores durante la importación.');
                }

                // Convert array to JSON
                $routeJson = null;
                if (count($errorsFormatted) > 0) {
                    $nameFile = 'error_' . $this->user_id . '.json';
                    $routeJson = 'companies/company_' . $this->company_id . '/patients/errors/' . $nameFile; // Ruta donde se guardará la carpeta
                    Storage::disk(Constants::DISK_FILES)->put($routeJson, json_encode($errorsFormatted, JSON_PRETTY_PRINT));
                }

                // Emitir errores al front
                ModalError::dispatch("patientModalErrors.{$this->user_id}", $routeJson);

                // Enviar notificación al usuario
                $title = 'Importación de pacientes';
                $subtitle = 'La importación de pacientes ha finalizado sin novedad.';
                if (count($errorsFormatted) > 0) {
                    $subtitle = 'La importación de pacientes ha finalizado con las siguientes novedades:';
                }

                $this->sendNotification(
                    $this->user_id,
                    [
                        'title' => $title,
                        'subtitle' => $subtitle,
                        'patients_import' => $errorsFormatted,
                    ]
                );

                $this->cacheService->clearByPrefix($this->key_redis_project . 'string:patients*');
            },
        ];
    }

    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            // Incrementar contador y calcular progreso
            $processed = Redis::incrby("integer:patients_import_processed_{$this->user_id}", 1);
            $total = Redis::get("integer:patients_import_total_{$this->user_id}") ?: 1;
            $progress = ($processed / $total) * 100;

            $data = [
                'tipo_id_pisi_id' => $row['tipo_de_documento'],
                'document' => $row['documento'],
                'rips_tipo_usuario_version2_id' => $row['tipo_de_usuario'],
                'birth_date' => $this->castExcelDate($row['fecha_de_nacimiento']),
                'sexo_id' => $row['sexo'],
                'pais_residency_id' => $row['pais_de_residencia'],
                'municipio_residency_id' => $row['municipio_de_residencia'],
                'zona_version2_id' => $row['zona_territorial_de_residencia'],
                'incapacity' => $row['incapacidad'],
                'pais_origin_id' => $row['pais_de_origen'],
                'first_name' => $row['primer_nombre'],
                'second_name' => $row['segundo_nombre'],
                'first_surname' => $row['primer_apellido'],
                'second_surname' => $row['segundo_apellido'],
                'company_id' => $this->company_id,
            ];

            // Validar los datos manualmente
            if ($this->validations($row, $processed, $data)) {
                // Emitir evento de progreso
                ProgressCircular::dispatch("patient.{$this->user_id}", $progress);

                return null; // Si hay errores, omitir esta fila
            }

            $incapacity = $row['incapacidad'] === 'SÍ' ? 1 : 0;
            $data['incapacity'] = $incapacity;

            Patient::create(attributes: $data);

            // Emitir evento de progreso
            ProgressCircular::dispatch("patient.{$this->user_id}", $progress);

            return null;
        });
    }

    public function chunkSize(): int
    {
        return Constants::CHUNKSIZE;
    }

    public function validations($row, $processed, $data)
    {
        $error = false;

        // Guardar los errores en Redis como una lista
        $result = $this->tipoIdPisis($row['tipo_de_documento'], 'id');
        if ($result == null) { // Usar === para comparación estricta
            $this->pushError($processed, '1', $row['tipo_de_documento'], $data, 'El ID de Tipo de Documento no existe en la base de datos.');
            $error = true; // O lanza una excepción, o haz algo para detener el flujo
        }

        // Validación: document
        if (empty(trim($row['documento']))) {
            $this->pushError($processed, '2', $row['documento'], $data, 'El documento es obligatorio.');
            $error = true;
        }

        if (strlen($row['documento']) < 4 || strlen($row['documento']) > 20) {
            $this->pushError($processed, '2', $row['documento'], $data, 'El documento debe tener entre 4 a 20 caracteres.');
            $error = true;
        }

        // Validación: rips_tipo_usuario_version2_id
        if (!$this->ripsTipoUsuario($row['tipo_de_usuario'], 'id')) {
            $this->pushError($processed, '3', $row['tipo_de_usuario'], $data, 'Tipo de usuario no válido.');
            $error = true;
        }


        // Validación: birth_date (fecha válida)
        $tipoId = $row['tipo_de_documento'];
        $birthDate = $this->castExcelDate($row['fecha_de_nacimiento']);
        $paisOriginId = $row['pais_de_origen'];
        $paisResidencyId = $row['pais_de_residencia'];

        if ($this->isValidDate($birthDate)) {
            $birth = new \DateTime($birthDate);
            $today = new \DateTime();
            $age = $today->diff($birth)->y;

            $tipoIdCode = $this->documentTypesMap[$tipoId] ?? null;
            $originCode = $this->nacionalitiesMap[$paisOriginId] ?? null;
            $residencyCode = $this->nacionalitiesMap[$paisResidencyId] ?? null;

            $allowedForeignTransientDocs = ['CE', 'CD', 'PA', 'SC'];

            // Mayores de 18 colombianos deben usar CC
            if ($age >= 18 && $originCode === 'CO' && $tipoIdCode !== 'CC') {
                $this->pushError($processed, '1', $tipoId, $data, 'Mayores de edad colombianos deben usar la Cédula de Ciudadanía (CC).');
                $error = true;
            }

            // TI → entre 7 y 17 años
            if ($tipoIdCode === 'TI' && ($age < 7 || $age > 17)) {
                $this->pushError($processed, '1', $tipoId, $data, 'La Tarjeta de Identidad (TI) es válida entre 7 y 17 años.');
                $error = true;
            }

            // RC y CN → menores de 7 años
            if (in_array($tipoIdCode, ['RC', 'CN']) && $age >= 7) {
                $this->pushError($processed, '1', $tipoId, $data, 'El documento RC o CN es solo para menores de 7 años.');
                $error = true;
            }

            // CN → opcionalmente menores de 3 años
            if ($tipoIdCode === 'CN' && $age > 3) {
                $this->pushError($processed, '1', $tipoId, $data, 'El documento CN es recomendado solo para menores de hasta 3 años.');
                $error = true;
            }

            // Venezolanos deben usar PE
            if ($originCode === 'VE' && $tipoIdCode !== 'PE') {
                $this->pushError($processed, '1', $tipoId, $data, 'Los migrantes venezolanos deben identificarse con el Permiso Especial de Permanencia (PE).');
                $error = true;
            }

            // Extranjeros de paso deben usar CE, CD, PA o SC
            $isForeigner = $originCode !== 'CO';
            $isTransient = $residencyCode === 'CO';
            if ($isForeigner && $isTransient && !in_array($tipoIdCode, $allowedForeignTransientDocs)) {
                $this->pushError($processed, '1', $tipoId, $data, 'Los extranjeros de paso deben usar CE, CD, PA o SC.');
                $error = true;
            }
        } else {
            $this->pushError($processed, '4', $birthDate, $data, 'La fecha no es válida.');
            $error = true;
        }

        // Validación: sexo_id
        if (!$this->sexos($row['sexo'], 'id')) {
            $this->pushError($processed, '5', $row['sexo'], $data, 'Sexo no válido.');
            $error = true;
        }

        // Validación: pais_residency_id
        if (!$this->paises($row['pais_de_residencia'], 'id')) {
            $this->pushError($processed, '6', $row['pais_de_residencia'], $data, 'País de residencia no válido.');
            $error = true;
        }

        // Validación: municipio_residency_id
        if (!empty($row['municipio_de_residencia'])) {
            if (!$this->municipios($row['municipio_de_residencia'], 'id')) {
                $this->pushError($processed, '7', $row['municipio_de_residencia'], $data, 'Municipio de residencia no válido.');
                $error = true;
            }
        }

        // Validación: zona_version2_id
        if (!empty($row['zona_territorial_de_residencia'])) {
            if (!empty($row['zona_territorial_de_residencia']) && !$this->zonas($row['zona_territorial_de_residencia'], 'id')) {
                $this->pushError($processed, '8', $row['zona_territorial_de_residencia'], $data, 'Zona no válida.');
                $error = true;
            }
        }

        // Validación: incapacity (solo SI o NO)
        if (!empty($row['incapacidad']) && !in_array(strtoupper(trim($row['incapacidad'])), ['SÍ', 'NO'])) {
            $this->pushError($processed, '9', $row['incapacidad'], $data, 'El campo incapacidad solo acepta SÍ o NO.');
            $error = true;
        }

        // Validación: pais_origin_id
        if (!$this->paises($row['pais_de_origen'], 'id')) {
            $this->pushError($processed, '10', $row['pais_de_origen'], $data, 'País de origen no válido.');
            $error = true;
        }

        // Validación: first_name
        if (empty(trim($row['primer_nombre']))) {
            $this->pushError($processed, '11', $row['primer_nombre'], $data, 'El primer nombre es obligatorio.');
            $error = true;
        }

        if (strlen($row['primer_nombre']) > 255) {
            $this->pushError($processed, '11', $row['primer_nombre'], $data, 'El primer nombre excede los 255 caracteres.');
            $error = true;
        }

        // Validación: second_name
        if (!empty($row['segundo_nombre']) && strlen($row['segundo_nombre']) > 255) {
            $this->pushError($processed, '12', $row['segundo_nombre'], $data, 'El segundo nombre excede los 255 caracteres.');
            $error = true;
        }

        // Validación: first_surname
        if (empty(trim($row['primer_apellido']))) {
            $this->pushError($processed, '13', $row['primer_apellido'], $data, 'El primer apellido es obligatorio.');
            $error = true;
        }

        if (strlen($row['primer_apellido']) > 255) {
            $this->pushError($processed, '13', $row['primer_apellido'], $data, 'El primer apellido excede los 255 caracteres.');
            $error = true;
        }

        // Validación: second_surname
        if (!empty($row['segundo_apellido']) && strlen($row['segundo_apellido']) > 255) {
            $this->pushError($processed, '14', $row['segundo_apellido'], $data, 'El segundo apellido excede los 255 caracteres.');
            $error = true;
        }

        return $error; // Omitir esta fila
    }

    private function castExcelDate($value): ?string
    {
        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        if ($this->isValidDate($value)) {
            return (new \DateTime($value))->format('Y-m-d');
        }

        return $value;
    }

    protected array $nacionalitiesMap = [
        '0196d975-d025-736a-8d98-1caaf180d672' => 'CO', // Colombia
        '0196d975-d249-72ed-a011-682197c69476' => 'VE', // Venezuela
    ];

    protected array $documentTypesMap = [
        '0196d975-cf42-7380-981e-998565b9509c' => 'AS',
        '0196d975-cf46-729a-bfe0-f7c9cf7ffe2f' => 'CC',
        '0196d975-cf48-7004-80fa-79f0d63f3cea' => 'CD',
        '0196d975-cf4b-717f-ae50-fabe2296153a' => 'CE',
        '0196d975-cf4d-70b0-938b-0cd6e930e505' => 'CN',
        '0196d975-cf50-71eb-9de0-95b900a286c0' => 'DE',
        '0196d975-cf53-7160-a813-5b4ed76b6998' => 'MS',
        '0196d975-cf55-7299-b71d-fc3ec062063d' => 'NI',
        '0196d975-cf58-7108-9cbc-c4014bb00974' => 'NV',
        '0196d975-cf5a-722e-b87d-88f60de540ad' => 'PA',
        '0196d975-cf5d-733d-aace-7754f3adc299' => 'PE',
        '0196d975-cf5f-7353-bd08-bb5662ce3b56' => 'PT',
        '0196d975-cf62-71d5-bb19-0c6aee438915' => 'RC',
        '0196d975-cf64-7343-8af0-6ec9608209ee' => 'SC',
        '0196d975-cf67-72d0-8a94-723d498bbb21' => 'SI',
        '0196d975-cf69-70cf-b75f-b3378e34eea4' => 'TI',
    ];

    protected function pushError($rowIndex, $columnIndex, $value, $data, $message)
    {
        Redis::rpush("paginate:patients_import_errors_{$this->user_id}", json_encode([
            'column' => $columnIndex,
            'row' => $rowIndex,
            'value' => $value,
            'data' => $data,
            'errors' => $message,
        ]));
    }

    public function tipoIdPisis($value, $field)
    {
        $redisData = $this->tipoIdPisis;

        $cache = $redisData;

        $data = $cache->first(function ($item) use ($value, $field) {
            $match = isset($item[$field]) && strtoupper($item[$field]) === strtoupper($value);
            return $match;
        });

        return $data;
    }

    public function ripsTipoUsuario($value, $field)
    {
        $redisData = $this->ripsTipoUsuarioVersion2;

        $cache = $redisData;

        $data = $cache->first(function ($item) use ($value, $field) {
            $match = isset($item[$field]) && strtoupper($item[$field]) === strtoupper($value);
            return $match;
        });

        return $data;
    }

    public function sexos($value, $field)
    {
        $redisData = $this->sexos;

        $cache = $redisData;

        $data = $cache->first(function ($item) use ($value, $field) {
            $match = isset($item[$field]) && strtoupper($item[$field]) === strtoupper($value);
            return $match;
        });

        return $data;
    }

    public function paises($value, $field)
    {
        $redisData = $this->paises;

        $cache = $redisData;

        $data = $cache->first(function ($item) use ($value, $field) {
            $match = isset($item[$field]) && strtoupper($item[$field]) === strtoupper($value);
            return $match;
        });

        return $data;
    }

    public function municipios($value, $field)
    {
        $redisData = $this->municipios;

        $cache = $redisData;

        $data = $cache->first(function ($item) use ($value, $field) {
            $match = isset($item[$field]) && strtoupper($item[$field]) === strtoupper($value);
            return $match;
        });

        return $data;
    }

    public function zonas($value, $field)
    {
        $redisData = $this->zonaVersion2;

        $cache = $redisData;

        $data = $cache->first(function ($item) use ($value, $field) {
            $match = isset($item[$field]) && strtoupper($item[$field]) === strtoupper($value);
            return $match;
        });

        return $data;
    }

    protected function isValidDate($value): bool
    {
        $value = trim($value);

        $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y'];

        foreach ($formats as $format) {
            $d = \DateTime::createFromFormat($format, $value);
            if ($d && $d->format($format) === $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * Enviar notificación al usuario
     */
    private function sendNotification($userId, $data)
    {
        // Obtener el objeto User a partir del ID
        $user = User::find($userId);


        if ($user) {
            // Enviar notificación
            $user->notify(new BellNotification($data));

            $excel = $this->excelErrorsValidation($data['patients_import']);
            $xlsx = $this->exportXlsxErrorsValidation($data['patients_import']);

            // Enviar el correo usando el job de Brevo
            BrevoProcessSendEmail::dispatch(
                emailTo: [
                    [
                        "name" => $user->full_name,
                        "email" => $user->email,
                    ]
                ],
                subject: $data['title'],
                templateId: 15,  // El ID de la plantilla de Brevo que quieres usar
                params: [
                    "full_name" => $user->full_name,
                    "subtitle" => $data['subtitle'],
                    "bussines_name" => $user->company?->name,
                    "patients_import" => $data['patients_import'],
                    "show_table_errors" => count($data['patients_import']) > 0 ? true : false,
                ],
                attachments: [
                    [
                        'name' => 'Lista de errores de validación.xlsx',
                        'content' => base64_encode($excel),
                    ],
                    [
                        'name' => 'Pacientes.xlsx',
                        'content' => base64_encode($xlsx),
                    ],
                ],
            );
        }
    }

    private function excelErrorsValidation($data)
    {

        $excel = Excel::raw(new PatientExcelErrorsValidationExport($data), \Maatwebsite\Excel\Excel::XLSX);

        return $excel;
    }

    private function exportXlsxErrorsValidation($data)
    {
        // Agrupar por 'row'
        $groupedErrors = collect($data)->groupBy('row');

        // Obtener un solo 'data' por grupo (el primero, por ejemplo)
        $result = $groupedErrors->map(function ($group) {
            // Tomar el primer elemento del grupo y devolver solo su 'data'
            return $group->first()['data'] ?? null;
        })->values();


        // Generar el xlsx con Laravel Excel
        $xlsx = Excel::raw(new PatientExcelErrorsValidationExport($result), \Maatwebsite\Excel\Excel::XLSX);

        return $xlsx;
    }
}

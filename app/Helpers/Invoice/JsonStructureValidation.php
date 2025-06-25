<?php

namespace App\Helpers\Invoice;

use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Validator;

class JsonStructureValidation
{

    private const TYPE_VALIDATION = "Estructura";

    public static function initValidation($path_json)
    {
        // Leer el contenido del JSON
        $jsonContent = file_get_contents($path_json);

        // Verificar si es JSON válido
        $data = json_decode($jsonContent);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'isValid' => false,
                'message' => 'El archivo no contiene un JSON válido: ' . json_last_error_msg(),
                'errors' => [],
            ];
        }

        // Cargar el esquema desde el archivo
        $schemaPath = storage_path('jsonschemas/validJSONSchema.json');
        if (! file_exists($schemaPath)) {
            return [
                'isValid' => false,
                'message' => "El archivo de esquema JSON no se encuentra en: $schemaPath",
                'errors' => [],
            ];
        }

        $schemaContent = file_get_contents($schemaPath);

        // Validar que el esquema sea JSON válido
        $schemaData = json_decode($schemaContent);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'isValid' => false,
                'message' => 'El esquema JSON no es válido: ' . json_last_error_msg(),
                'errors' => [],
            ];
        }

        // Validar con Opis\JsonSchema usando URI
        $validator = new Validator;
        $validator->setMaxErrors(100); // Permitir recolectar hasta 100 errores
        $validator->setStopAtFirstError(false); // Permitir recolectar hasta 100 errores
        $result = $validator->validate($data, $schemaData);

        if ($result->isValid()) {
            return [
                'isValid' => $result->isValid(),
                'message' => 'Archivo JSON válido y procesado correctamente.',
                'errors' => [],
            ];
        }


        // Recolectar errores
        $errorFormatter = new ErrorFormatter;
        $errors = $errorFormatter->format($result->error());
        $formattedErrors = [];


        // Procesar cada error
        foreach ($errors as $path => $messages) {
            foreach ($messages as $message) {
                $errorList = self::simplifyErrorMessage($message, $path, $data);
                foreach ($errorList as $error) {
                    if (! empty($error['message'])) {
                        $formattedErrors[] = [
                            'type' => self::TYPE_VALIDATION,
                            'level' => $error['level'],
                            'key' => $error['key'],
                            'data' => $error['data'],
                            'message' => $error['message'],
                        ];
                    }
                }
            }
        }

        // Eliminar duplicados basados en mensaje
        $uniqueErrors = [];
        $seenMessages = [];
        foreach ($formattedErrors as $error) {
            if (! in_array($error['message'], $seenMessages)) {
                $uniqueErrors[] = $error;
                $seenMessages[] = $error['message'];
            }
        }

        return [
            'isValid' => $result->isValid(),
            'message' => 'Error de validación JSON.',
            'errors' => array_values($uniqueErrors),
        ];
    }

    /**
     * Simplifica y mejora los mensajes de error de validación JSON
     *
     * @param  string  $message  El mensaje de error original
     * @param  string  $path  La ruta del error
     * @param  mixed  $data  Los datos del JSON para extraer valores
     * @return array
     */
    private static function simplifyErrorMessage($message, $path = '', $data = null)
    {
        $cleanPath = self::cleanJsonPath($path);
        $level = $cleanPath; //self::determineErrorLevel($path);
        $errors = [];

        // Extraer el índice del usuario de la ruta, si está presente
        $userIndex = self::extractUserIndexFromPath($path);

        // Patrón para campos requeridos faltantes
        if (strpos($message, 'required properties') !== false && strpos($message, 'missing') !== false) {
            // Extraer los campos faltantes, por ejemplo: "The required properties (tipoNota, numNota) are missing"
            preg_match('/\(([^)]+)\)/', $message, $matches);
            $missingFields = isset($matches[1]) ? array_map('trim', explode(',', $matches[1])) : ['desconocido'];
            foreach ($missingFields as $key) {
                $value = ''; // Campo faltante, valor vacío
                $mensaje = "En el nivel de $level falta el campo requerido '$key'" .
                    ($cleanPath ? " (en $cleanPath)" : '') .
                    ($userIndex !== null ? " (Usuario $userIndex)" : '');
                $errors[] = [
                    'type' => self::TYPE_VALIDATION,
                    'level' => $level,
                    'key' => $key,
                    'data' => $value,
                    'message' => $mensaje,
                ];
            }
        }

        // Manejo CORREGIDO para propiedades adicionales
        elseif (strpos($message, 'Additional object properties are not allowed') !== false) {
            // Extraer todos los campos mencionados
            preg_match('/: ([^,]+(?:, [^,]+)*)/', $message, $matches);
            $allFields = isset($matches[1]) ? array_map('trim', explode(',', $matches[1])) : [];

            // Obtener las propiedades permitidas para la ruta actual
            $definedProperties = self::getAllowedPropertiesForPath($path);

            // Filtrar para obtener solo los campos realmente no permitidos
            $invalidFields = array_diff($allFields, $definedProperties);

            foreach ($invalidFields as $field) {
                $value = self::getValueFromPath($data, $field);
                $mensaje = "Campo no permitido en $level: '$field'" .
                    ($cleanPath ? " (en $cleanPath)" : '') .
                    ($userIndex !== null ? " (Usuario $userIndex)" : '');
                $errors[] = [
                    'type' => self::TYPE_VALIDATION,
                    'level' => $level,
                    'key' => $field,
                    'data' => $value,
                    'message' => $mensaje,
                ];
            }
        }

        // Para errores de tipo
        elseif (strpos($message, 'must be') !== false) {
            $key = self::extractKeyFromPath($path);
            $value = self::getValueFromPath($data, $path);
            $mensaje = 'Tipo de dato incorrecto' .
                ($cleanPath ? " en $cleanPath" : '') .
                ': ' . $message .
                ($userIndex !== null ? " (Usuario $userIndex)" : '');
            $errors[] = [
                'type' => self::TYPE_VALIDATION,
                'level' => $level,
                'key' => $key,
                'data' => $value,
                'message' => $mensaje,
            ];
        }
        // Mensaje genérico
        else {
            $key = self::extractKeyFromPath($path);
            $value = self::getValueFromPath($data, $path);
            $mensaje = "Error en $level: $message" . ($cleanPath ? " (en $cleanPath)" : '') .
                ($userIndex !== null ? " (Usuario $userIndex)" : '');
            $errors[] = [
                'type' => self::TYPE_VALIDATION,
                'level' => $level,
                'key' => $key,
                'data' => $value,
                'message' => $mensaje,
            ];
        }

        return $errors;
    }

    // Método para extraer el índice del usuario de la ruta
    private static function extractUserIndexFromPath($path)
    {
        // Usar expresión regular para extraer el índice del usuario
        if (preg_match('#^/usuarios/(\d+)#', $path, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private static function getAllowedPropertiesForPath($path)
    {
        // Normalize path
        $path = ltrim($path, '#');

        // Root level
        if ($path === '' || $path === '/') {
            return ['numDocumentoIdObligado', 'numFactura', 'tipoNota', 'numNota', 'usuarios'];
        }

        // User level (/usuarios/0)
        if (preg_match('#^/usuarios/\d+$#', $path)) {
            return [
                'codSexo',
                'consecutivo',
                'incapacidad',
                'tipoUsuario',
                'codPaisOrigen',
                'fechaNacimiento',
                'codPaisResidencia',
                'codMunicipioResidencia',
                'numDocumentoIdentificacion',
                'tipoDocumentoIdentificacion',
                'codZonaTerritorialResidencia',
                'servicios',
            ];
        }

        // Services level (/usuarios/0/servicios)
        if (preg_match('#^/usuarios/\d+/servicios$#', $path)) {
            return [
                'consultas',
                'procedimientos',
                'urgencias',
                'hospitalizacion',
                'recienNacidos',
                'medicamentos',
                'otrosServicios',
            ];
        }

        // Individual procedure level (/usuarios/0/servicios/procedimientos/0)
        if (preg_match('#^/usuarios/\d+/servicios/procedimientos/\d+$#', $path)) {
            return [
                'codPrestador',
                'fechaInicioAtencion',
                'idMIPRES',
                'numAutorizacion',
                'codProcedimiento',
                'viaIngresoServicioSalud',
                'modalidadGrupoServicioTecSal',
                'grupoServicios',
                'codServicio',
                'finalidadTecnologiaSalud',
                'tipoDocumentoIdentificacion',
                'numDocumentoIdentificacion',
                'codDiagnosticoPrincipal',
                'codDiagnosticoRelacionado',
                'codComplicacion',
                'valorPagoModerador',
                'numFEVPagoModerador',
                'consecutivo',
                'vrServicio',
                'conceptoRecaudo',
            ];
        }

        // Individual hospitalization level (/usuarios/0/servicios/hospitalizacion/0)
        if (preg_match('#^/usuarios/\d+/servicios/hospitalizacion/\d+$#', $path)) {
            return [
                'codPrestador',
                'viaIngresoServicioSalud',
                'fechaInicioAtencion',
                'numAutorizacion',
                'causaMotivoAtencion',
                'codDiagnosticoPrincipal',
                'codDiagnosticoPrincipalE',
                'codDiagnosticoRelacionadoE1',
                'codDiagnosticoRelacionadoE2',
                'codDiagnosticoRelacionadoE3',
                'codComplicacion',
                'condicionDestinoUsuarioEgreso',
                'codDiagnosticoCausaMuerte',
                'fechaEgreso',
                'consecutivo',
                'numDocumentoIdentificacion',
                'tipoDocumentoIdentificacion',
                'numFEVPagoModerador',
            ];
        }

        // Other service types (unchanged from your code)
        if (preg_match('#^/usuarios/\d+/servicios/consultas$#', $path)) {
            return [
                'codPrestador',
                'fechaInicioAtencion',
                'numAutorizacion',
                'codConsulta',
                'modalidadGrupoServicioTecSal',
                'grupoServicios',
                'codServicio',
                'finalidadTecnologiaSalud',
                'causaMotivoAtencion',
                'codDiagnosticoPrincipal',
                'codDiagnosticoRelacionado1',
                'codDiagnosticoRelacionado2',
                'codDiagnosticoRelacionado3',
                'tipoDiagnosticoPrincipal',
                'tipoDocumentoIdentificacion',
                'numDocumentoIdentificacion',
                'valorPagoModerador',
                'numFEVPagoModerador',
                'consecutivo',
                'vrServicio',
                'conceptoRecaudo',
            ];
        } elseif (preg_match('#^/usuarios/\d+/servicios/urgencias$#', $path)) {
            return [
                'codPrestador',
                'fechaInicioAtencion',
                'causaMotivoAtencion',
                'codDiagnosticoPrincipal',
                'codDiagnosticoPrincipalE',
                'codDiagnosticoRelacionadoE1',
                'codDiagnosticoRelacionadoE2',
                'codDiagnosticoRelacionadoE3',
                'condicionDestinoUsuarioEgreso',
                'codDiagnosticoCausaMuerte',
                'fechaEgreso',
                'consecutivo',
                'numFEVPagoModerador',
                'numDocumentoIdentificacion',
                'tipoDocumentoIdentificacion',
            ];
        } elseif (preg_match('#^/usuarios/\d+/servicios/recienNacidos$#', $path)) {
            return [
                'codPrestador',
                'tipoDocumentoIdentificacion',
                'numDocumentoIdentificacion',
                'fechaNacimiento',
                'edadGestacional',
                'numConsultasCPrenatal',
                'codSexoBiologico',
                'peso',
                'codDiagnosticoPrincipal',
                'condicionDestino',
                'condicionDestinoUsuarioEgreso',
                'codDiagnosticoCausaMuerte',
                'fechaEgreso',
                'consecutivo',
                'numFEVPagoModerador',
            ];
        } elseif (preg_match('#^/usuarios/\d+/servicios/medicamentos$#', $path)) {
            return [
                'codPrestador',
                'numAutorizacion',
                'idMIPRES',
                'fechaDispensAdmon',
                'codDiagnosticoPrincipal',
                'codDiagnosticoRelacionado',
                'tipoMedicamento',
                'codTecnologiaSalud',
                'nomTecnologiaSalud',
                'concentracionMedicamento',
                'unidadMedida',
                'formaFarmaceutica',
                'unidadMinDispensa',
                'cantidadMedicamento',
                'diasTratamiento',
                'tipoDocumentoIdentificacion',
                'numDocumentoIdentificacion',
                'vrUnitMedicamento',
                'valorPagoModerador',
                'numFEVPagoModerador',
                'consecutivo',
                'vrServicio',
                'conceptoRecaudo',
            ];
        } elseif (preg_match('#^/usuarios/\d+/servicios/otrosServicios$#', $path)) {
            return [
                'codPrestador',
                'numAutorizacion',
                'idMIPRES',
                'fechaSuministroTecnologia',
                'tipoOS',
                'codTecnologiaSalud',
                'nomTecnologiaSalud',
                'cantidadOS',
                'tipoDocumentoldentificacion',
                'numDocumentoldentificacion',
                'vrUnitOS',
                'vrServicio',
                'conceptoRecaudo',
                'valorPagoModerador',
                'numFEVPagoModerador',
                'consecutivo',
            ];
        }

        // Log unmatched paths for debugging
        return [];
    }

    /**
     * Extrae el valor del JSON en la ruta especificada
     *
     * @param  mixed  $data  Los datos del JSON
     * @param  string  $path  La ruta del error
     * @return string
     */
    private static function getValueFromPath($data, $path)
    {
        if (! $data) {
            return '';
        }

        // Normalizar la ruta eliminando el prefijo '#'
        $path = ltrim($path, '#');
        if (empty($path) || $path === '/') {
            return is_object($data) || is_array($data) ? '[object]' : (string) $data;
        }

        // Convertir la ruta en un arreglo de claves
        $parts = explode('/', trim($path, '/'));
        $current = $data;

        foreach ($parts as $part) {
            if (is_object($current)) {
                if (property_exists($current, $part)) {
                    $current = $current->$part;
                } else {
                    return '';
                }
            } elseif (is_array($current)) {
                if (is_numeric($part) && isset($current[$part])) {
                    $current = $current[$part];
                } else {
                    return '';
                }
            } else {
                return '';
            }
        }

        // Simplificar el valor
        if (is_object($current) || is_array($current)) {
            return '[object]';
        }

        return (string) $current;
    }

    /**
     * Extrae la clave desde la ruta del error
     *
     * @param  string  $path
     * @return string
     */
    private static function extractKeyFromPath($path)
    {
        // Normalizar la ruta eliminando el prefijo '#'
        $path = ltrim($path, '#');
        if (empty($path) || $path === '/') {
            return 'desconocido';
        }

        // Tomar la última parte de la ruta como la clave
        $parts = explode('/', trim($path, '/'));

        return end($parts);
    }

    /**
     * Determina el nivel del error para mensajes más amigables
     *
     * @param  string  $path
     * @return string
     */
    private static function determineErrorLevel($path)
    {
        // Normalizar la ruta eliminando el prefijo '#'
        $path = ltrim($path, '#');

        // Check more specific paths first, allowing additional segments
        if (preg_match('#^/usuarios/\d+/servicios/procedimientos(/\d+)?(/.*)?$#', $path)) {
            return 'procedimiento';
        } elseif (preg_match('#^/usuarios/\d+/servicios/consultas(/\d+)?(/.*)?$#', $path)) {
            return 'consulta';
        } elseif (preg_match('#^/usuarios/\d+/servicios/urgencias(/\d+)?(/.*)?$#', $path)) {
            return 'urgencia';
        } elseif (preg_match('#^/usuarios/\d+/servicios/hospitalizacion(/\d+)?(/.*)?$#', $path)) {
            return 'hospitalización';
        } elseif (preg_match('#^/usuarios/\d+/servicios/recienNacidos(/\d+)?(/.*)?$#', $path)) {
            return 'recién nacido';
        } elseif (preg_match('#^/usuarios/\d+/servicios/medicamentos(/\d+)?(/.*)?$#', $path)) {
            return 'medicamento';
        } elseif (preg_match('#^/usuarios/\d+/servicios/otrosServicios(/\d+)?(/.*)?$#', $path)) {
            return 'otro servicio';
        } elseif (preg_match('#^/usuarios/\d+/servicios$#', $path)) {
            return 'servicio';
        } elseif (preg_match('#^/usuarios(/\d+)?$#', $path)) {
            return 'usuario';
        } elseif (empty($path) || $path === '/' || $path === '') {
            return 'factura';
        }
        return $path;
    }

    /**
     * Limpia y traduce la ruta del error para hacerla más legible
     *
     * @param  string  $path
     * @return string
     */
    private static function cleanJsonPath($path)
    {
        // Normalizar la ruta eliminando el prefijo '#'
        $path = ltrim($path, '#');
        if (empty($path) || $path === '/') {
            return '';
        }

        $mappings = [
            '/usuarios' => 'usuarios',
            '/servicios' => 'servicios',
            '/consultas' => 'consultas',
            '/procedimientos' => 'procedimientos',
            '/urgencias' => 'urgencias',
            '/hospitalizacion' => 'hospitalizacion',
            '/recienNacidos' => 'recienNacidos',
            '/medicamentos' => 'medicamentos',
            '/otrosServicios' => 'otrosServicios',
        ];

        // Reemplazar nombres de niveles
        // $cleanPath = str_replace(array_keys($mappings), array_values($mappings), $path);
        $cleanPath = $path;

        // Mantener los índices numéricos para indicar posición (por ejemplo, Usuario[0])
        $cleanPath = preg_replace('/\/(\d+)/', '[$1]', $cleanPath);

        // Reemplazar barras por "." para mayor legibilidad
        $cleanPath = str_replace('/', '.', $cleanPath);

        return trim($cleanPath, '.');
    }
}

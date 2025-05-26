<?php

namespace App\Services\JsonValidation;

class JsonDataValidationConfig
{
    public static function getValidationRules(): array
    {
        return [
            'tipoNota' => [
                'type' => 'exists',
                'table' => 'tipo_notas',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El tipoNota no existe en la tabla tipo_notas.',
            ],
            'numDocumentoIdObligado' => [
                'type' => 'exists',
                'table' => 'service_vendors',
                'column' => 'nit',
                'select' => ["id", "nit", "name"],
                'error_message' => 'El numDocumentoIdObligado no existe en la tabla service_vendors.',
            ],
            'usuarios.*.tipoDocumentoIdentificacion' => [
                'type' => 'exists',
                'table' => 'tipo_id_pisis',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El tipoDocumentoIdentificacion no existe en la tabla tipo_id_pisis.',
            ],
            'usuarios.*.tipoUsuario' => [
                'type' => 'exists',
                'table' => 'rips_tipo_usuario_version2s',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El tipoUsuario no existe en la tabla rips_tipo_usuario_version2s.',
            ],
            'usuarios.*.fechaNacimiento' => [
                'type' => 'date',
                'error_message' => fn($rule, $value) => "El fechaNacimiento '{$value}' debe ser una fecha válida.",
            ],
            'usuarios.*.codSexo' => [
                'type' => 'exists',
                'table' => 'sexos',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El codSexo no existe en la tabla sexos.',
            ],
            'usuarios.*.codPaisResidencia' => [
                'type' => 'exists',
                'table' => 'pais',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El codPaisResidencia no existe en la tabla pais.',
            ],
            'usuarios.*.codMunicipioResidencia' => [
                'type' => 'exists',
                'table' => 'municipios',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El codMunicipioResidencia no existe en la tabla municipios.',
            ],
            'usuarios.*.codZonaTerritorialResidencia' => [
                'type' => 'exists',
                'table' => 'zona_version2s',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El codZonaTerritorialResidencia no existe en la tabla zona_version2s.',
            ],
            'usuarios.*.incapacidad' => [
                'type' => 'in',
                'values' => ['SI', 'NO'],
                'error_message' => fn($rule) => 'El incapacidad debe ser uno de: ' . implode(', ', $rule['values']) . '.',
            ],
            'usuarios.*.codPaisOrigen' => [
                'type' => 'exists',
                'table' => 'pais',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El codPaisOrigen no existe en la tabla pais.',
            ],
            'usuarios.*.servicios.consultas.*.codPrestador' => [
                'type' => 'exists',
                'table' => 'ips_cod_habilitacions',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El codPrestador no existe en la tabla ips_cod_habilitacions.',
            ],
            'usuarios.*.servicios.consultas.*.fechaInicioAtencion' => [
                'type' => 'date',
                'error_message' => fn($rule, $value) => "El fechaInicioAtencion '{$value}' debe ser una fecha válida.",
            ],
            'usuarios.*.servicios.consultas.*.codConsulta' => [
                'type' => 'exists',
                'table' => 'cups_rips',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El codConsulta no existe en la tabla cups_rips.',
            ],
            'usuarios.*.servicios.consultas.*.codConsulta' => [
                'type' => 'exists',
                'table' => 'cups_rips',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El codConsulta no existe en la tabla cups_rips.',
            ],
            'usuarios.*.servicios.consultas.*.modalidadGrupoServicioTecSal' => [
                'type' => 'exists',
                'table' => 'modalidad_atencions',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El modalidadGrupoServicioTecSal no existe en la tabla modalidad_atencions.',
            ],
            'usuarios.*.servicios.consultas.*.grupoServicios' => [
                'type' => 'exists',
                'table' => 'grupo_servicios',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El grupoServicios no existe en la tabla grupo_servicios.',
            ],
            'usuarios.*.servicios.consultas.*.codServicio' => [
                'type' => 'exists',
                'table' => 'servicios',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El codServicio no existe en la tabla servicios.',
            ],
            'usuarios.*.servicios.consultas.*.finalidadTecnologiaSalud' => [
                'type' => 'exists',
                'table' => 'rips_finalidad_consulta_version2s',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El finalidadTecnologiaSalud no existe en la tabla rips_finalidad_consulta_version2s.',
            ],
            'usuarios.*.servicios.consultas.*.causaMotivoAtencion' => [
                'type' => 'exists',
                'table' => 'rips_causa_externa_version2s',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El causaMotivoAtencion no existe en la tabla rips_causa_externa_version2s.',
            ],
            'usuarios.*.servicios.consultas.*.codDiagnosticoPrincipal' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El codDiagnosticoPrincipal no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.consultas.*.codDiagnosticoRelacionado1' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El codDiagnosticoRelacionado1 no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.consultas.*.codDiagnosticoRelacionado2' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El codDiagnosticoRelacionado2 no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.consultas.*.codDiagnosticoRelacionado3' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El codDiagnosticoRelacionado3 no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.consultas.*.tipoDiagnosticoPrincipal' => [
                'type' => 'exists',
                'table' => 'rips_tipo_diagnostico_principal_version2s',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El tipoDiagnosticoPrincipal no existe en la tabla rips_tipo_diagnostico_principal_version2s.',
            ],
            'usuarios.*.servicios.consultas.*.tipoDocumentoIdentificacion' => [
                'type' => 'exists',
                'table' => 'tipo_id_pisis',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El tipoDocumentoIdentificacion no existe en la tabla tipo_id_pisis.',
            ],
            'usuarios.*.servicios.consultas.*.vrServicio' => [
                'type' => 'numeric',
                'error_message' => 'El vrServicio debe ser un número válido.',
            ],
            'usuarios.*.servicios.consultas.*.conceptoRecaudo' => [
                'type' => 'exists',
                'table' => 'concepto_recaudos',
                'column' => 'codigo',
                'select' => ["id", "codigo", "nombre"],
                'error_message' => 'El conceptoRecaudo no existe en la tabla concepto_recaudos.',
            ],
            'usuarios.*.servicios.consultas.*.valorPagoModerador' => [
                'type' => 'numeric',
                'error_message' => 'El valorPagoModerador debe ser un número válido.',
            ],
        ];
    }
}

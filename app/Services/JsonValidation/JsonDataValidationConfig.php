<?php

namespace App\Services\JsonValidation;

class JsonDataValidationConfig
{
    public static function getValidationRules(): array
    {
        // reglas de la factura
        $rulesRoot = [
            'tipoNota' => [
                'type' => 'exists',
                'table' => 'tipo_notas',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El tipoNota no existe en la tabla tipo_notas.',
            ],
            'numDocumentoIdObligado' => [
                'type' => 'exists',
                'table' => 'service_vendors',
                'withCompanyId' => true,
                'column' => 'nit',
                'select' => ['id', 'nit', 'name'],
                'error_message' => 'El numDocumentoIdObligado no existe en la tabla service_vendors.',
            ],
        ];

        // reglas de los usuarios
        $rulesUsuarios = [
            'usuarios.*.tipoDocumentoIdentificacion' => [
                'type' => 'exists',
                'table' => 'tipo_id_pisis',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El tipoDocumentoIdentificacion no existe en la tabla tipo_id_pisis.',
            ],
            'usuarios.*.numDocumentoIdentificacion' => [
                'type' => 'exists',
                'table' => 'patients',
                'withCompanyId' => true,
                'column' => 'document',
                'select' => ['id', 'document', 'first_name', 'second_name', 'first_surname', 'second_surname'],
                'error_message' => 'El numDocumentoIdentificacion no existe en la tabla patients.',
            ],
            'usuarios.*.tipoUsuario' => [
                'type' => 'exists',
                'table' => 'rips_tipo_usuario_version2s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El tipoUsuario no existe en la tabla rips_tipo_usuario_version2s.',
            ],
            'usuarios.*.fechaNacimiento' => [
                'type' => 'date',
                'error_message' => fn($rule, $value) => "El fechaNacimiento '{$value}' debe ser una fecha válida.",
            ],
            'usuarios.*.codSexo' => [
                'type' => 'exists',
                'table' => 'sexos',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codSexo no existe en la tabla sexos.',
            ],
            'usuarios.*.codPaisResidencia' => [
                'type' => 'exists',
                'table' => 'pais',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codPaisResidencia no existe en la tabla pais.',
            ],
            'usuarios.*.codMunicipioResidencia' => [
                'type' => 'exists',
                'table' => 'municipios',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codMunicipioResidencia no existe en la tabla municipios.',
            ],
            'usuarios.*.codZonaTerritorialResidencia' => [
                'type' => 'exists',
                'table' => 'zona_version2s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
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
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codPaisOrigen no existe en la tabla pais.',
            ],
            'usuarios.*.consecutivo' => [
                'type' => 'numeric',
                'error_message' => 'El consecutivo debe ser un número válido.',
            ],
        ];

        // reglas de consultas
        $rulesConsultas = [
            'usuarios.*.servicios.consultas.*.codPrestador' => [
                'type' => 'exists',
                'table' => 'ips_cod_habilitacions',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codPrestador no existe en la tabla ips_cod_habilitacions.',
            ],
            'usuarios.*.servicios.consultas.*.fechaInicioAtencion' => [
                'type' => 'date',
                'error_message' => fn($rule, $value) => "El fechaInicioAtencion '{$value}' debe ser una fecha válida.",
            ],
            'usuarios.*.servicios.consultas.*.codConsulta' => [
                'type' => 'exists',
                'table' => 'cups_rips',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codConsulta no existe en la tabla cups_rips.',
            ],
            'usuarios.*.servicios.consultas.*.modalidadGrupoServicioTecSal' => [
                'type' => 'exists',
                'table' => 'modalidad_atencions',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El modalidadGrupoServicioTecSal no existe en la tabla modalidad_atencions.',
            ],
            'usuarios.*.servicios.consultas.*.grupoServicios' => [
                'type' => 'exists',
                'table' => 'grupo_servicios',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El grupoServicios no existe en la tabla grupo_servicios.',
            ],
            'usuarios.*.servicios.consultas.*.codServicio' => [
                'type' => 'exists',
                'table' => 'servicios',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codServicio no existe en la tabla servicios.',
            ],
            'usuarios.*.servicios.consultas.*.finalidadTecnologiaSalud' => [
                'type' => 'exists',
                'table' => 'rips_finalidad_consulta_version2s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El finalidadTecnologiaSalud no existe en la tabla rips_finalidad_consulta_version2s.',
            ],
            'usuarios.*.servicios.consultas.*.causaMotivoAtencion' => [
                'type' => 'exists',
                'table' => 'rips_causa_externa_version2s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El causaMotivoAtencion no existe en la tabla rips_causa_externa_version2s.',
            ],
            'usuarios.*.servicios.consultas.*.codDiagnosticoPrincipal' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoPrincipal no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.consultas.*.codDiagnosticoRelacionado1' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoRelacionado1 no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.consultas.*.codDiagnosticoRelacionado2' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoRelacionado2 no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.consultas.*.codDiagnosticoRelacionado3' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoRelacionado3 no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.consultas.*.tipoDiagnosticoPrincipal' => [
                'type' => 'exists',
                'table' => 'rips_tipo_diagnostico_principal_version2s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El tipoDiagnosticoPrincipal no existe en la tabla rips_tipo_diagnostico_principal_version2s.',
            ],
            'usuarios.*.servicios.consultas.*.tipoDocumentoIdentificacion' => [
                'type' => 'exists',
                'table' => 'tipo_id_pisis',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El tipoDocumentoIdentificacion no existe en la tabla tipo_id_pisis.',
            ],
            'usuarios.*.servicios.consultas.*.vrServicio' => [
                'type' => 'numeric',
                'error_message' => 'El vrServicio debe ser un número válido.',
            ],
            'usuarios.*.servicios.consultas.*.conceptoRecaudo' => [
                'type' => 'exists',
                'table' => 'concepto_recaudos',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El conceptoRecaudo no existe en la tabla concepto_recaudos.',
            ],
            'usuarios.*.servicios.consultas.*.valorPagoModerador' => [
                'type' => 'numeric',
                'error_message' => 'El valorPagoModerador debe ser un número válido.',
            ],
            'usuarios.*.servicios.consultas.*.consecutivo' => [
                'type' => 'numeric',
                'error_message' => 'El consecutivo debe ser un número válido.',
            ],
        ];

        // reglas de procedimientos
        $rulesProcedimientos = [
            'usuarios.*.servicios.procedimientos.*.codPrestador' => [
                'type' => 'exists',
                'table' => 'ips_cod_habilitacions',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codPrestador no existe en la tabla ips_cod_habilitacions.',
            ],
            'usuarios.*.servicios.procedimientos.*.fechaInicioAtencion' => [
                'type' => 'date',
                'error_message' => fn($rule, $value) => "El fechaInicioAtencion '{$value}' debe ser una fecha válida.",
            ],
            'usuarios.*.servicios.procedimientos.*.codProcedimiento' => [
                'type' => 'exists',
                'table' => 'cups_rips',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codProcedimiento no existe en la tabla cups_rips.',
            ],
            'usuarios.*.servicios.procedimientos.*.viaIngresoServicioSalud' => [
                'type' => 'exists',
                'table' => 'via_ingreso_usuarios',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El viaIngresoServicioSalud no existe en la tabla via_ingreso_usuarios.',
            ],
            'usuarios.*.servicios.procedimientos.*.modalidadGrupoServicioTecSal' => [
                'type' => 'exists',
                'table' => 'modalidad_atencions',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El modalidadGrupoServicioTecSal no existe en la tabla modalidad_atencions.',
            ],
            'usuarios.*.servicios.procedimientos.*.grupoServicios' => [
                'type' => 'exists',
                'table' => 'grupo_servicios',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El grupoServicios no existe en la tabla grupo_servicios.',
            ],
            'usuarios.*.servicios.procedimientos.*.codServicio' => [
                'type' => 'exists',
                'table' => 'servicios',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codServicio no existe en la tabla servicios.',
            ],
            'usuarios.*.servicios.procedimientos.*.finalidadTecnologiaSalud' => [
                'type' => 'exists',
                'table' => 'rips_finalidad_consulta_version2s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El finalidadTecnologiaSalud no existe en la tabla rips_finalidad_consulta_version2s.',
            ],
            'usuarios.*.servicios.procedimientos.*.tipoDocumentoIdentificacion' => [
                'type' => 'exists',
                'table' => 'tipo_id_pisis',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El tipoDocumentoIdentificacion no existe en la tabla tipo_id_pisis.',
            ],
            'usuarios.*.servicios.procedimientos.*.codDiagnosticoPrincipal' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoPrincipal no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.procedimientos.*.codDiagnosticoRelacionado' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoRelacionado no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.procedimientos.*.codComplicacion' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codComplicacion no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.procedimientos.*.vrServicio' => [
                'type' => 'numeric',
                'error_message' => 'El vrServicio debe ser un número válido.',
            ],
            'usuarios.*.servicios.procedimientos.*.conceptoRecaudo' => [
                'type' => 'exists',
                'table' => 'concepto_recaudos',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El conceptoRecaudo no existe en la tabla concepto_recaudos.',
            ],
            'usuarios.*.servicios.procedimientos.*.valorPagoModerador' => [
                'type' => 'numeric',
                'error_message' => 'El valorPagoModerador debe ser un número válido.',
            ],
            'usuarios.*.servicios.procedimientos.*.consecutivo' => [
                'type' => 'numeric',
                'error_message' => 'El consecutivo debe ser un número válido.',
            ],
        ];

        // reglas de medicamentos
        $rulesMedicamentos = [
            'usuarios.*.servicios.medicamentos.*.codPrestador' => [
                'type' => 'exists',
                'table' => 'ips_cod_habilitacions',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codPrestador no existe en la tabla ips_cod_habilitacions.',
            ],
            'usuarios.*.servicios.medicamentos.*.fechaDispensAdmon' => [
                'type' => 'date',
                'error_message' => fn($rule, $value) => "El fechaDispensAdmon '{$value}' debe ser una fecha válida.",
            ],
            'usuarios.*.servicios.medicamentos.*.codDiagnosticoPrincipal' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoPrincipal no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.medicamentos.*.codDiagnosticoRelacionado' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoRelacionado no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.medicamentos.*.tipoMedicamento' => [
                'type' => 'exists',
                'table' => 'tipo_medicamento_pos_version2s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El tipoMedicamento no existe en la tabla tipo_medicamento_pos_version2s.',
            ],
            'usuarios.*.servicios.medicamentos.*.concentracionMedicamento' => [
                'type' => 'numeric',
                'error_message' => 'El concentracionMedicamento debe ser un número válido.',
            ],
            'usuarios.*.servicios.medicamentos.*.unidadMedida' => [
                'type' => 'numeric',
                'error_message' => 'El unidadMedida debe ser un número válido.',
            ],
            'usuarios.*.servicios.medicamentos.*.unidadMinDispensa' => [
                'type' => 'numeric',
                'error_message' => 'El unidadMinDispensa debe ser un número válido.',
            ],
            'usuarios.*.servicios.medicamentos.*.cantidadMedicamento' => [
                'type' => 'numeric',
                'error_message' => 'El cantidadMedicamento debe ser un número válido.',
            ],
            'usuarios.*.servicios.medicamentos.*.diasTratamiento' => [
                'type' => 'numeric',
                'error_message' => 'El diasTratamiento debe ser un número válido.',
            ],
            'usuarios.*.servicios.medicamentos.*.tipoDocumentoIdentificacion' => [
                'type' => 'exists',
                'table' => 'tipo_id_pisis',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El tipoDocumentoIdentificacion no existe en la tabla tipo_id_pisis.',
            ],
            'usuarios.*.servicios.medicamentos.*.vrUnitMedicamento' => [
                'type' => 'numeric',
                'error_message' => 'El vrUnitMedicamento debe ser un número válido.',
            ],
            'usuarios.*.servicios.medicamentos.*.vrServicio' => [
                'type' => 'numeric',
                'error_message' => 'El vrServicio debe ser un número válido.',
            ],
            'usuarios.*.servicios.medicamentos.*.conceptoRecaudo' => [
                'type' => 'exists',
                'table' => 'concepto_recaudos',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El conceptoRecaudo no existe en la tabla concepto_recaudos.',
            ],
            'usuarios.*.servicios.medicamentos.*.valorPagoModerador' => [
                'type' => 'numeric',
                'error_message' => 'El valorPagoModerador debe ser un número válido.',
            ],
            'usuarios.*.servicios.medicamentos.*.consecutivo' => [
                'type' => 'numeric',
                'error_message' => 'El consecutivo debe ser un número válido.',
            ],
        ];

        // reglas de otros servicios
        $rulesOtrosServicios = [
            'usuarios.*.servicios.otrosServicios.*.codPrestador' => [
                'type' => 'exists',
                'table' => 'ips_cod_habilitacions',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codPrestador no existe en la tabla ips_cod_habilitacions.',
            ],
            'usuarios.*.servicios.otrosServicios.*.fechaSuministroTecnologia' => [
                'type' => 'date',
                'error_message' => fn($rule, $value) => "El fechaSuministroTecnologia '{$value}' debe ser una fecha válida.",
            ],
            'usuarios.*.servicios.otrosServicios.*.tipoOS' => [
                'type' => 'exists',
                'table' => 'tipo_otros_servicios',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El tipoOS no existe en la tabla tipo_otros_servicios.',
            ],
            'usuarios.*.servicios.otrosServicios.*.codTecnologiaSalud' => [
                'type' => 'exists',
                'table' => 'cups_rips',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codTecnologiaSalud no existe en la tabla cups_rips.',
            ],
            'usuarios.*.servicios.otrosServicios.*.cantidadOS' => [
                'type' => 'numeric',
                'error_message' => 'El cantidadOS debe ser un número válido.',
            ],
            'usuarios.*.servicios.otrosServicios.*.tipoDocumentoIdentificacion' => [
                'type' => 'exists',
                'table' => 'tipo_id_pisis',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El tipoDocumentoIdentificacion no existe en la tabla tipo_id_pisis.',
            ],
            'usuarios.*.servicios.otrosServicios.*.vrUnitOS' => [
                'type' => 'numeric',
                'error_message' => 'El vrUnitOS debe ser un número válido.',
            ],
            'usuarios.*.servicios.otrosServicios.*.vrServicio' => [
                'type' => 'numeric',
                'error_message' => 'El vrServicio debe ser un número válido.',
            ],
            'usuarios.*.servicios.otrosServicios.*.conceptoRecaudo' => [
                'type' => 'exists',
                'table' => 'concepto_recaudos',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El conceptoRecaudo no existe en la tabla concepto_recaudos.',
            ],
            'usuarios.*.servicios.otrosServicios.*.valorPagoModerador' => [
                'type' => 'numeric',
                'error_message' => 'El valorPagoModerador debe ser un número válido.',
            ],
            'usuarios.*.servicios.otrosServicios.*.consecutivo' => [
                'type' => 'numeric',
                'error_message' => 'El consecutivo debe ser un número válido.',
            ],
        ];

        // reglas de urgencias
        $rulesUrgencias = [
            'usuarios.*.servicios.urgencias.*.codPrestador' => [
                'type' => 'exists',
                'table' => 'ips_cod_habilitacions',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codPrestador no existe en la tabla ips_cod_habilitacions.',
            ],
            'usuarios.*.servicios.urgencias.*.fechaInicioAtencion' => [
                'type' => 'date',
                'error_message' => fn($rule, $value) => "El fechaInicioAtencion '{$value}' debe ser una fecha válida.",
            ],
            'usuarios.*.servicios.urgencias.*.causaMotivoAtencion' => [
                'type' => 'exists',
                'table' => 'rips_causa_externa_version2s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El causaMotivoAtencion no existe en la tabla rips_causa_externa_version2s.',
            ],
            'usuarios.*.servicios.urgencias.*.codDiagnosticoPrincipal' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoPrincipal no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.urgencias.*.codDiagnosticoPrincipalE' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoPrincipalE no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.urgencias.*.codDiagnosticoRelacionadoE1' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoRelacionadoE1 no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.urgencias.*.codDiagnosticoRelacionadoE2' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoRelacionadoE2 no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.urgencias.*.codDiagnosticoRelacionadoE3' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoRelacionadoE3 no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.urgencias.*.codDiagnosticoCausaMuerte' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoCausaMuerte no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.urgencias.*.fechaEgreso' => [
                'type' => 'date',
                'error_message' => fn($rule, $value) => "El fechaEgreso '{$value}' debe ser una fecha válida.",
            ],
            'usuarios.*.servicios.urgencias.*.consecutivo' => [
                'type' => 'numeric',
                'error_message' => 'El consecutivo debe ser un número válido.',
            ],
        ];

        $rulesHospitalizacion = [
            'usuarios.*.servicios.hospitalizacion.*.codPrestador' => [
                'type' => 'exists',
                'table' => 'ips_cod_habilitacions',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codPrestador no existe en la tabla ips_cod_habilitacions.',
            ],
            'usuarios.*.servicios.hospitalizacion.*.viaIngresoServicioSalud' => [
                'type' => 'exists',
                'table' => 'via_ingreso_usuarios',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El viaIngresoServicioSalud no existe en la tabla via_ingreso_usuarios.',
            ],
            'usuarios.*.servicios.hospitalizacion.*.fechaInicioAtencion' => [
                'type' => 'date',
                'error_message' => fn($rule, $value) => "El fechaInicioAtencion '{$value}' debe ser una fecha válida.",
            ],
            'usuarios.*.servicios.hospitalizacion.*.causaMotivoAtencion' => [
                'type' => 'exists',
                'table' => 'rips_causa_externa_version2s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El causaMotivoAtencion no existe en la tabla rips_causa_externa_version2s.',
            ],

            'usuarios.*.servicios.hospitalizacion.*.codDiagnosticoPrincipal' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoPrincipal no existe en la tabla cie10s.',
            ],

            'usuarios.*.servicios.hospitalizacion.*.codDiagnosticoPrincipalE' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoPrincipalE no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.hospitalizacion.*.codDiagnosticoRelacionadoE1' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoRelacionadoE1 no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.hospitalizacion.*.codDiagnosticoRelacionadoE2' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoRelacionadoE2 no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.hospitalizacion.*.codDiagnosticoRelacionadoE3' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoRelacionadoE3 no existe en la tabla cie10s.',
            ],

            'usuarios.*.servicios.hospitalizacion.*.codComplicacion' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codComplicacion no existe en la tabla cie10s.',
            ],
            'usuarios.*.servicios.hospitalizacion.*.condicionDestinoUsuarioEgreso' => [
                'type' => 'exists',
                'table' => 'condiciony_destino_usuario_egresos',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El condicionDestinoUsuarioEgreso no existe en la tabla condiciony_destino_usuario_egresos.',
            ],
            'usuarios.*.servicios.hospitalizacion.*.codDiagnosticoCausaMuerte' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoCausaMuerte no existe en la tabla cie10s.',
            ],

            'usuarios.*.servicios.hospitalizacion.*.fechaEgreso' => [
                'type' => 'date',
                'error_message' => fn($rule, $value) => "El fechaEgreso '{$value}' debe ser una fecha válida.",
            ],
            'usuarios.*.servicios.hospitalizacion.*.consecutivo' => [
                'type' => 'numeric',
                'error_message' => 'El consecutivo debe ser un número válido.',
            ],
        ];

        $rulesRecienNacidos = [
            'usuarios.*.servicios.recienNacidos.*.codPrestador' => [
                'type' => 'exists',
                'table' => 'ips_cod_habilitacions',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codPrestador no existe en la tabla ips_cod_habilitacions.',
            ],

            'usuarios.*.servicios.recienNacidos.*.tipoDocumentoIdentificacion' => [
                'type' => 'exists',
                'table' => 'tipo_id_pisis',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El tipoDocumentoIdentificacion no existe en la tabla tipo_id_pisis.',
            ],

            'usuarios.*.servicios.recienNacidos.*.fechaNacimiento' => [
                'type' => 'date',
                'error_message' => fn($rule, $value) => "El fechaNacimiento '{$value}' debe ser una fecha válida.",
            ],

            'usuarios.*.servicios.recienNacidos.*.edadGestacional' => [
                'type' => 'numeric',
                'error_message' => 'El edadGestacional debe ser un número válido.',
            ],
            'usuarios.*.servicios.recienNacidos.*.numConsultasCPrenatal' => [
                'type' => 'numeric',
                'error_message' => 'El numConsultasCPrenatal debe ser un número válido.',
            ],

            'usuarios.*.servicios.recienNacidos.*.codSexoBiologico' => [
                'type' => 'exists',
                'table' => 'sexos',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codSexoBiologico no existe en la tabla sexos.',
            ],

            'usuarios.*.servicios.recienNacidos.*.peso' => [
                'type' => 'numeric',
                'error_message' => 'El peso debe ser un número válido.',
            ],

            'usuarios.*.servicios.recienNacidos.*.codDiagnosticoPrincipal' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoPrincipal no existe en la tabla cie10s.',
            ],

            'usuarios.*.servicios.recienNacidos.*.condicionDestinoUsuarioEgreso' => [
                'type' => 'exists',
                'table' => 'condiciony_destino_usuario_egresos',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El condicionDestinoUsuarioEgreso no existe en la tabla condiciony_destino_usuario_egresos.',
            ],

            'usuarios.*.servicios.recienNacidos.*.codDiagnosticoCausaMuerte' => [
                'type' => 'exists',
                'table' => 'cie10s',
                'withCompanyId' => false,
                'column' => 'codigo',
                'select' => ['id', 'codigo', 'nombre'],
                'error_message' => 'El codDiagnosticoCausaMuerte no existe en la tabla cie10s.',
            ],

            'usuarios.*.servicios.recienNacidos.*.fechaEgreso' => [
                'type' => 'date',
                'error_message' => fn($rule, $value) => "El fechaEgreso '{$value}' debe ser una fecha válida.",
            ],
            'usuarios.*.servicios.recienNacidos.*.consecutivo' => [
                'type' => 'numeric',
                'error_message' => 'El consecutivo debe ser un número válido.',
            ],
        ];

        // Unir todas las reglas
        return array_merge(
            $rulesRoot,
            $rulesUsuarios,
            $rulesConsultas,
            $rulesProcedimientos,
            $rulesMedicamentos,
            $rulesOtrosServicios,
            $rulesUrgencias,
            $rulesHospitalizacion,
            $rulesRecienNacidos,
        );
    }
}

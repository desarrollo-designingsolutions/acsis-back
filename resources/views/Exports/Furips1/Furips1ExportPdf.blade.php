<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Único de Reclamaciones - FURIPS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #fff;
            font-size: 8px;
        }

        .form-container {
            width: 100vh;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 10px;
            line-height: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            width: 50px;
            vertical-align: middle;
        }

        .header h1 {
            margin: 0;
            font-size: 8px;
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
        }

        .header p {
            margin: 2px 0;
            font-size: 8px;
        }

        .section {
            margin-bottom: 15px;
            border: 1px solid #000;
            padding: 5px;
        }

        .section h2 {
            background-color: #d0e7d2;
            margin: -5px -5px 5px -5px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            font-size: 12px;
            padding: 2px;
            border: 1px solid #000;
        }

        .form-group {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 3px;
        }

        .form-group label {
            width: 150px;
            margin-right: 5px;
            font-size: 8px;
            display: inline-block;
            vertical-align: middle;
        }

        .form-group input[type="text"],
        .form-group input[type="date"] {
            width: 120px;
            height: 14px;
            padding: 1px;
            font-size: 8px;
            border: 1px solid #000;
            border-radius: 0;
            box-sizing: border-box;
            text-align: center;
        }

        .form-group input[type="checkbox"] {
            width: 14px;
            height: 14px;
            margin-right: 3px;
            vertical-align: middle;
        }

        .date-group {
            display: flex;
            gap: -1px;
            /* Muy pegados entre sí */
        }

        .date-group input {
            width: 14px;
            height: 14px;
            text-align: center;
            border: 1px solid #000;
            padding: 0;
            margin: 0;
            font-size: 8px;
            box-sizing: border-box;
        }

        .checkbox-group {
            margin-left: 155px;
        }

        .checkbox-group div {
            margin-bottom: 1px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <div class="header">

            <!-- Imagen alineada a la izquierda absoluta -->
            <div style="position: absolute; top: 10; left: 10;">
                <img src="{{ public_path('storage/escudo_colombia.jpg') }}" alt="logo" style="max-width: 100px; max-height: 100px;">
            </div>

            <!-- Contenido centrado desde margen izquierdo -->
            <h1 style="margin-left: 10;">REPÚBLICA DE COLOMBIA</h1><br />
            <h1 style="margin-left: 10;">MINISTERIO DE SALUD Y PROTECCIÓN SOCIAL</h1><br />
            <p style="margin-left: 40;">
                FORMULARIO ÚNICO DE RECLAMACIÓN DE LAS INSTITUCIONES PRESTADORAS DE SERVICIOS DE SALUD POR SERVICIOS PRESTADOS A VICTIMAS
            </p>
            <p style="margin-left: 10;">PRESTADORES DE SERVICIOS DE SALUD - FURIPS</p>
        </div>
        <div style="display: flex; flex-direction: column; font-size: 8px; margin-top: 5px;">

            <!-- Fila 1: Fecha Radicación, RG y No. Radicado -->
            <div style="margin-bottom: 5px; font-size: 8px;">

                <!-- Fecha Radicación -->
                <span style="display: inline-block; width: 70px; vertical-align: middle;">Fecha Radicación:</span>
                @foreach ($data['radication_date'] as $digit)
                <input type="text" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach

                <!-- RG -->
                <span style="display: inline-block; width: 22px; margin-left: 10px; vertical-align: middle;">RG</span>
                <input type="text"
                    style="width: 50px; height: 10px; border: 1px solid #000; vertical-align: middle; box-sizing: border-box;">

                <!-- No. Radicado -->
                <span style="display: inline-block; width: 129px; margin-left: 10px; vertical-align: middle;">No. Radicado</span>
                <input type="text"
                    style="width: 150px; height: 10px; border: 1px solid #000; vertical-align: middle; box-sizing: border-box;">
            </div>

            <!-- Fila 2: Nota y Nro Factura -->
            <div style="margin-bottom: 5px; font-size: 8px;">

                <!-- Nota -->
                <span style="display: inline-block; width: 150px; vertical-align: middle;">
                    No. Radicación Anterior <br> (Respuesta a glosa, marcar X en RG)
                </span>
                <input type="text" style="width: 150px; height: 10px; border: 1px solid #000; box-sizing: border-box;">

                <!-- Nro Factura -->
                <span style="display: inline-block; width: 130px; vertical-align: middle; margin-left: 10px;">Nro Factura / Cuenta de Cobro:</span>
                <input type="text" value="{{ $data['invoice_number'] }}"
                    style="width: 150px; height: 10px; border: 1px solid #000; vertical-align: middle; box-sizing: border-box;">
            </div>

        </div>


        <div class="section">
            <h2>
                I. DATOS DE LA INSTITUCIÓN PRESTADORA DE SERVICIOS DE SALUD
            </h2>
            <div class="form-group">
                <label style="width: 100px;">Razón Social</label>
                <input type="text" value="{{ $data['service_vendor_name'] }}" style="width: 520px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Código Habilitación</label>
                <input type="text" value="{{ $data['service_vendor_ipsable'] }}" style="width: 250px; height: 14px;">
                <label style="margin-left: 10px; width: 20px;">Nit</label>
                <input type="text" value="{{ $data['service_vendor_nit'] }}" style="width: 222px; height: 14px;">
            </div>
        </div>

        <div class="section">
            <h2>II. DATOS DE LA VÍCTIMA DEL EVENTO CATASTRÓFICO O ACCIDENTE DE TRÁNSITO</h2>
            <div class="form-group">
                <label style="width: 50px;">1er Apellido</label>
                <input type="text" value="{{ $data['patient_first_surname'] }}" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Apellido</label>
                <input type="text" value="{{ $data['patient_second_surname'] }}" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 50px;">1er Nombre</label>
                <input type="text" value="{{ $data['patient_first_name'] }}" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Nombre</label>
                <input type="text" value="{{ $data['patient_second_name'] }}" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Tipo de Documento</label>
                @foreach ($data['victim_documents'] as $digit)
                <div style="
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            position: relative;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            line-height: 14px;
            box-sizing: border-box;
        ">
                    <!-- Marca de agua (letra M, F, etc.) -->
                    <span style="color: #ccc; position: absolute; left: 0; right: 0; top: 0; bottom: 0; text-align: center; line-height: 14px;">
                        {{ $digit }}
                    </span>

                    <!-- Si se cumple la condición, sobreescribe con X -->
                    @if ($digit == $data['victim_document'])
                    <span style="position: relative; z-index: 2; color: black;">X</span>
                    @endif
                </div>
                @endforeach
                <label style="width: 100px; margin-left: 70px;">No. Documento</label>
                <input type="text" value="{{ $data['patient_document'] }}" style="width: 150px; height: 14px;">
            </div>
            <div class="form-group">
                <span style="display: inline-block; width: 105px; vertical-align: middle;">Fecha De Nacimiento:</span>
                @foreach ($data['patient_birth_date'] as $digit)
                <input type="text" value="{{ $digit }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach
                <label style="width: 20px; margin-left: 70px;">Sexo</label>
                @foreach ($data['select_sexo'] as $digit)
                <div style="
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            position: relative;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            line-height: 14px;
            box-sizing: border-box;
        ">
                    <!-- Marca de agua (letra M, F, etc.) -->
                    <span style="color: #ccc; position: absolute; left: 0; right: 0; top: 0; bottom: 0; text-align: center; line-height: 14px;">
                        {{ $digit }}
                    </span>

                    <!-- Si se cumple la condición, sobreescribe con X -->
                    @if ($digit == $data['sexo_code'])
                    <span style="position: relative; z-index: 2; color: black;">X</span>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="form-group">
                <label style="width: 97px;">Dirección Residencia</label>
                <input type="text" value="{{ $data['patient_recidence_address'] }}" style="width: 520px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 98px;">Departamento</label>
                <input type="text" value="{{ $data['patient_department_name'] }}" style="width: 130px; height: 14px;">
                <label style="width: 30px; margin-left: 70px;">Cod.</label>
                <input type="text" value="{{ $data['patient_department_code'] }}" style="width: 50px; height: 14px;">
                <label style="width: 50px; margin-left: 30px;">Teléfono</label>
                <input type="text" value="{{ $data['patient_phone'] }}" style="width: 125px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 98px;">Municipio</label>
                <input type="text" value="{{ $data['patient_municipio_name'] }}" style="width: 160px; height: 14px;">
                <label style="width: 30px; margin-left: 40px;">Cod.</label>
                <input type="text" value="{{ $data['patient_municipio_code'] }}" style="width: 70px; height: 14px;">
            </div>
            <div class="form-group" style="display: flex; align-items: center; font-size: 11px;">
                <label style="width: 200px;">Condición del Accidentado</label>
                @foreach ($data['victim_conditions'] as $option)
                <div style="
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            position: relative;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            line-height: 14px;
            box-sizing: border-box;
        ">
                    <!-- Marca de agua (letra M, F, etc.) -->
                    @if ($option['value'] == $data['patient_condition'])
                    <span style="position: relative; z-index: 2; color: black;">X</span>
                    @endif
                </div>
                <label style="width: 60px; margin-left: 5px;">{{ $option['label']}}</label>
                @endforeach
            </div>
        </div>

        <div class="section">
            <h2>III. DATOS DEL SITIO DONDE OCURRIÓ EL EVENTO CATASTRÓFICO O EL ACCIDENTE DE TRÁNSITO</h2>
            <div class="form-group">
                <label style="width: 100px;">Naturaleza del evento</label>
                <label style="width: 50px; margin-left: 20px;">Accidente de tránsito</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_001" ? 'X' : '' }}" style="width: 14px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Naturales:</label>
                <label style="width: 50px; margin-left: 20px;">Sismo</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_002" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                <label style="width: 70px; margin-left: 20px;">Maremoto</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_003" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                <label style="width: 70px; margin-left: 20px;">Erupciones Volcánicas</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_004" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                <label style="width: 70px; margin-left: 20px;">Huracán</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_016" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;"></label>
                <label style="width: 50px; margin-left: 20px;">Inundaciones</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_006" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                <label style="width: 70px; margin-left: 20px;">Avalancha</label>
                <input type="text" value="{{ $data['eventNature']->value === 'EVENT_NATURE_007' ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                <label style="width: 70px; margin-left: 20px;">Deslizamiento de Tierra</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_005" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                <label style="width: 70px; margin-left: 20px;">Incendio Natural</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_008" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;"></label>
                <label style="width: 50px; margin-left: 20px;">Rayo</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_018" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                <label style="width: 70px; margin-left: 20px;">Vendaval</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_019" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                <label style="width: 70px; margin-left: 20px;">Tornado</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_020" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Terroristas:</label>
                <label style="width: 50px; margin-left: 20px;">Explosión</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_009" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                <label style="width: 70px; margin-left: 20px;">Masacre</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_013" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                <label style="width: 70px; margin-left: 20px;">Mina Antipersonal</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_015" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                <label style="width: 70px; margin-left: 20px;">Combate</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_011" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;"></label>
                <label style="width: 50px; margin-left: 20px;">Incendio</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_010" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                <label style="width: 70px; margin-left: 20px;">Ataques a Municipios</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_012" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
            </div>
            <div class="form-group">
                <label style="width: 50px;">Otro</label>
                <input type="text" value="{{ $data['eventNature']->value === "EVENT_NATURE_017" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                <span style="display: inline-block; width: 50px; vertical-align: middle; margin-left: 10px;">Cuál?</span>
                <input type="text" value="{{ $data['otherEventDescription'] }}" style="width: 475px; height: 14px; border: 1px solid #000; vertical-align: middle; box-sizing: border-box;">
            </div>
            <div class="form-group">
                <label style="width: 137px;">Dirección de la ocurrencia</label>
                <input type="text" value="{{ $data['eventOccurrenceAddress'] }}" style="width: 475px; height: 14px;">
            </div>
            <div class="form-group">
                <span style="display: inline-block; width: 145px; vertical-align: middle;">Fecha Evento/Accidente</span>
                @foreach ($data['eventOccurrenceDate'] as $digit)
                <input type="text" value="{{ $digit }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach
                <span style="display: inline-block; width: 70px; vertical-align: middle; margin-left: 80px;">Hora</span>
                @foreach ($data['eventOccurrenceTime'] as $digit)
                <input type="text" value="{{ $digit }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach
            </div>
            <div class="form-group">
                <label style="width: 98px;">Departamento</label>
                <input type="text" value="{{ $data['eventDepartment_name'] }}" style="width: 130px; height: 14px;">
                <label style="width: 30px; margin-left: 70px;">Cod.</label>
                <input type="text" value="{{ $data['eventDepartment_code'] }}" style="width: 50px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 98px;">Municipio</label>
                <input type="text" value="{{ $data['eventMunicipalityCode_name'] }}" style="width: 160px; height: 14px;">
                <label style="width: 30px; margin-left: 40px;">Cod.</label>
                <input type="text" value="{{ $data['eventMunicipalityCode_code'] }}" style="width: 70px; height: 14px;">
                <label style="width: 30px; margin-left: 30px;">Zona</label>
                @foreach ($data['eventZones'] as $option)
                <div style="
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            position: relative;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            line-height: 14px;
            box-sizing: border-box;
        ">
                    <!-- Marca de agua (letra M, F, etc.) -->
                    <span style="color: #ccc; position: absolute; left: 0; right: 0; top: 0; bottom: 0; text-align: center; line-height: 14px;">
                        {{ $option['label'] }}
                    </span>

                    <!-- Si se cumple la condición, sobreescribe con X -->
                    @if ($option['value'] == $data['eventZone'])
                    <span style="position: relative; z-index: 2; color: black;">X</span>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="form-group">
                <label style="width: 300px;">Descripción Breve del Evento Catastrófico o Accidente de Tránsito</label><br>
                <label style="width: 250px;">Enuncie las principales características del evento / accidente:</label>
                <div style="border-bottom: 1px solid #000; height: 16px; width: 100%;"></div>
                <div style="border-bottom: 1px solid #000; height: 16px; width: 100%; margin-top: 2px;"></div>
                <div style="border-bottom: 1px solid #000; height: 16px; width: 100%; margin-top: 2px;"></div>
            </div>
        </div>

        <div class="section">
            <h2>IV. DATOS DEL VEHÍCULO DE ACCIDENTE DE TRÁNSITO</h2>
            <div class="form-group">
                <label style="margin-top: 20px; width: 100%;"></label>
                <label style="width: 50px; margin-left: 40px;">Asegurado</label>
                <input type="text" value="{{ $data['insurance_statuse'] == '1' ? 'X' : '' }}" style="width: 14px; height: 14px;">
                <label style="width: 70px; margin-left: 20px;">No Asegurado</label>
                <input type="text" value="{{ $data['insurance_statuse'] == '2' ? 'X' : '' }}" style="width: 14px; height: 14px;">
                <label style="width: 70px; margin-left: 20px;">Vehículo fantasma</label>
                <input type="text" value="{{ $data['insurance_statuse'] == '3' ? 'X' : '' }}" style="width: 14px; height: 14px;">
                <label style="width: 70px; margin-left: 20px;">Póliza Falsa</label>
                <input type="text" value="{{ $data['insurance_statuse'] == '4' ? 'X' : '' }}" style="width: 14px; height: 14px;">
                <label style="width: 70px; margin-left: 20px;">Vehículo en fuga</label>
                <input type="text" value="{{ $data['insurance_statuse'] == '5' ? 'X' : '' }}" style="width: 14px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 50px;">Marca</label>
                <input type="text" value="{{ $data['vehicleBrand'] }}" style="width: 250px; height: 14px;">
                <label style="width: 30px; margin-left: 70px;">Placa</label>
                <input type="text" value="{{ $data['vehiclePlate'] }}" style="width: 70px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 140px;">Tipo de Servicio:</label>
                <label style="width: 40px; margin-left: 20px;">Particular</label>
                <input type="text" style="width: 14px; height: 14px;">
                <label style="width: 40px; margin-left: 20px;">Público</label>
                <input type="text" style="width: 14px; height: 14px;">
                <label style="width: 40px; margin-left: 20px;">Oficial</label>
                <input type="text" style="width: 14px; height: 14px;">
                <label style="width: 100px; margin-left: 20px;">Vehiculo de emergencia</label>
                <input type="text" style="width: 14px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 80px;"></label>
                <label style="width: 160px; margin-left: 20px;">Vehiculo de servicio diplomatico o consular</label>
                <input type="text" style="width: 14px; height: 14px;">
                <label style="width: 100px; margin-left: 20px;">Vehiculo de transporte masivo</label>
                <input type="text" style="width: 14px; height: 14px; margin-left: 10px;">
                <label style="width: 70px; margin-left: 20px;"> Vehiculo escolar</label>
                <input type="text" style="width: 14px; height: 14px; margin-left: 10px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Código de la aseguradora</label>
                <input type="text" value="{{ $data['insurance_code'] }}" style="width: 250px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 60px;">No. de la Póliza</label>
                <input type="text" value="{{ $data['policy_number'] }}" style="width: 250px; height: 14px;">
                <label style="width: 100px; margin-left: 110px;">Intervención de autoridad</label>
                <label style="width: 10px;">Si</label>
                <input type="text" value="{{ $data['authorityIntervention_code'] === "YES_NO_001" ? 'X' : '' }}" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                <label style="width: 10px; margin-left: 20px;">No</label>
                <input type="text" value="{{ $data['authorityIntervention_code'] === "YES_NO_002" ? 'X' : '' }}" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
            </div>
            <div class="form-group">
                <label style="width: 40px;">Vigencia</label>
                <label style="width: 30px;">Desde</label>
                @foreach ($data['incident_start_date'] as $digit)
                <input type="text" value="{{ $digit }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach
                <label style="width: 30px; margin-left: 10px;">Hasta</label>
                @foreach ($data['incident_end_date'] as $digit)
                <input type="text" value="{{ $digit }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach
                <label style="width: 100px; margin-left: 15px;">Cobro Excedente Póliza</label>
                <label style="width: 10px;">Si</label>
                <input type="text" value="{{ $data['policyExcessCharge'] === "YES_NO_001" ? 'X' : '' }}" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                <label style="width: 10px; margin-left: 20px;">No</label>
                <input type="text" value="{{ $data['policyExcessCharge'] === "YES_NO_002" ? 'X' : '' }}" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
            </div>
        </div>

        <div class="section">
            <h2>V. DATOS DEL PROPIETARIO DEL VEHÍCULO </h2>
            <div class="form-group">
                <label style="width: 50px;">1er Apellido</label>
                <input type="text" value="{{ $data['ownerFirstLastName'] }}" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Apellido</label>
                <input type="text" value="{{ $data['ownerSecondLastName'] }}" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 50px;">1er Nombre</label>
                <input type="text" value="{{ $data['ownerFirstName'] }}" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Nombre</label>
                <input type="text" value="{{ $data['ownerSecondName'] }}" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Tipo de Documento</label>
                @foreach ($data['owner_documents'] as $digit)
                <div style="
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            position: relative;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            line-height: 14px;
            box-sizing: border-box;
        ">
                    <!-- Marca de agua (letra M, F, etc.) -->
                    <span style="color: #ccc; position: absolute; left: 0; right: 0; top: 0; bottom: 0; text-align: center; line-height: 14px;">
                        {{ $digit }}
                    </span>

                    <!-- Si se cumple la condición, sobreescribe con X -->
                    @if ($digit == $data['owner_document'])
                    <span style="position: relative; z-index: 2; color: black;">X</span>
                    @endif
                </div>
                @endforeach
                <label style="width: 100px; margin-left: 90px;">No. Documento</label>
                <input type="text" value="{{ $data['ownerDocumentNumber'] }}" style="width: 150px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 97px;">Dirección Residencia</label>
                <input type="text" value="{{ $data['ownerResidenceAddress'] }}" style="width: 520px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 98px;">Departamento</label>
                <input type="text" value="{{ $data['ownerResidenceDepartment_name'] }}" style="width: 130px; height: 14px;">
                <label style="width: 30px; margin-left: 70px;">Cod.</label>
                <input type="text" value="{{ $data['ownerResidenceDepartment_code'] }}" style="width: 50px; height: 14px;">
                <label style="width: 50px; margin-left: 30px;">Teléfono</label>
                <input type="text" value="{{ $data['ownerResidencePhone'] }}" style="width: 125px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 98px;">Municipio</label>
                <input type="text" value="{{ $data['ownerResidenceMunicipality_name'] }}" style="width: 160px; height: 14px;">
                <label style="width: 30px; margin-left: 40px;">Cod.</label>
                <input type="text" value="{{ $data['ownerResidenceMunicipality_code'] }}" style="width: 70px; height: 14px;">
            </div>
        </div>
    </div>

    <div class="form-container" style="page-break-before: always;">
        <div class="header">

            <!-- Imagen alineada a la izquierda absoluta -->
            <div style="position: absolute; top: 10; left: 10;">
                <img src="{{ public_path('storage/escudo_colombia.jpg') }}" alt="logo" style="max-width: 100px; max-height: 100px;">
            </div>

            <!-- Contenido centrado desde margen izquierdo -->
            <h1 style="margin-left: 10;">REPÚBLICA DE COLOMBIA</h1><br />
            <h1 style="margin-left: 10;">MINISTERIO DE SALUD Y PROTECCIÓN SOCIAL</h1><br />
            <p style="margin-left: 40;">
                FORMULARIO ÚNICO DE RECLAMACIÓN DE LAS INSTITUCIONES PRESTADORAS DE SERVICIOS DE SALUD POR SERVICIOS PRESTADOS A VICTIMAS
            </p>
            <p style="margin-left: 10;">PERSONAS JURIDICAS - FURIPS</p>
        </div>

        <div class="section">
            <h2>
                VI. DATOS DEL CONDUCTOR DEL VEHÍCULO INVOLUCRADO EN EL ACCIDENTE DE TRANSITO
            </h2>
            <div class="form-group">
                <label style="width: 50px;">1er Apellido</label>
                <input type="text" value="{{ $data['driverFirstLastName'] }}" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Apellido</label>
                <input type="text" value="{{ $data['driverSecondLastName'] }}" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 50px;">1er Nombre</label>
                <input type="text" value="{{ $data['driverFirstName'] }}" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Nombre</label>
                <input type="text" value="{{ $data['driverSecondName'] }}" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Tipo de Documento</label>
                @foreach ($data['driver_documents'] as $digit)
                <div style="
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            position: relative;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            line-height: 14px;
            box-sizing: border-box;
        ">
                    <!-- Marca de agua (letra M, F, etc.) -->
                    <span style="color: #ccc; position: absolute; left: 0; right: 0; top: 0; bottom: 0; text-align: center; line-height: 14px;">
                        {{ $digit }}
                    </span>

                    <!-- Si se cumple la condición, sobreescribe con X -->
                    @if ($digit == $data['driver_document'])
                    <span style="position: relative; z-index: 2; color: black;">X</span>
                    @endif
                </div>
                @endforeach
                <label style="width: 100px; margin-left: 90px;">No. Documento</label>
                <input type="text" value="{{ $data['driverDocumentNumber'] }}" style="width: 150px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 97px;">Dirección Residencia</label>
                <input type="text" value="{{ $data['driverResidenceAddress'] }}" style="width: 520px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 98px;">Departamento</label>
                <input type="text" value="{{ $data['driverResidenceDepartment_name'] }}" style="width: 130px; height: 14px;">
                <label style="width: 30px; margin-left: 70px;">Cod.</label>
                <input type="text" value="{{ $data['driverResidenceDepartment_code'] }}" style="width: 50px; height: 14px;">
                <label style="width: 50px; margin-left: 30px;">Teléfono</label>
                <input type="text" value="{{ $data['driverResidencePhone'] }}" style="width: 125px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 98px;">Municipio</label>
                <input type="text" value="{{ $data['driverResidenceMunicipality_name'] }}" style="width: 160px; height: 14px;">
                <label style="width: 30px; margin-left: 40px;">Cod.</label>
                <input type="text" value="{{ $data['driverResidenceMunicipality_code'] }}" style="width: 70px; height: 14px;">
            </div>
        </div>

        <div class="section">
            <h2>VII. DATOS DE REMISION</h2>
            <div class="form-group">
                <label style="width: 100px; margin-left: 15px;">Tipo Referencia</label>
                <label style="width: 50px;">Remisión</label>
                <input type="text" value="{{ $data['referenceType']->value === "REFERENCE_TYPE_001" ? 'X' : '' }}" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                <label style="width: 80px; margin-left: 20px;">Orden de Servicio</label>
                <input type="text" value="{{ $data['referenceType']->value === "REFERENCE_TYPE_002" ? 'X' : '' }}" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Fecha remisión</label>
                @foreach ($data['referralDate'] as $digit)
                <input type="text" value="{{ $digit }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach
                <span style="display: inline-block; width: 50px; vertical-align: middle; margin-left: 20px;">a las</span>
                @foreach ($data['departureTime'] as $digit)
                <input type="text" value="{{ $digit }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach
            </div>
            <div class="form-group">
                <label style="width: 100px;">Prestador que remite</label>
                <input type="text" value="{{ $data['referringHealthProviderCode_name'] }}" style="width: 512px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Código de inscripción</label>
                <input type="text" value="{{ $data['referringHealthProviderCode_code'] }}" style="width: 200px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Profesional que remite</label>
                <input type="text" value="{{ $data['referringProfessional'] }}" style="width: 260px; height: 14px;">
                <label style="width: 40px; margin-left: 30px;"> Cargo</label>
                <input type="text" value="{{ $data['referringPersonPosition'] }}" style="width: 165px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Fecha aceptación</label>
                @foreach ($data['admissionDate'] as $digit)
                <input type="text" value="{{ $digit }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach
                <span style="display: inline-block; width: 50px; vertical-align: middle; margin-left: 20px;">a las</span>
                @foreach ($data['admissionTime'] as $digit)
                <input type="text" value="{{ $digit }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach
            </div>
            <div class="form-group">
                <label style="width: 100px;">Prestador que recibe</label>
                <input type="text" value="{{ $data['receivingHealthProviderCode_name'] }}" style="width: 512px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Código de inscripción</label>
                <input type="text" value="{{ $data['receivingHealthProviderCode_code'] }}" style="width: 200px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Profesional que recibe</label>
                <input type="text" value="{{ $data['receivingProfessional'] }}" style="width: 260px; height: 14px;">
                <label style="width: 40px; margin-left: 30px;"> Cargo</label>
                <input type="text" value="{{ $data['referralRecipientCharge'] }}" style="width: 165px; height: 14px;">
            </div>
        </div>

        <div class="section">
            <h2> VIII. AMPARO DE TRANSPORTE Y MOVILIZACION DE LA VICTIMA</h2>
            <div class="form-group">
                <label style="width: 100%;">Diligenciar únicamente para el transporte desde el sitio del evento hasta la primera IPS (Transporte Primario)</label>
                <label style="width: 100px;">Datos de Vehículo </label>
                <label style="width: 50px;">Placa No. </label>
                <input type="text" value="{{ $data['interinstitutionalTransferAmbulancePlate'] }}" style="width: 100px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 100px;">Transporto la víctima desde</label>
                <input type="text" value="{{ $data['victimTransportFromEventSite'] }}" style="width: 260px; height: 14px;">
                <label style="width: 40px; margin-left: 30px;">Hasta</label>
                <input type="text" value="{{ $data['victimTransportToEnd'] }}" style="width: 165px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Tipo de Transporte</label>
                <label style="width: 80px;">Ambulancia Básica</label>
                <input type="text" value="{{ isset($data['transportServiceType']) ? $data['transportServiceType']->value === "TRANSPORT_SERVICE_TYPE_001" ? 'X' : '' : '' }}" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                <label style="width: 80px; margin-left: 20px;">Ambulancia Medicada</label>
                <input type="text" value="{{isset($data['transportServiceType']) ?  $data['transportServiceType']->value === "TRANSPORT_SERVICE_TYPE_002" ? 'X' : '' :'' }}" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                <label style="width: 110px; margin-left: 40px;">Lugar donde recoge la Victima</label>
                <label style="width: 30px; margin-left: 30px;">Zona</label>
                @foreach ($data['pickupZones'] as $option)
                <div style="
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            position: relative;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            line-height: 14px;
            box-sizing: border-box;
        ">
                    <!-- Marca de agua (letra M, F, etc.) -->
                    <span style="color: #ccc; position: absolute; left: 0; right: 0; top: 0; bottom: 0; text-align: center; line-height: 14px;">
                        {{ $option['label'] }}
                    </span>

                    <!-- Si se cumple la condición, sobreescribe con X -->
                    @if ($option['value'] == $data['victimPickupZone'])
                    <span style="position: relative; z-index: 2; color: black;">X</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <div class="section">
            <h2>IX. CERTIFICADO DE LA ATENCIÓN MEDICA DELA VICTIMA COMO PRUEBA DEL ACCIDENTE O EVENTO</h2>
            <div class="form-group">
                <label style="width: 100px;">Fecha de ingreso</label>
                @foreach ($data['medicalAdmissionDate'] as $digit)
                <input type="text" value="{{ $digit }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach
                <span style="display: inline-block; width: 50px; vertical-align: middle; margin-left: 20px;">a las</span>
                @foreach ($data['medicalAdmissionTime'] as $digit)
                <input type="text" value="{{ $digit }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach
            </div>
            <div class="form-group">
                <label style="width: 100px;">Fecha de egreso</label>
                @foreach ($data['medicalDischargeDate'] as $digit)
                <input type="text" value="{{ $digit }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach
                <span style="display: inline-block; width: 50px; vertical-align: middle; margin-left: 20px;">a las</span>
                @foreach ($data['medicalDischargeTime'] as $digit)
                <input type="text" value="{{ $digit }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                @endforeach
            </div>

            <div class="form-group">
                <label style="width: 150px;">Código Diagnóstico principal de Ingreso</label>
                <input type="text" value="{{ $data['primaryAdmissionDiagnosis_code'] }}" style="width: 116px; height: 14px;">
                <label style="width: 150px; margin-left: 60px;"> Código Diagnóstico principal de Egreso</label>
                <input type="text" value="{{ $data['associatedAdmissionDiagnosisCode1_code'] }}" style="width: 116px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 150px;">Otro código Diagnóstico principal de Ingreso</label>
                <input type="text" value="{{ $data['associatedAdmissionDiagnosisCode2_code'] }}" style="width: 116px; height: 14px;">
                <label style="width: 150px; margin-left: 60px;">Otro código Diagnóstico principal de Egreso</label>
                <input type="text" value="{{ $data['primaryDischargeDiagnosisCode_code'] }}" style="width: 116px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 150px;">Otro código Diagnóstico principal de Ingreso</label>
                <input type="text" value="{{ $data['associatedDischargeDiagnosisCode1_code'] }}" style="width: 116px; height: 14px;">
                <label style="width: 150px; margin-left: 60px;">Otro código Diagnóstico principal de Egreso</label>
                <input type="text" value="{{ $data['associatedDischargeDiagnosisCode2_code'] }}" style="width: 116px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 50px;">1er Apellido</label>
                <input type="text" value="{{ $data['doctorFirstLastName'] }}" style="width: 241px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Apellido</label>
                <input type="text" value="{{ $data['doctorSecondLastName'] }}" style="width: 241px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 50px;">1er Nombre</label>
                <input type="text" value="{{ $data['doctorFirstName'] }}" style="width: 241px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Nombre</label>
                <input type="text" value="{{ $data['doctorSecondName'] }}" style="width: 241px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 130px;">Tipo de Documento</label>
                @foreach ($data['doctor_documents'] as $digit)
                <div style="
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            position: relative;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            line-height: 14px;
            box-sizing: border-box;
        ">
                    <!-- Marca de agua (letra M, F, etc.) -->
                    <span style="color: #ccc; position: absolute; left: 0; right: 0; top: 0; bottom: 0; text-align: center; line-height: 14px;">
                        {{ $digit }}
                    </span>

                    <!-- Si se cumple la condición, sobreescribe con X -->
                    @if ($digit == $data['doctor_document'])
                    <span style="position: relative; z-index: 2; color: black;">X</span>
                    @endif
                </div>
                @endforeach
                <label style="width: 100px; margin-left: 90px;">No. Documento</label>
                <input type="text" value="{{ $data['doctorIdNumber'] }}" style="width: 150px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 355px;"></label>
                <label style="width: 100px;">Número de registro médico</label>
                <input type="text" value="{{ $data['doctorRegistrationNumber'] }}" style="width: 150px; height: 14px;">
            </div>
        </div>

        <div class="section" style="page-break-before: always;">
            <h2>X. AMPAROS QUE RECLAMA</h2>
            <table style="margin-top: 5px;">
                <tr>
                    <td></td>
                    <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Valor total facturado</td>
                    <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Valor reclamado al FOSYGA</td>
                </tr>
                <tr>
                    <td style="width: 250px; border: 1px solid #000;">Gastos médicos quirúrgicos</td>
                    <td style="width: 150px; border: 1px solid #000; text-align: right;">{{ $data['totalBilledMedicalSurgical']  }}</td>
                    <td style="width: 150px; border: 1px solid #000; text-align: right;">{{ $data['totalClaimedMedicalSurgical']  }}</td>
                </tr>
                <tr>
                    <td style="width: 250px; border: 1px solid #000;">Gastos de transporte y movilización de la víctima</td>
                    <td style="width: 150px; border: 1px solid #000; text-align: right;">{{ $data['totalBilledTransport']  }}</td>
                    <td style="width: 150px; border: 1px solid #000; text-align: right;">{{ $data['totalClaimedTransport']  }}</td>
                </tr>
            </table>
            <p style="margin-left: 0; margin-top: 5px; font-size: 7px; text-align: justify; width: 655px;">
                El total facturado y reclamado descrito en este numeral se debe detallar y hacer descripcion de las actividades, procedimientos, medicamentos, insumos, suministros, materiales, dentro del anexo
                técnico numero 2
            </p>
        </div>
        <div class="section">
            <h2>XI. DECLARACIONES DE LA INSTITUCION PRESTADORA DE SERVICIOS DE SALUD</h2>
            <p style="margin-left: 0; margin-top: 5px; font-size: 7px; text-align: justify; width: 655px;">
                Como representante legal o Gerente de la Institución Prestadora de Servicios de Salud, declaró bajo la gavedad de juramento que toda la información contenidad en este formulario es cierta y
                podrá se verificada por la Compañía de Seguros, por la Dirección de Administracion de Fondos de la Protección Social o quien haga sus veces, por el Administrador Fiduciario del Fondo de
                Solidaridad y Garantía Fosyga, por la Superintendencia Nacional de Salud o la Contraloria General de la República de no ser así, acepto todas las consecuencias legales que produzca esta
                situación. Adicionalmente, manifiesto que la reclamación no ha sido presentada con anterioridad ni se ha recibido pago alguno por las sumas reclamadas.
            </p>
            <div style="margin-left: 0; margin-top: 5px; font-size: 8px;">
                <p style="display: inline-block; border-bottom: 1px solid #000; height: 16px; width: 200px; padding-left: 10px; margin-left: 20px; vertical-align: top;"></p>
                <p style="display: inline-block; border-bottom: 1px solid #000; height: 16px; width: 200px; padding-left: 10px; margin-left: 180px; vertical-align: top;"></p>
                <div style="margin-top: 2px;">
                    <span style="display: inline-block; width: 100px; margin-left: 100px;">NOMBRE</span>
                    <span style="display: inline-block; width: 200px; margin-left: 220px;">FIRMA DEL REPRESENTANTE LEGAL O GERENTE</span>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
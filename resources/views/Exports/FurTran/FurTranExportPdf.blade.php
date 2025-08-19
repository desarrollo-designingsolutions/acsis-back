<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Único de Reclamaciones - FURIPS (Parte A)</title>
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
            background-color: rgb(229, 231, 208);
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
                FORMULARIO ÚNICO DE RECLAMACIÓN DE RECLAMACION DE GASTOS DE TRANSPORTE Y MOVILIZACION DE VICTIMAS - FURTRAN
            </p>
        </div>
        <div style="display: flex; flex-direction: column; font-size: 8px; margin-top: 5px;">

            <!-- Fila 1: Fecha Entrega, RG, No. Radicado -->
            <div style="margin-bottom: 5px; font-size: 8px;">

                <!-- Fecha Entrega -->
                <span style="display: inline-block; width: 70px; vertical-align: middle;">Fecha Entrega:</span>
                @for ($i = 0; $i < 8; $i++)
                    <input type="text" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endfor

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
                <span style="display: inline-block; width: 130px; vertical-align: middle; margin-left: 10px;">No. Factura</span>
                <input type="text" value="{{ $data['invoice_number'] }}"
                    style="width: 150px; height: 10px; border: 1px solid #000; vertical-align: middle; box-sizing: border-box;">
            </div>

        </div>


        <div class="section">
            <h2>
                I. DATOS DEL TRANSPORTADOR (si es persona natural diligenciar los campos referentes a nombres y apellidos)
            </h2>
            <div class="form-group">
                <label style="width: 200px;">Nombre Empresa de Transporte Especial o Reclamante</label>
                <input type="text" style="width: 420px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 200px;">Código de habilitación Empresa de Transporte Especial</label>
                <input type="text" style="width: 250px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 50px;">1er Apellido</label>
                <input type="text" value="{{ $data['firstLastNameClaimant'] }}" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Apellido</label>
                <input type="text" value="{{ $data['secondLastNameClaimant'] }}" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 50px;">1er Nombre</label>
                <input type="text" value="{{ $data['firstNameClaimant'] }}" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Nombre</label>
                <input type="text" value="{{ $data['secondNameClaimant'] }}" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label style="width: 100px;">Tipo de Documento</label>
                    @foreach ($data['claimanid_documents'] as $digit)
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
                        @if ($digit == $data['claimanid_document'])
                        <span style="position: relative; z-index: 2; color: black;">X</span>
                        @endif
                    </div>
                    @endforeach
                    <label style="width: 100px; margin-left: 90px;">No. Documento</label>
                    <input type="text" value="{{ $data['claimantIdNumber'] }}" style="width: 150px; height: 14px;">
                </div>
                <div class="form-group">
                    <label style="width: 100px;">Tipo de Vehículo o de Servicio de ambulancia:</label>
                    <label style="width: 50px; margin-left: 20px;">Ambulancia básica</label>
                    <input type="text" value="{{ $data['vehicleServiceType']?->value === "VEHICLE_SERVICE_TYPE_001" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                    <label style="width: 70px; margin-left: 20px;">Ambulancia medicalizada</label>
                    <input type="text" value="{{ $data['vehicleServiceType']?->value === "VEHICLE_SERVICE_TYPE_002" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                    <label style="width: 70px; margin-left: 20px;">Particular</label>
                    <input type="text" value="{{ $data['vehicleServiceType']?->value === "VEHICLE_SERVICE_TYPE_003" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                    <label style="width: 70px; margin-left: 20px;">Público</label>
                    <input type="text" value="{{ $data['vehicleServiceType']?->value === "VEHICLE_SERVICE_TYPE_004" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                </div>
                <div class="form-group">
                    <label style="width: 100px;"></label>
                    <label style="width: 50px; margin-left: 20px;">Oficial</label>
                    <input type="text" value="{{ $data['vehicleServiceType']?->value === "VEHICLE_SERVICE_TYPE_005" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                    <label style="width: 70px; margin-left: 20px;">De emergencia</label>
                    <input type="text" value="{{ $data['vehicleServiceType']?->value === "VEHICLE_SERVICE_TYPE_006" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                    <label style="width: 70px; margin-left: 20px;">Diplomático o consular</label>
                    <input type="text" value="{{ $data['vehicleServiceType']?->value === "VEHICLE_SERVICE_TYPE_007" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                    <label style="width: 70px; margin-left: 20px;">Transporte masivo</label>
                    <input type="text" value="{{ $data['vehicleServiceType']?->value === "VEHICLE_SERVICE_TYPE_008" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                </div>
                <div class="form-group">
                    <label style="width: 120px;"></label>
                    <label style="width: 50px;">Escolar</label>
                    <input type="text" value="{{ $data['vehicleServiceType']?->value === "VEHICLE_SERVICE_TYPE_009" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                </div>

                <div class="form-group">
                    <label style="width: 50px; margin-left: 5px;">Placa No. </label>
                    <input type="text" value="{{ $data['vehiclePlate'] }}" style="width: 100px; height: 14px;">
                </div>

                <div class="form-group">
                    <label style="width: 98px;">Departamento</label>
                    <input type="text" value="{{ $data['claimantDepartment_name'] }}" style="width: 130px; height: 14px;">
                    <label style="width: 30px; margin-left: 70px;">Cod.</label>
                    <input type="text" value="{{ $data['claimantDepartment_code'] }}" style="width: 50px; height: 14px;">
                    <label style="width: 50px; margin-left: 30px;">Teléfono</label>
                    <input type="text" value="{{ $data['claimantPhone'] }}" style="width: 125px; height: 14px;">
                </div>

                <div class="form-group">
                    <label style="width: 98px;">Municipio</label>
                    <input type="text" value="{{ $data['claimantMunicipality_name'] }}" style="width: 160px; height: 14px;">
                    <label style="width: 30px; margin-left: 40px;">Cod.</label>
                    <input type="text" value="{{ $data['claimantMunicipality_code'] }}" style="width: 70px; height: 14px;">
                </div>
            </div>

            <div class="section">
                <h2>II. DATOS DE LA VICTIMA TRANSLADADA</h2>
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
                </div>
                <div class="form-group">
                    <table style="margin-top: 5px;">
                        <tr>
                            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No. Doc</td>
                            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No. Documento</td>
                            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Primer Nombre</td>
                            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Segundo Nombre</td>
                            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Primer Apellido</td>
                            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Segundo Apellido</td>
                        </tr>
                        <tr>
                            <td style="width: 50px; height: 10px; border: 1px solid #000;">{{ $data['victim_document'] }}</td>
                            <td style="width: 110px; height: 10px; border: 1px solid #000;">{{ $data['patient_document'] }}</td>
                            <td style="width: 110px; height: 10px; border: 1px solid #000;">{{ $data['patient_first_name'] }}</td>
                            <td style="width: 110px; height: 10px; border: 1px solid #000;">{{ $data['patient_second_name'] }}</td>
                            <td style="width: 110px; height: 10px; border: 1px solid #000;">{{ $data['patient_first_surname'] }}</td>
                            <td style="width: 110px; height: 10px; border: 1px solid #000;">{{ $data['patient_second_surname'] }}</td>
                        </tr>
                    </table>
                </div>
                <div class="form-group">
                    <span style="display: inline-block; width: 105px; vertical-align: middle;">Fecha De Nacimiento:</span>
                    @foreach ($data['patient_birth_date'] as $digit)
                    <input type="text" value="{{ $digit }}" maxlength="1"
                        style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endforeach
                    <label style="width: 20px; margin-left: 80px;">Sexo</label>
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
            </div>

            <div class="section">
                <h2>III. IDENTIFICACION DEL TIPO DE EVENTO</h2>
                <div class="form-group">
                    <label style="width: 100%;">Tipo de evento:</label>
                    <label style="width: 100px; margin-left: 40px;">1.Accidente de tránsito:</label>
                    <input type="text" style="width: 14px; height: 14px;" value="{{ $data['eventType']?->value === "EVENT_TYPE_001" ? 'X' : '' }}">
                    <label style="width: 100px; margin-left: 20px;">2.Evento catastrófico de origen Natural:</label>
                    <input type="text" style="width: 14px; height: 14px;" value="{{ $data['eventType']?->value === "EVENT_TYPE_002" ? 'X' : '' }}">
                    <label style="width: 100px; margin-left: 20px;">3.Evento terrorista:</label>
                    <input type="text" style="width: 14px; height: 14px;" value="{{ $data['eventType']?->value === "EVENT_TYPE_003" ? 'X' : '' }}">
                </div>
            </div>

            <div class="section">
                <h2>IV. LUGAR EN EL QUE SE RECOGE LA VICTIMA</h2>
                <div class="form-group">
                    <label style="width: 97px;">Dirección Residencia</label>
                    <input type="text" value="{{ $data['pickupAddress'] }}" style="width: 520px; height: 14px;">
                </div>
                <div class="form-group">
                    <label style="width: 98px;">Departamento</label>
                    <input type="text" value="{{ $data['pickupDepartment_name'] }}" style="width: 130px; height: 14px;">
                    <label style="width: 30px; margin-left: 70px;">Cod.</label>
                    <input type="text" value="{{ $data['pickupDepartment_code'] }}" style="width: 50px; height: 14px;">
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
                        @if ($option['value'] == $data['pickupZone'])
                        <span style="position: relative; z-index: 2; color: black;">X</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                <div class="form-group">
                    <label style="width: 98px;">Municipio</label>
                    <input type="text" value="{{ $data['pickupMunicipality_name'] }}" style="width: 160px; height: 14px;">
                    <label style="width: 30px; margin-left: 40px;">Cod.</label>
                    <input type="text" value="{{ $data['pickupMunicipality_code'] }}" style="width: 70px; height: 14px;">
                </div>
            </div>

            <div class="section">
                <h2>V. CERTIFICADO DE TRASLADO DE VICTIMAS</h2>
                <div class="form-group">
                    <label style="width: 600px;">La institución prestadora de servicios de salud certifica que la entidad de transporte especial o persona natural efectuó el translado de la victima a esta IPS</label>
                </div>
                <div class="form-group">
                    <label style="width: 100px;">El día</label>
                    @foreach ($data['transferDate'] as $digit)
                    <input type="text" value="{{ $digit }}" maxlength="1"
                        style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endforeach
                    <span style="display: inline-block; width: 50px; vertical-align: middle; margin-left: 20px;">a las</span>
                    @foreach ($data['transferTime'] as $digit)
                    <input type="text" value="{{ $digit }}" maxlength="1"
                        style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endforeach
                </div>
                <div class="form-group">
                    <label style="width: 130px;">Nombre IPS que atendió la victima</label>
                    <input type="text" value="{{ $data['ipsName'] }}" style="width: 240px; height: 14px;">
                    <label style="width: 39px; margin-left: 10px;">Nit</label>
                    <input type="text" value="{{ $data['ipsNit'] }}" style="width: 165px; height: 14px;">
                </div>
                <div class="form-group">
                    <label style="width: 50px;">Dirección</label>
                    <input type="text" value="{{ $data['ipsAddress'] }}" style="width: 260px; height: 14px;">
                    <label style="width: 60px; margin-left: 30px;">Código IPS</label>
                    <input type="text" value="{{ $data['ipsReceptionHabilitation_code'] }}" style="width: 202px; height: 14px;">
                </div>
                <div class="form-group">
                    <label style="width: 50px;">Departamento</label>
                    <input type="text" value="{{ $data['transferPickupDepartment_name'] }}" style="width: 260px; height: 14px;">
                    <label style="width: 30px; margin-left: 10px;">Código</label>
                    <input type="text" value="{{ $data['transferPickupDepartment_code'] }}" style="width: 50px; height: 14px;">
                    <label style="width: 50px; margin-left: 10px;">Teléfono</label>
                    <input type="text" value="{{ $data['ipsPhone'] }}" style="width: 115px; height: 14px;">
                </div>
                <div class="form-group">
                    <label style="width: 50px;">Municipio</label>
                    <input type="text" value="{{ $data['transferPickupMunicipality_name'] }}" style="width: 160px; height: 14px;">
                    <label style="width: 30px; margin-left: 80px;">Cod.</label>
                    <input type="text" value="{{ $data['transferPickupMunicipality_code'] }}" style="width: 70px; height: 14px;">
                </div>
            </div>

            <div class="section">
                <h2>VI. DATOS OBLIGATORIOS SI EL EVENTO ES UN ACCIDENTE DE TRANSITO</h2>
                <div class="form-group">
                    <label style="width: 100px;">Condición de víctima:</label>
                    <label style="width: 50px; margin-left: 20px;">Conductor</label>
                    <input type="text" style="width: 14px; height: 14px;" value="{{ $data['victimCondition']?->value === "VICTIM_CONDITION_001" ? 'X' : '' }}">
                    <label style="width: 70px; margin-left: 20px;">Peatón</label>
                    <input type="text" style="width: 14px; height: 14px; margin-left: 10px;" value="{{ $data['victimCondition']?->value === "VICTIM_CONDITION_002" ? 'X' : '' }}">
                    <label style="width: 70px; margin-left: 20px;">Ocupante</label>
                    <input type="text" style="width: 14px; height: 14px; margin-left: 10px;" value="{{ $data['victimCondition']?->value === "VICTIM_CONDITION_003" ? 'X' : '' }}">
                    <label style="width: 70px; margin-left: 20px;">Ciclista</label>
                    <input type="text" style="width: 14px; height: 14px; margin-left: 10px;" value="{{ $data['victimCondition']?->value === "VICTIM_CONDITION_004" ? 'X' : '' }}">
                </div>
                <div class="form-group">
                    <label style="width: 100px;">Estado de Aseguramiento:</label>
                    <label style="width: 50px; margin-left: 20px;">Asegurado</label>
                    <input type="text" value="{{ $data['insurance_status'] === "1" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                    <label style="width: 70px; margin-left: 20px;">No asegurado</label>
                    <input type="text" value="{{ $data['insurance_status'] === "2" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                    <label style="width: 70px; margin-left: 20px;">Vehiculo fantasma</label>
                    <input type="text" value="{{ $data['insurance_status'] === "3" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                    <label style="width: 70px; margin-left: 20px;">Póliza falsa</label>
                    <input type="text" value="{{ $data['insurance_status'] === "4" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                </div>
                <div class="form-group">
                    <label style="width: 100px;"></label>
                    <label style="width: 50px; margin-left: 20px;">Vehículo en fuga</label>
                    <input type="text" value="{{ $data['insurance_status'] === "5" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                    <label style="width: 70px; margin-left: 20px;">Asegurado D.2497</label>
                    <input type="text" value="{{ $data['insurance_status'] === "6" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                </div>
                <div class="form-group">
                    <label style="width: 120px;">Placa del vehículo involucrado</label>
                    <input type="text" style="width: 130px; height: 14px;" value="{{ $data['involvedVehiclePlate'] }}">
                    <label style="width: 100px; margin-left: 10px;">Código de la aseguradora</label>
                    <input type="text" style="width: 50px; height: 14px;" value={{ $data['insurerCode'] }}>
                    <label style="width: 80px; margin-left: 10px;">Número de la póliza</label>
                    <input type="text" style="width: 70px; height: 14px;" value="{{ $data['policy_number'] }}">
                </div>
                <div class="form-group">
                    <label style="width: 100px;">Tipo de Veículo:</label>
                    <label style="width: 50px; margin-left: 20px;">Automóvil</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_001" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                    <label style="width: 70px; margin-left: 20px;">Bus</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_002" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                    <label style="width: 70px; margin-left: 20px;">Buseta</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_003" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                    <label style="width: 70px; margin-left: 20px;">Camión</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_004" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                </div>
                <div class="form-group">
                    <label style="width: 100px;"></label>
                    <label style="width: 50px; margin-left: 20px;">Camioneta</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_005" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                    <label style="width: 70px; margin-left: 20px;">Campero</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_006" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                    <label style="width: 70px; margin-left: 20px;">Microbus</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_007" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                    <label style="width: 70px; margin-left: 20px;">Tractocamión</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_008" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                </div>
                <div class="form-group">
                    <label style="width: 100px;"></label>
                    <label style="width: 50px; margin-left: 20px;">Motocicleta</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_009" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                    <label style="width: 70px; margin-left: 20px;">Motocarro</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_010" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                    <label style="width: 70px; margin-left: 20px;">Moto triciclo</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_011" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                    <label style="width: 70px; margin-left: 20px;">Cuatrimoto</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_012" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                </div>
                <div class="form-group">
                    <label style="width: 100px;"></label>
                    <label style="width: 50px; margin-left: 20px;">Moto Extranjera</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_013" ? 'X' : '' }}" style="width: 14px; height: 14px;">
                    <label style="width: 70px; margin-left: 20px;">Vehículo Extranjero</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_014" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                    <label style="width: 70px; margin-left: 20px;">Volqueta</label>
                    <input type="text" value="{{ $data['involvedVehicleType']?->value === "VEHICLE_TYPE_015" ? 'X' : '' }}" style="width: 14px; height: 14px; margin-left: 10px;">
                </div>
                <div class="form-group">
                    <label style="width: 100px;">Fecha de inicio póliza</label>
                    @foreach ($data['policy_start_date'] as $digit)
                    <input type="text" value="{{ $digit }}" maxlength="1"
                        style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endforeach
                    <label style="width: 80px; margin-left: 10px;">Fecha final póliza</label>
                    @foreach ($data['policy_end_date'] as $digit)
                    <input type="text" value="{{ $digit }}" maxlength="1"
                        style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endforeach
                </div>
                <div class="form-group">
                    <label style="width: 120px;">Número de radicado SIRAS</label>
                    <input type="text" style="width: 130px; height: 14px;" value="{{ $data['sirasRecordNumber'] }}">
                </div>
            </div>

            <div class="section">
                <h2>VII. AMPARO RECLAMADO</h2>
                <div class="form-group">
                    <label style="width: 100px;">Valor Facturado:</label>
                    <input type="text" value="{{ $data['billedValue'] }}" style="width: 100px; height: 14px;">
                    <label style="width: 100px; margin-left: 20px;">Valor Reclamado:</label>
                    <input type="text" value="{{ $data['claimedValue'] }}" style="width: 100px; height: 14px;">
                </div>
            </div>

            <div class="section">
                <h2>VIII. MANIFESTACION DEL SERVICIO HABILITADO DEL PRESTADOR DE SERVICIOS DE SALUD</h2>
                <label style="width: 150px;">Manifestación de servicios habilitados</label>
                <label style="width: 10px; margin-left: 20px;">Si</label>
                <input type="text" value="{{ $data['serviceEnabledIndication']?->value === "YES_NO_001" ? 'X' : '' }}" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                <label style="width: 10px; margin-left: 20px;">No</label>
                <input type="text" value="{{ $data['serviceEnabledIndication']?->value === "YES_NO_002" ? 'X' : '' }}" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
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
                        <span style="display: inline-block; width: 190px; margin-left: 30px; text-align: center;">NOMBRE REPRESENTANTE LEGAL O PERSONA RESPONSABLE PARA TRAMITE DE ADMISIONES DE LA IPS</span>
                        <span style="display: inline-block; width: 200px; margin-left: 195px; text-align: center;">FIRMA DEL REPRESENTANTE LEGAL O PERSONA RESPONSABLE PARA TRAMITE DE ADMISIONES DE LA IPS</span>
                    </div>
                </div>
                <div style="margin-left: 0; margin-top: 5px; font-size: 8px;">
                    <p style="display: inline-block; border-bottom: 1px solid #000; height: 16px; width: 200px; padding-left: 10px; margin-left: 20px; vertical-align: top;"></p>
                    <p style="display: inline-block; border-bottom: 1px solid #000; height: 16px; width: 200px; padding-left: 10px; margin-left: 180px; vertical-align: top;"></p>
                    <div style="margin-top: 2px;">
                        <span style="display: inline-block; width: 190px; margin-left: 30px; text-align: center; vertical-align: top;">TIPO Y NUMERO DE DOCUMENTO</span>
                        <span style="display: inline-block; width: 200px; margin-left: 195px; text-align: center;">FIRMA DEL REPRESENTANTE LEGAL DE LA EMPRESA TRANSPORTADORA O DE LA PERSONA NATURAL QUE REALIZO EL TRANSPORTE</span>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>
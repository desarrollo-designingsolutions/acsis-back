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
                    <input type="text" value="{{ $i }}" maxlength="1"
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
                <input type="text"
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
                <input type="text" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Apellido</label>
                <input type="text" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 50px;">1er Nombre</label>
                <input type="text" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Nombre</label>
                <input type="text" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Tipo de Documento</label>
                @for ($i = 0; $i < 7; $i++)
                    <input type="text" value="{{ $i }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endfor
                    <label style="width: 100px; margin-left: 90px;">No. Documento</label>
                    <input type="text" style="width: 150px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Tipo de Vehículo o de Servicio de ambulancia:</label>
                <label style="width: 50px; margin-left: 20px;">Ambulancia Básica</label>
                <input type="text" style="width: 14px; height: 14px;">
                <label style="width: 70px; margin-left: 20px;">Ambulancia Medicada</label>
                <input type="text" style="width: 14px; height: 14px; margin-left: 10px;">
                <label style="width: 70px; margin-left: 20px;">De emergencia</label>
                <input type="text" style="width: 14px; height: 14px; margin-left: 10px;">
                <label style="width: 70px; margin-left: 20px;">Particular</label>
                <input type="text" style="width: 14px; height: 14px; margin-left: 10px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;"></label>
                <label style="width: 50px; margin-left: 20px;">Público</label>
                <input type="text" style="width: 14px; height: 14px;">
                <label style="width: 70px; margin-left: 20px;">Oficial</label>
                <input type="text" style="width: 14px; height: 14px; margin-left: 10px;">
                <label style="width: 70px; margin-left: 20px;">Vehículo de servicio diplomatico o consular</label>
                <input type="text" style="width: 14px; height: 14px; margin-left: 10px;">
                <label style="width: 70px; margin-left: 20px;">Vehículo de transporte masivo</label>
                <input type="text" style="width: 14px; height: 14px; margin-left: 10px;">
            </div>
            <div class="form-group">
                <label style="width: 120px;"></label>
                <label style="width: 50px;">Vehículo escolar</label>
                <input type="text" style="width: 14px; height: 14px;">
                <label style="width: 78px; margin-left: 20px;">Otro</label>
                <input type="text" style="width: 14px; height: 14px;">
            </div>

            <div class="form-group">
                <span style="display: inline-block; width: 50px; vertical-align: middle;">Cuál?</span>
                <input type="text" style="width: 375px; height: 14px; border: 1px solid #000; vertical-align: middle; box-sizing: border-box;">
                <label style="width: 50px; margin-left: 5px;">Placa No. </label>
                <input type="text" style="width: 100px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 98px;">Departamento</label>
                <input type="text" style="width: 130px; height: 14px;">
                <label style="width: 30px; margin-left: 70px;">Cod.</label>
                <input type="text" style="width: 50px; height: 14px;">
                <label style="width: 50px; margin-left: 30px;">Teléfono</label>
                <input type="text" style="width: 125px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 98px;">Municipio</label>
                <input type="text" style="width: 160px; height: 14px;">
                <label style="width: 30px; margin-left: 40px;">Cod.</label>
                <input type="text" style="width: 70px; height: 14px;">
            </div>
        </div>

        <div class="section">
            <h2>II. DATOS DE LA VICTIMA TRANSLADADA</h2>
            <div class="form-group">
                <label style="width: 100px;">Tipo de Documento</label>
                @for ($i = 0; $i < 7; $i++)
                    <input type="text" value="{{ $i }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endfor
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
                        <td style="width: 50px; height: 10px; border: 1px solid #000;"></td>
                        <td style="width: 110px; height: 10px; border: 1px solid #000;"></td>
                        <td style="width: 110px; height: 10px; border: 1px solid #000;"></td>
                        <td style="width: 110px; height: 10px; border: 1px solid #000;"></td>
                        <td style="width: 110px; height: 10px; border: 1px solid #000;"></td>
                        <td style="width: 110px; height: 10px; border: 1px solid #000;"></td>
                    </tr>
                </table>
            </div>
            <div class="form-group">
                <span style="display: inline-block; width: 97px; vertical-align: middle;">Fecha de Nacimiento</span>
                @for ($i = 0; $i < 8; $i++)
                    <input type="text" value="{{ $i }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endfor
                    <label style="width: 20px; margin-left: 80px;">Sexo</label>
                    @for ($i = 0; $i < 2; $i++)
                        <input type="text" value="{{ $i }}" maxlength="1"
                        style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box; margin-left: 40px;">
                        @endfor
            </div>
        </div>

        <div class="section">
            <h2>III. IDENTIFICACION DEL TIPO DE EVENTO</h2>
            <div class="form-group">
                <label style="width: 100%;">Tipo de evento:</label>
                <label style="width: 100px; margin-left: 40px;">1.Accidente de tránsito:</label>
                <input type="text" style="width: 14px; height: 14px;">
                <label style="width: 100px; margin-left: 20px;">2.Evento catastrófico de origen Natural:</label>
                <input type="text" style="width: 14px; height: 14px;">
                <label style="width: 100px; margin-left: 20px;">3.Evento terrorista:</label>
                <input type="text" style="width: 14px; height: 14px;">
            </div>
        </div>

        <div class="section">
            <h2>IV. LUGAR EN EL QUE SE RECOGE LA VICTIMA</h2>
            <div class="form-group">
                <label style="width: 97px;">Dirección Residencia</label>
                <input type="text" style="width: 520px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 98px;">Departamento</label>
                <input type="text" style="width: 130px; height: 14px;">
                <label style="width: 30px; margin-left: 70px;">Cod.</label>
                <input type="text" style="width: 50px; height: 14px;">
                <label style="width: 30px; margin-left: 30px;">Zona</label>
                @for ($i = 0; $i < 2; $i++)
                    <input type="text" value="{{ $i }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box; margin-left: 15px;">
                    @endfor
            </div>
            <div class="form-group">
                <label style="width: 98px;">Municipio</label>
                <input type="text" style="width: 160px; height: 14px;">
                <label style="width: 30px; margin-left: 40px;">Cod.</label>
                <input type="text" style="width: 70px; height: 14px;">
            </div>
        </div>

        <div class="section">
            <h2>V. CERTIFICADO DE TRASLADO DE VICTIMAS</h2>
            <div class="form-group">
                <label style="width: 50px;">1er Apellido</label>
                <input type="text" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Apellido</label>
                <input type="text" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 50px;">1er Nombre</label>
                <input type="text" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Nombre</label>
                <input type="text" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Tipo de Documento</label>
                @for ($i = 0; $i < 7; $i++)
                    <input type="text" value="{{ $i }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endfor
                    <label style="width: 100px; margin-left: 90px;">No. Documento</label>
                    <input type="text" style="width: 150px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 97px;">Dirección Residencia</label>
                <input type="text" style="width: 520px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 98px;">Departamento</label>
                <input type="text" style="width: 130px; height: 14px;">
                <label style="width: 30px; margin-left: 70px;">Cod.</label>
                <input type="text" style="width: 50px; height: 14px;">
                <label style="width: 50px; margin-left: 30px;">Teléfono</label>
                <input type="text" style="width: 125px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 98px;">Municipio</label>
                <input type="text" style="width: 160px; height: 14px;">
                <label style="width: 30px; margin-left: 40px;">Cod.</label>
                <input type="text" style="width: 70px; height: 14px;">
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
                <input type="text" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Apellido</label>
                <input type="text" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 50px;">1er Nombre</label>
                <input type="text" style="width: 246px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Nombre</label>
                <input type="text" style="width: 246px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Tipo de Documento</label>
                @for ($i = 0; $i < 7; $i++)
                    <input type="text" value="{{ $i }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endfor
                    <label style="width: 100px; margin-left: 90px;">No. Documento</label>
                    <input type="text" style="width: 150px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 97px;">Dirección Residencia</label>
                <input type="text" style="width: 520px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 98px;">Departamento</label>
                <input type="text" style="width: 130px; height: 14px;">
                <label style="width: 30px; margin-left: 70px;">Cod.</label>
                <input type="text" style="width: 50px; height: 14px;">
                <label style="width: 50px; margin-left: 30px;">Teléfono</label>
                <input type="text" style="width: 125px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 98px;">Municipio</label>
                <input type="text" style="width: 160px; height: 14px;">
                <label style="width: 30px; margin-left: 40px;">Cod.</label>
                <input type="text" style="width: 70px; height: 14px;">
            </div>
        </div>

        <div class="section">
            <h2>VII. DATOS DE REMISION</h2>
            <div class="form-group">
                <label style="width: 100px; margin-left: 15px;">Tipo Referencia</label>
                <label style="width: 50px;">Remisión</label>
                <input type="text" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                <label style="width: 80px; margin-left: 20px;">Orden de Servicio</label>
                <input type="text" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Fecha remisión</label>
                @for ($i = 0; $i < 8; $i++)
                    <input type="text" value="{{ $i }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endfor
                    <span style="display: inline-block; width: 50px; vertical-align: middle; margin-left: 20px;">a las</span>
                    @for ($i = 0; $i < 4; $i++)
                        <input type="text" value="{{ $i }}" maxlength="1"
                        style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                        @endfor
            </div>
            <div class="form-group">
                <label style="width: 100px;">Prestador que remite</label>
                <input type="text" style="width: 512px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Código de inscripción</label>
                <input type="text" style="width: 200px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Profesional que remite</label>
                <input type="text" style="width: 260px; height: 14px;">
                <label style="width: 40px; margin-left: 30px;"> Cargo</label>
                <input type="text" style="width: 165px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Fecha aceptación</label>
                @for ($i = 0; $i < 8; $i++)
                    <input type="text" value="{{ $i }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endfor
                    <span style="display: inline-block; width: 50px; vertical-align: middle; margin-left: 20px;">a las</span>
                    @for ($i = 0; $i < 4; $i++)
                        <input type="text" value="{{ $i }}" maxlength="1"
                        style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                        @endfor
            </div>
            <div class="form-group">
                <label style="width: 100px;">Prestador que recibe</label>
                <input type="text" style="width: 512px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Código de inscripción</label>
                <input type="text" style="width: 200px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Profesional que recibe</label>
                <input type="text" style="width: 260px; height: 14px;">
                <label style="width: 40px; margin-left: 30px;"> Cargo</label>
                <input type="text" style="width: 165px; height: 14px;">
            </div>
        </div>

        <div class="section">
            <h2> VIII. AMPARO DE TRANSPORTE Y MOVILIZACION DE LA VICTIMA</h2>
            <div class="form-group">
                <label style="width: 100%;">Diligenciar únicamente para el transporte desde el sitio del evento hasta la primera IPS (Transporte Primario)</label>
                <label style="width: 100px;">Datos de Vehículo </label>
                <label style="width: 50px;">Placa No. </label>
                <input type="text" style="width: 100px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 100px;">Transporto la víctima desde</label>
                <input type="text" style="width: 260px; height: 14px;">
                <label style="width: 40px; margin-left: 30px;">Hasta</label>
                <input type="text" style="width: 165px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 100px;">Tipo de Transporte</label>
                <label style="width: 80px;">Ambulancia Básica</label>
                <input type="text" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                <label style="width: 80px; margin-left: 20px;">Ambulancia Medicada</label>
                <input type="text" maxlength="1" style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                <label style="width: 110px; margin-left: 40px;">Lugar donde recoge la Victima</label>
                <label style="width: 30px; margin-left: 30px;">Zona</label>
                @for ($i = 0; $i < 2; $i++)
                    <input type="text" value="{{ $i }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box; margin-left: 15px;">
                    @endfor
            </div>
        </div>

        <div class="section">
            <h2>IX. CERTIFICADO DE LA ATENCIÓN MEDICA DELA VICTIMA COMO PRUEBA DEL ACCIDENTE O EVENTO</h2>
            <div class="form-group">
                <label style="width: 100px;">Fecha de ingreso</label>
                @for ($i = 0; $i < 8; $i++)
                    <input type="text" value="{{ $i }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endfor
                    <span style="display: inline-block; width: 50px; vertical-align: middle; margin-left: 20px;">a las</span>
                    @for ($i = 0; $i < 4; $i++)
                        <input type="text" value="{{ $i }}" maxlength="1"
                        style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                        @endfor
            </div>

            <div class="form-group">
                <label style="width: 150px;">Código Diagnóstico principal de Ingreso</label>
                <input type="text" style="width: 116px; height: 14px;">
                <label style="width: 150px; margin-left: 60px;"> Código Diagnóstico principal de Egreso</label>
                <input type="text" style="width: 116px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 150px;">Otro código Diagnóstico principal de Ingreso</label>
                <input type="text" style="width: 116px; height: 14px;">
                <label style="width: 150px; margin-left: 60px;">Otro código Diagnóstico principal de Egreso</label>
                <input type="text" style="width: 116px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 150px;">Otro código Diagnóstico principal de Ingreso</label>
                <input type="text" style="width: 116px; height: 14px;">
                <label style="width: 150px; margin-left: 60px;">Otro código Diagnóstico principal de Egreso</label>
                <input type="text" style="width: 116px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 50px;">1er Apellido</label>
                <input type="text" style="width: 241px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Apellido</label>
                <input type="text" style="width: 241px; height: 14px;">
            </div>
            <div class="form-group">
                <label style="width: 50px;">1er Nombre</label>
                <input type="text" style="width: 241px; height: 14px;">
                <label style="width: 50px; margin-left: 10px;">2do Nombre</label>
                <input type="text" style="width: 241px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 130px;">Tipo de Documento</label>
                @for ($i = 0; $i < 3; $i++)
                    <input type="text" value="{{ $i }}" maxlength="1"
                    style="width: 14px; height: 14px; border: 1px solid #000; text-align: center; vertical-align: middle; padding: 0; margin: 0; box-sizing: border-box;">
                    @endfor
                    <label style="width: 100px; margin-left: 170px;">No. Documento</label>
                    <input type="text" style="width: 150px; height: 14px;">
            </div>

            <div class="form-group">
                <label style="width: 355px;"></label>
                <label style="width: 100px;">No. Documento</label>
                <input type="text" style="width: 150px; height: 14px;">
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
                    <td style="width: 150px; border: 1px solid #000;"></td>
                    <td style="width: 150px; border: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td style="width: 250px; border: 1px solid #000;">Gastos de transporte y movilización de la víctima</td>
                    <td style="width: 150px; border: 1px solid #000;"></td>
                    <td style="width: 150px; border: 1px solid #000;"></td>
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
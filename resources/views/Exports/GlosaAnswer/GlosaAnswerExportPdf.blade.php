<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Respuesta de Glosas y/o Devoluciones</title>
<style>
    /* Estilo base similar a tus PDF previos */
    @page { margin: 20px 18px 28px 18px; size: letter landscape;  }
    body { font-family: Arial, sans-serif; font-size: 9px; color:#000; }
    .wrap { border: 2px solid #000; padding: 10px; }
    .row { display:flex; align-items:center; }
    .space { height:6px; }

    /* Encabezado */
    .header { text-align:center; position: relative; padding: 6px 0 2px 0; }
    .header .logo-left  { position:absolute; left:0; top:0; }
    .header .logo-right { position:absolute; right:0; top:0; }
    .header img { max-height: 48px; }
    .header h1 { font-size: 12px; margin:0 0 2px 0; font-weight:bold; }
    .header p  { margin:0; line-height: 13px; }

    /* Bloque datos cortos */
    .meta { margin-top: 10px; border:1px solid #000; }
    .meta td { padding: 4px 6px; border-right:1px solid #000; }
    .meta td:last-child { border-right:0; }
    .meta .label { font-weight: bold; width: 180px; white-space:nowrap; }

    /* Tabla principal */
    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #000; padding: 6px 4px; vertical-align: top; }
    thead th { text-align: center; background: #eee; font-weight:bold; }
    .text-center { text-align:center; }
    .text-right { text-align:right; }
    .text-justify { text-align: justify; }
    .no-wrap { white-space: nowrap; }

    /* Totales */
    tfoot td { font-weight:bold; background:#f6f6f6; }

    /* Pie */
    .contact { margin-top:6px; }
    .legal { margin-top:6px; border:1px solid #000; padding:6px; font-size:8px; text-transform: uppercase; font-weight: bold; }
    .sign { margin-top: 18px; }
</style>
</head>
<body>
<div class="wrap">

    {{-- ENCABEZADO --}}
    <div class="header">
        <div class="logo-left">
            {{-- Ajusta rutas según tu storage/public --}}`
            <img src="{{ public_path($data['left_logo']) }}" alt="Logo Izquierdo">
        </div>
        <div class="logo-right">
            <img src="{{ public_path($data['right_logo']) }}" alt="Logo Derecho">
        </div>

        <h1>{{$data['service_vendor_name']}} </h1>
        <p><strong>NIT. 000.000195.217-1</strong></p>
        <p><strong>Calle 24 No 17-30 Yopal - Casanare</strong></p>
        <p><strong>ÁREA DE AUDITORÍA DE CUENTAS</strong></p>
        <p><strong>FORMATO RESPUESTA DE GLOSAS Y/O DEVOLUCIONES</strong></p>
    </div>

    <div class="space"></div>

    {{-- BLOQUE DE DATOS CORTOS --}}
    <p><strong>ENTIDAD RESPONSABLE DE PAGO : {{ $data['entity_name'] }}</strong></p>
    <p><strong>NIT : {{ $data['entity_nit'] }}</strong></p>
    <p><strong>FECHA DE RESPUESTA: {{ $data['date_answer'] }}</strong></p>

    <div class="space"></div>

    {{-- TABLA PRINCIPAL --}}
    <table>
        <thead>
            <tr>
                <th style="width:7%">No Factura</th>
                <th style="width:16%">Nombre y<br>Apellido Usuario</th>
                <th style="width:10%">No Documento</th>
                <th style="width:9%">Vr. Factura</th>
                <th style="width:9%">Vr. Glosa</th>
                <th style="width:18%">Motivo de Objeción</th>
                <th style="width:9%">Vr Aceptado<br>por CBVY</th>
                <th style="width:9%">Vr Soportado<br>a Pagar ERP</th>
                <th style="width:13%">Respuesta a la Objeción</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sumFactura = 0; $sumGlosa = 0; $sumAceptado = 0; $sumERP = 0;
            @endphp
                @php
                    $sumFactura += (float)($data['invoice_value'] ?? 0);
                    $sumGlosa   += (float)($data['glosa_value'] ?? 0);
                    $sumAceptado+= (float)($data['value_accepted'] ?? 0);
                    $sumERP     += (float)($data['value_approved'] ?? 0);
                @endphp
                <tr>
                    <td class="text-center">{{ $data['invoice_number'] ?? '' }}</td>
                    <td>{{ $data['patient_name'] ?? '' }}</td>
                    <td class="text-center">{{ $data['patient_document'] ?? '' }}</td>
                    <td class="text-right">{{ number_format($data['invoice_value'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($data['glosa_value'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-justify">{{ $data['reason'] ?? '' }}</td>
                    <td class="text-right">{{ number_format($data['value_accepted'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($data['value_approved'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-justify">{{ $data['observation'] ?? '' }}</td>
                </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right">TOTAL</td>
                <td class="text-right">{{ number_format($sumFactura, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($sumGlosa, 0, ',', '.') }}</td>
                <td></td>
                <td class="text-right">{{ number_format($sumAceptado, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($sumERP, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    {{-- CONTACTO --}}
    <p class="contact">Cualquier inquietud comunicarse al teléfono <strong>(8) 6342861 en la ciudad de Yopal - Casanare</strong>.</p>

    {{-- BLOQUE LEGAL --}}
    <div class="legal">
        FAVOR TRAMITAR LA CUENTA LO MÁS RÁPIDO POSIBLE DE LO CONTRARIO NOS VEREMOS EN LA OBLIGACIÓN DE APLICAR
        EL ARTÍCULO 7 DE LA RESOLUCIÓN 1281 DE 2002 QUE REZA: EN EL EVENTO EN QUE LAS GLOSAS FORMULADAS RESULTEN
        INFUNDADAS EL PRESTADOR TENDRÁ DERECHO AL RECONOCIMIENTO DE INTERESES MORATORIOS DESDE LA FECHA DE
        PRESENTACIÓN DE LA FACTURA, RECLAMACIÓN O CUENTA DE COBRO EN LOS TÉRMINOS DEL DECRETO 056 DE 2015 ARTÍCULO 41 NUMERAL 1.
    </div>

    {{-- FIRMAS / CRÉDITOS --}}
    <div class="sign">
        <p>Auditor Externo - <strong>ACSIS SAS</strong></p>
        <p>{{$data['service_vendor_name']}}</p>
    </div>
</div>
</body>
</html>

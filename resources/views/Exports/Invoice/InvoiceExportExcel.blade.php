<div>
    <table>
        <thead>
            <tr>
                <th rowspan="2"
                    style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Entidad
                </th>
                <th rowspan="2"
                    style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Factura
                    No.</th>
                <th rowspan="2"
                    style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Tipo
                    Factura</th>
                <th rowspan="2"
                    style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Valor
                    Pagado</th>
                <th rowspan="2"
                    style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Valor
                    Glosa</th>
                <th rowspan="2"
                    style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Fecha
                    Radicación</th>
                <th rowspan="2"
                    style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">
                    Paciente</th>
                <th rowspan="2"
                    style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Estado
                </th>
            </tr>
            <tr></tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr class="">
                    <td style="text-align: center; padding: 8px;">{{ $row['entity_name'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['invoice_number'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['type_name'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['value_paid'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['value_glosa'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['radication_date'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['patient_name'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['status'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

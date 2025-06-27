<div>
    <table>
        <thead>
            <tr> 
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Tipo de Documento</th>
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Documento</th>
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Tipo de Usuario</th>
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Fecha de Nacimiento</th>
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Sexo</th>
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Pais de Residencia</th>
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Municipio de Residencia</th>
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Zona Territorial de Residencia</th>
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Incapacidad</th>
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Pais de Origen</th>
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Primer Nombre</th>
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Segundo Nombre</th>
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Primer Apellido</th>
                <th rowspan="2" style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Segundo Apellido</th>
            </tr>
            <tr></tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr class="">
                    <td style="text-align: center; padding: 8px;">{{ $row['tipo_id_pisi_nombre'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['document'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['rips_tipo_usuario_version2_nombre'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['birth_date'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['sexo_nombre'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['pais_residency_nombre'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['municipio_residency_nombre'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['zona_version2_nombre'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['incapacity'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['pais_origin_nombre'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['first_name'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['second_name'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['first_surname'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['second_surname'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

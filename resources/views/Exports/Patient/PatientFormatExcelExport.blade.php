<table>
    <thead>
        <tr>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Tipo de Documento</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Documento</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Tipo de Usuario</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Fecha de Nacimiento</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Sexo</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Pais de Residencia</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Municipio de Residencia</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Zona Territorial de Residencia</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Incapacidad</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Pais de Origen</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Primer Nombre</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Segundo Nombre</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Primer Apellido</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Segundo Apellido</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                <td> {{ $row['tipo_id_pisi_id'] }}</td>
                <td> {{ $row['document'] }}</td>
                <td> {{ $row['rips_tipo_usuario_version2_id'] }}</td>
                <td> {{ $row['birth_date'] }}</td>
                <td> {{ $row['sexo_id'] }}</td>
                <td> {{ $row['pais_residency_id'] }}</td>
                <td> {{ $row['municipio_residency_id'] }}</td>
                <td> {{ $row['zona_version2_id'] }}</td>
                <td> {{ $row['incapacity'] }}</td>
                <td> {{ $row['pais_origin_id'] }}</td>
                <td> {{ $row['first_name'] }}</td>
                <td> {{ $row['second_name'] }}</td>
                <td> {{ $row['first_surname'] }}</td>
                <td> {{ $row['second_surname'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

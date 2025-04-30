<div>
    <table>
        <thead>
            <tr>
                <th rowspan="2"
                style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Nombre</th>
                <th rowspan="2"
                style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Email</th>
                <th rowspan="2"
                style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Rol</th>
                <th rowspan="2"
                style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Estado</th>
            </tr>
            <tr></tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr class="">
                    <td style="text-align: center; padding: 8px;">{{ $row['full_name'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['email'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['role_description'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['is_active'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

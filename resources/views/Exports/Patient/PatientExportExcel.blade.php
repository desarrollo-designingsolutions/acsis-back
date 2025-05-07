<div>
    <table>
        <thead>
            <tr>
                <th rowspan="2"
                style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Documento</th>
                <th rowspan="2"
                style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">Nombre</th>
            </tr>
            <tr></tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr class="">
                    <td style="text-align: center; padding: 8px;">{{ $row['document'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $row['full_name'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

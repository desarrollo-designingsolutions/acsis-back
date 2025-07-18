<table>
    <thead>
        <tr>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">municipio_residencia_id</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">codigo</td>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">nombre</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td> {{ $item['id'] }}</td>
                <td> {{ $item['codigo'] }}</td>
                <td> {{ $item['nombre'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

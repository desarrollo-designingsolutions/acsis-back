<table>
    <thead>
        <tr>
            <td style="background-color: #d1cdcc; text-align: center; padding: 8px; border: 1px solid black">nombre</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td> {{ $item['name'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

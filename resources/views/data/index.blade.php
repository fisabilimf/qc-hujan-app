<!DOCTYPE html>
<html>
<head>
    <title>Data Split Result</title>
</head>
<body>
    <h2>Data Table - Part p ({{ $splitPercentage }}%)</h2>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Data</th>
                <th>Peringkat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($p as $row)
                <tr>
                    <td>{{ $row['No'] }}</td>
                    <td>{{ $row['Data'] }}</td>
                    <td>{{ $row['Peringkat'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Data Table - Part q ({{ 100 - $splitPercentage }}%)</h2>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Data</th>
                <th>Peringkat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($q as $row)
                <tr>
                    <td>{{ $row['No'] }}</td>
                    <td>{{ $row['Data'] }}</td>
                    <td>{{ $row['Peringkat'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Data Split Example</title>
</head>
<body>
    <h2>Set Split Percentage</h2>
    <form method="GET" action="{{ route('data.index') }}">
        <label for="split_percentage">Enter Split Percentage:</label>
        <input type="number" name="split_percentage" id="split_percentage" value="{{ $splitPercentage }}" min="1" max="99" required>
        <button type="submit">Apply</button>
    </form>

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
                    <td>{{ $row->No }}</td>
                    <td>{{ $row->Data }}</td>
                    <td>{{ $row->Peringkat }}</td>
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
                    <td>{{ $row->No }}</td>
                    <td>{{ $row->Data }}</td>
                    <td>{{ $row->Peringkat }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

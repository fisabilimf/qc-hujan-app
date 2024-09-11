<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Data Input and Split Example</title>
</head>
<body>
    <h2>Enter Data and Split Percentage</h2>
    <form method="POST" action="{{ route('data.process') }}">
        @csrf <!-- Laravel security token -->
        
        <label for="data">Enter Data (one value per line):</label><br>
        <textarea name="data" id="data" rows="10" cols="40" placeholder="e.g., 128&#13;&#10;75&#13;&#10;110&#13;&#10;..."></textarea><br>

        <label for="split_percentage">Enter Split Percentage:</label>
        <input type="number" name="split_percentage" id="split_percentage" value="50" min="1" max="99" required><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>

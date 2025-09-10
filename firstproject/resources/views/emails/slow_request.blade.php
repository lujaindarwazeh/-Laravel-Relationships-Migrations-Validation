<!DOCTYPE html>
<html>
<head>
    <title>Slow Request Alert</title>
</head>
<body>

    <h2>Alert: Slow Request Detected</h2>

    <p>A request to the application took longer than the defined threshold.</p>

    <h3>Request Details:</h3>
    <ul>
       
        <li><strong>URL:</strong> {{ $url }}</li>
        <li><strong>Duration:</strong> {{ $duration }} ms</li>
       
    </ul>

  

</body>
</html>
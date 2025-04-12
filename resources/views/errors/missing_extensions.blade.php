<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missing PHP Extensions</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        .error { color: red; font-size: 20px; font-weight: bold; }
        .list { margin-top: 20px; font-size: 18px; }
    </style>
</head>
<body>
    <h1 class="error">Required PHP Extensions Are Missing</h1>
    <p>Please enable the following PHP extensions in your server configuration:</p>
    <ul class="list">
        @foreach ($missingExtensions as $extension)
        <li>"{{ $extension }}" Extension is Required</li>
        @endforeach
    </ul>
</body>
</html>

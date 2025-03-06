<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Barcodes</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            width: 100%;
            margin: auto;
        }
        .token {
            border: 1px dashed black;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .barcode-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between; /* ✅ Side by Side */
            width: 100%;
            max-width: 800px; /* ✅ Set max width for A4 */
            margin: auto;
        }
        .token img {
            width: 100%;
            max-width: 180px; /* ✅ Barcode size */
            height: auto;
        }
        @media print {
            button { display: none; } /* ✅ Hide Button on Print */
            body { margin: 0; padding: 0; }
            .barcode-container { page-break-inside: avoid; } /* ✅ Prevent break */
        }
    </style>
</head>
<body>

    <h3>Medicine Barcodes </h3>
    <p>Date: {{ date('m/d/Y') }}</p>

    <div class="barcode-container">
        @foreach ($medicines as $medicine)
            <div class="token">
                <strong>{{ $medicine->name }}</strong> <br>
                <small>{{ $medicine->barcode }}</small>
                <div class="barcode">
                    <img src="{{ $medicine->barcode_image }}" alt="Barcode">
                </div>
            </div>
        @endforeach
    </div>

    <button onclick="window.print()">🖨️ Print</button>

</body>
</html>

@php
    ini_set("pcre.backtrack_limit", "500000000");
    error_reporting(0);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ url('css/pdf.css') }}" rel="stylesheet" />
</head>
<body class="a4">
    <div class="p-1 h-100">
        <table class="items text-center table-border" style="font-size: 12px">
            <tr>
                <th rowspan="2">SL</th>
                <th rowspan="2">Product Name</th>
                <th rowspan="2">Pack Size</th>
                <th rowspan="2">Opening Stock</th>
                <th colspan="2">Received During the Month</th>
                <th rowspan="2">Total Stock Qty</th>
                <th colspan="2">Sales During the Month</th>
                <th rowspan="2">Return</th>
                <th colspan="2">Closing Stock</th>
            </tr>
            <tr>
                <th>Factory/Depo</th>
                <th>Transfer</th>
                <th>Sales Qty</th>
                <th>Sales Value</th>
                <th>Qty</th>
                <th>Value</th>
            </tr>

            @php
                $count = 1;
                $closingValueTotal = 0;
                $salesValueTotal = 0;
            @endphp
            @foreach ($medicines as $medicine)
                @php
                    $salesQty = 0;
                    $salesValue = 0;
                    $stockQty = 0;
                    $stockValue = 0;
                    $salesReturnQty = App\Models\ReturnMedicine::whereBetween('return_date', [$start_date, $end_date])->where('medicine_id', $medicine->id)->sum('quantity');
                    $stockReturnQty = App\Models\StockReturnList::whereBetween('return_date', [$start_date, $end_date])->where('medicine_id', $medicine->id)->sum('quantity');
                    $openingStock = App\Models\OpeningStock::where('opening_month', $report_date->copy()->format('F'))->where('opening_year', $report_date->copy()->format('Y'))->where('medicine_id', $medicine->id)->value('opening_stock');
                @endphp
                @foreach ($invoices as $invoice)    
                    @foreach ($invoice->salesMedicines as $salesMedicine)
                        @if ($salesMedicine->medicine_id == $medicine->id) 
                            @php
                                $salesQty += $salesMedicine->initial_quantity;
                                $salesValue += $salesMedicine->total;
                            @endphp
                        @endif
                    @endforeach
                @endforeach
                @foreach ($stock_invoices as $stock_invoice)    
                    @foreach ($stock_invoice->stockLists as $stockList)
                        @if ($stockList->medicine_id == $medicine->id) 
                            @php
                                $stockQty += $stockList->initial_quantity;
                                $stockValue += $stockList->total;
                            @endphp
                        @endif
                    @endforeach
                @endforeach
                @php
                    $closingStock = $openingStock + $stockQty + $salesReturnQty - $stockReturnQty - $salesQty;
                    $closingValue = $closingStock * $medicine->price;
                    $closingValueTotal += $closingValue;
                    $salesValueTotal += $salesValue;
                @endphp
                <tr>
                    <td>{{ $count++ }}</td>
                    <td>{{ $medicine->name }}</td>
                    <td>{{ $medicine->pack_size }}</td>
                    <td>{{ $openingStock }}</td>
                    <td>{{ $stockQty }}</td>
                    <td>{{ $stockReturnQty }}</td>
                    <td>{{ $openingStock + $stockQty - $stockReturnQty }}</td>
                    <td>{{ $salesQty }}</td>
                    <td>{{ $salesValue }}</td>
                    <td>{{ $salesReturnQty }}</td>
                    <td>{{ $openingStock + $stockQty + $salesReturnQty - $stockReturnQty - $salesQty }}</td>
                    <td>{{ 
                        $closingValue
                    }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="8" class="text-end">Total</td>
                <td>{{ $salesValueTotal }}</td>
                <td></td>
                <td></td>
                <td>{{ $closingValueTotal }}</td>
            </tr>
        </table>
    </div>
</body>
<script>
    window.print();
</script>
</html>

@php
    $content = ob_get_clean();

    $conf = [
        'mode'          => 'utf-8',
        'format'        => [224, 286],
        'tempDir'       => storage_path('temp'),
        'orientation'   => 'L',
        'margin_left'   => 6,
        'margin_right'  => 6,
    ];

    $mpdf = new \Mpdf\Mpdf($conf);
    $dateTime = now()->format("d/m/Y, h:i A");

    $header = "<div style='text-align:center;'>
                <!--<h1 class='fw-bolder border-bottom m-0 text-italic'>$pdf_title</h1>-->
                <h5 class='fw-bolder border-bottom m-0'>
                    Stock Statement From : " . $report_date->copy()->startOfMonth()->format('d M Y') . " to " . $report_date->copy()->format('d M Y') . "
                </h5>
           </div>";


    $mpdf->SetHTMLHeader($header);
    $mpdf->SetTopMargin(20);

    $footer = "
        <div style='margin-top: 1rem;padding-right:50px;width:100%;'>
            <div class='text-start' style='width:50%; float: left;'>Report run on: $dateTime</div>
            <div class='text-end' style='width:50%; float: right;'>Page {PAGENO} of {nbpg}</div>
        </div>
    ";

    $mpdf->SetHTMLFooter($footer);

    $stylesheet = file_get_contents('css/pdf.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($content, \Mpdf\HTMLParserMode::HTML_BODY);
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->SetWatermarkImage(public_path('img/logo.png')); // Path to watermark image
    $mpdf->showWatermarkImage = true;
    $mpdf->Output();
@endphp

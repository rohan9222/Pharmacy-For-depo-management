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
        <table>
            <tr>
                <td colspan="2" class="text-center">
                    <h4>Product Sales Report</h4>
                </td>
            </tr>
            <tr>
                <td>Territory : </td>
                <td>{{ $user_data_all->route }} </td>
            </tr>
            <tr>
                <td>Name Of : </td>
                <td>{{ $user_data_all->name }} ({{ $user_data_all->user_id }})</td>
            </tr>
        </table>
        <table class="items text-center table-border" style="font-size: 12px">
            <tr>
                <th>SL</th>
                <th>Product Name</th>
                <th>Product Terget(pcs)</th>
                <th>Product Sales(pcs)</th>
                <th>Total Sales Target (Value)</th>
                <th>Total Sales (Value)</th>
                <th>Achieve %</th>
            </tr>

            @php
                $count = 1;
                $target_medicines = json_decode($sales_target_total->product_target_data);
            @endphp
            @foreach ($target_medicines as $target_medicine)
                @php
                    $roleShort = rolesConvertShort($user_data_all->role);
                    $invoice_data = App\Models\Invoice::where($roleShort . '_id', $user_data_all->id)
                                ->whereBetween('invoice_date', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
                                ->pluck('id')
                                ->toArray();

                    $sales_medicines = App\Models\SalesMedicine::whereIn('invoice_id', $invoice_data)
                                ->where('medicine_id', $target_medicine->medicine_id)
                                ->get();

                    // $total_sales_target = $target_medicines->sum('total');
                @endphp
                <tr>
                    <td>{{ $count++ }}</td>
                    <td>{{ $target_medicine->medicine_name }}</td>
                    <td>{{ $target_medicine->quantity }}</td>
                    <td>{{ $sales_medicines->sum('quantity') }}</td>
                    <td>{{ $target_medicine->total }}</td>
                    <td>{{ round($sales_medicines->sum('quantity') * $target_medicine->price) }}</td>
                    <td>{{ round($sales_medicines->sum('quantity') / $target_medicine->quantity * 100,2) }}</td>
                </tr>
            @endforeach

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
                    Statement Period: " . $start_date->format('d M Y') . " to " . $end_date->format('d M Y') . "
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

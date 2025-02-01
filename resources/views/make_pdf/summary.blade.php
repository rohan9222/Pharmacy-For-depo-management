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
<body>
    <div class="p-1">
        <div>
            <div style="display: inline-block; width: 45%; float: left;">
                <p class="m-0 subtitle ">Sales Office :</p>
                <p class="m-0">Address : {{$site_data->site_address}}</p>
                <p class="m-0">Mobile : {{$site_data->site_phone}}</p>
            </div>
            <div class="title">Invoice</div>
        </div>
        {{-- {{ dd($invoice_data) }} --}}
        <table class="table border">
            <tr>
                <td >Cust ID: {{$invoice_data->customer->user_id}}</td>
                <td >MPO ID: {{$invoice_data->fieldOfficer->user_id}}</td>
                <td>Category: {{$invoice_data->customer->category}}</td>
            </tr>
            <tr>
                <td>Name: {{$invoice_data->customer->name}}</td>
                <td>Name: {{$invoice_data->fieldOfficer->name}}</td>
                <td>Invoice No: {{$invoice_data->invoice_no}}</td>
            </tr>
            <tr>
                <td>Address: {{$invoice_data->customer->address}}</td>
                <td>Mobile: {{$invoice_data->fieldOfficer->mobile}}</td>
                <td>Invoice Date: {{ date('d-M-Y', strtotime($invoice_data->created_at))}}</td>
            </tr>
            <tr>
                <td>Mobile: {{$invoice_data->customer->mobile}}</td>
                <td>Route: {{$invoice_data->customer->route}}</td>
                <td>Delivery Date: </td>
            </tr>
        </table>

        <table class="items text-center">
            <tr class="border">
                <th class="border-start">Product Name</th>
                <th class="border-start">Pack Size</th>
                <th class="border-start">Unit TP</th>
                <th class="border-start">Unit VAT</th>
                <th class="border-start">QTY</th>
                <th class="border-start">Total TP</th>
                <th class="border-start">Total Vat</th>
                <th class="border-start border-end">Total Price</th>
            </tr>
        @php
            $sumTotalPrice = 0;
            $sumVatAmount = 0;
            $sumTotal = 0;
        @endphp

        @foreach ($invoice_data->salesMedicines as $medicine_list)
            @php
                $totalPrice = $medicine_list->price * $medicine_list->initial_quantity;
                $vatAmount = round($totalPrice * $medicine_list->vat / 100, 2);
            @endphp
            <tr>
                <td class="text-start border-dotted border-start">{{$medicine_list->medicine->name}}</td>
                <td class="border-dotted border-start">{{$medicine_list->medicine->pack_size}}</td>
                <td class="border-dotted border-start">{{$medicine_list->price}}</td>
                <td class="border-dotted border-start">{{$medicine_list->vat}}</td>
                <td class="border-dotted border-start">{{$medicine_list->initial_quantity}}</td>
                <td class="border-dotted border-start">{{$totalPrice}}</td>
                <td class="border-dotted border-start">{{$vatAmount}}</td>
                <td class="border-dotted border-start border-end">{{$medicine_list->total}}</td>
            </tr>

            @php
                $sumTotalPrice += $totalPrice;
                $sumVatAmount += $vatAmount;
                $sumTotal += $medicine_list->total;
            @endphp
        @endforeach

            <!-- Total Row -->
            <tr>
                <td class="text-start" colspan="5">Note:</td>
                <td class="border-start">{{$sumTotalPrice}}</td>
                <td class="border-start">{{$sumVatAmount}}</td>
                <td class="border-start border-end">{{$sumTotal}}</td>
            </tr>

            <tr></tr>
            <tr>
                <td colspan="5"></td>
                <td class="border" colspan="2">Discount on TP ({{$invoice_data->discount+$invoice_data->spl_discount}}%):</td>
                <td class="border">{{$invoice_data->dis_amount+$invoice_data->spl_dis_amount}}</td>
            </tr>
            <tr>
                <td colspan="5" class="subtitle text-uppercase">IN WORD: taka. {{$grand_total_words}} only</td>
                <td class="border" colspan="2"><b>Net Payable Amount:</b></td>
                <td class="border"><b>{{$invoice_data->grand_total}}</b></td>
            </tr>
        </table>
    </div>
</body>
</html>


@php

    $content    =   ob_get_clean();

    $conf       =   [
        'mode'          =>  'utf-8',
        'format'        =>  [224, 286],
        'tempDir'       =>  storage_path('temp'),
        'orientation'   => 'portrait',
        'margin_left' => 6,
        'margin_right' => 6,
        // 'orientation'   => 'L'
    ];

    $mpdf = new \Mpdf\Mpdf($conf);
    $dateTime = date("d/m/Y,  h:i A", time());
    $html = "<div style='width:100%; text-align:center;'>
                <div style='font-size:18px;text-align:center'><h2 style='margin:0; background-color:#cff4fc'>$pdf_title</h2></div>
            </div>";

        $mpdf->SetHTMLHeader($html);
        $mpdf->SetTopMargin(20);

    $mpdf->SetHTMLFooter("
        <div class='footer text-center' style='width:100%;'>
            <div style='width:20%; float: left;'>
                <p>-------------------</p>
                <p>Powered By</p>
            </div>
            <div style='width:20%; float: left;'>
                <p>-------------------</p>
                <p>Authorized By</p>
            </div>
            <div style='width:20%; float: left;'>
                <p>-------------------</p>
                <p>Delivered By</p>
            </div>
            <div style='width:20%; float: left;'>
                <p>-------------------</p>
                <p>Collected By</p>
            </div>
            <div style='width:20%; float: left;'>
                <p>-------------------</p>
                <p>Customer's Signature</p>
            </div>
        </div>
        <div style='margin-top: 1rem;padding-right:50px;width:100%;'>
            <div class='text-start' style='width:50%; float: left;'>Report run on: $dateTime</div>
            <div class='text-end' style='width:50%; float: right;'>Page {PAGENO} of {nbpg}</div>
        </div>
    ");

    $stylesheet = file_get_contents('css/pdf.css');

    $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($content, \Mpdf\HTMLParserMode::HTML_BODY);
    $mpdf->SetDisplayMode('fullpage');
    //$mpdf->Output("{$reportName}.pdf");
    $mpdf->Output();
@endphp

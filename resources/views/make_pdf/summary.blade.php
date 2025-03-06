
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
    {{-- compress voucher --}}
    <div class="p-1 h-100">
        <div>
            <!--<div style="display: inline-block; width: 45%; float: left;">-->
            <!--    <p class="m-0 subtitle ">Sales Office :</p>-->
            <!--    <p class="m-0">Address : {{$site_data->site_address}}</p>-->
            <!--    <p class="m-0">Mobile : {{$site_data->site_phone}}</p>-->
            <!--</div>-->
            <div class="title" style="text-align: center;">Product summary</div>
        </div>
        @php
            $inv_user_data = $invoice_data->first();
        @endphp
        <table class="table border">
            <tr>
                <td >Summary ID: {{$inv_user_data->summary_id}}</td>
                <td >TSE ID: {{$inv_user_data->fieldOfficer->user_id}}</td>
                <td>Delivery Man ID: {{$inv_user_data->deliveredBy->user_id}}</td>
            </tr>
            <tr>
                <td>Summary Dt: {{ date('d-M-Y', strtotime($inv_user_data->delivery_date))}}</td>
                <td>TSE Name: {{$inv_user_data->fieldOfficer->name}}</td>
                <td>Delivery Man: {{$inv_user_data->deliveredBy->name}}</td>
            </tr>
            <tr>
                <td></td>
                <td>Mobile: {{$inv_user_data->fieldOfficer->mobile}}</td>
                <td>Mobile: {{$inv_user_data->deliveredBy->mobile}}</td>
            </tr>
        </table>
        <table class="table">
            <tr>
                <td>Total Invoice : {{$invoice_data->count()}}</td>
                <td>Invoices # : {{ $invoice_data->pluck('invoice_no')->implode(', ') }}</td>
                <td></td>
            </tr>
        </table>

        <table class="items text-center border">
            <tr class="border">
                <th class="border-start" rowspan="2">Product ID</th>
                <th class="border-start" rowspan="2">Product Name</th>
                <th class="border-start" rowspan="2">Pack Size</th>
                <th class="border-start" rowspan="2">Unit Price</th>
                <th class="border-start" rowspan="2">Unit VAT(%)</th>
                <th class="border-start text-center" colspan="3">Issue</th>
                <th class="border-start text-center" colspan="3">Return</th>
                <th class="border-start text-center" rowspan="2">Total Qty</th>
            </tr>
            <tr class="border">
                <th class="border-start text-center">Qty</th>
                <th class="border-start text-center">Bonus</th>
                <th class="border-start text-center">Total</th>
                <th class="border-start text-center">Qty</th>
                <th class="border-start text-center">Bonus</th>
                <th class="border-start text-center">Total</th>
            </tr>
            @php
                $groupedMedicines = [];

                foreach ($invoice_data as $inv) {
                    foreach ($inv->salesMedicines as $medicine_list) {
                        $medicineId = $medicine_list->medicine->id;

                        if (!isset($groupedMedicines[$medicineId])) {
                            $groupedMedicines[$medicineId] = [
                                'id' => $medicineId,
                                'name' => $medicine_list->medicine->name,
                                'pack_size' => $medicine_list->medicine->pack_size,
                                'price' => $medicine_list->price,
                                'vat' => $medicine_list->vat,
                                'initial_quantity' => 0,
                                'total' => 0,
                            ];
                        }
                        // Summing up quantities and total amounts
                        $groupedMedicines[$medicineId]['initial_quantity'] += $medicine_list->initial_quantity;
                        $groupedMedicines[$medicineId]['total'] += $medicine_list->total;
                    }
                    $discount += $inv->dis_amount;
                    $spl_discount += $inv->spl_dis_amount;
                }

                // Sort the grouped medicines by product ID (ascending order)
                ksort($groupedMedicines);

                // Initialize total sum variables
                $sumTotalPrice = 0;
                $sumVatAmount = 0;
                $sumTotal = 0;
            @endphp

            @foreach ($groupedMedicines as $medicine)
                @php
                    $totalPrice = $medicine['price'] * $medicine['initial_quantity'];
                    $vatAmount = round($totalPrice * $medicine['vat'] / 100, 2);

                    // Summing overall totals
                    $sumTotalPrice += $totalPrice;
                    $sumVatAmount += $vatAmount;
                    $sumTotal += $medicine['total'];
                @endphp

                <tr>
                    <td class="text-start border-dotted border-start">{{ $medicine['id'] }}</td>
                    <td class="text-start border-dotted border-start">{{ $medicine['name'] }}</td>
                    <td class="border-dotted border-start">{{ $medicine['pack_size'] }}</td>
                    <td class="border-dotted border-start">{{ $medicine['price'] }}</td>
                    <td class="border-dotted border-start">{{ $medicine['vat'] }}</td>
                    <td class="border-dotted border-start">{{ $medicine['initial_quantity'] }}</td>
                    <td class="border-dotted border-start"></td>
                    <td class="border-dotted border-start"></td>
                    <td class="border-dotted border-start"></td>
                    <td class="border-dotted border-start"></td>
                    <td class="border-dotted border-start"></td>
                    <td class="border-dotted border-start border-end"></td>
                </tr>
            @endforeach
        </table>
        <table class="table">
            <tr class="text-start">
                <td>
                    <p class="underline text-bold">Return</p>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>TP:</span>
                        <span class="text-start" style='width:60%;'></span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>VAT:</span>
                        <span class="text-start" style='width:60%;'></span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Discount:</span>
                        <span class="text-start" style='width:60%;'></span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Special Dis:</span>
                        <span class="text-start" style='width:60%;'></span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Total:</span>
                        <span class="text-start" style='width:60%;'></span>
                    </div>
                </td>
                <td>
                    <p class="underline text-bold">Issue</p>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>TP:</span>
                        <span class="text-start" style='width:60%;'>{{$sumTotalPrice}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>VAT:</span>
                        <span class="text-start" style='width:60%;'>{{$sumVatAmount}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Discount:</span>
                        <span class="text-start" style='width:60%;'>{{$discount}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Special Dis:</span>
                        <span class="text-start" style='width:60%;'>{{$spl_discount}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Total:</span>
                        <span class="text-start" style='width:60%;'>{{$sumTotalPrice + $sumVatAmount - $discount - $spl_discount}}</span>
                    </div>
                </td>
                <td>
                    <p class="underline text-bold">Net Amount</p>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>TP:</span>
                        <span class="text-start" style='width:60%;'>{{$sumTotalPrice}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>VAT:</span>
                        <span class="text-start" style='width:60%;'>{{$sumVatAmount}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Discount:</span>
                        <span class="text-start" style='width:60%;'>{{$discount}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Special Dis:</span>
                        <span class="text-start" style='width:60%;'>{{$spl_discount}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Total:</span>
                        <span class="text-start" style='width:60%;'>{{$sumTotalPrice + $sumVatAmount - $discount - $spl_discount}}</span>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4" class="text-center"><span class="text-italic">Net Payable Amount : </span><span class="text-uppercase">IN WORD: taka. {{ Illuminate\Support\Number::spell($sumTotalPrice + $sumVatAmount - $discount - $spl_discount, locale: 'en')}} only</span> </td>
            </tr>
        </table>
    </div>

    {{-- Invoice compress Bill --}}
    <div class="p-1 h-100">
        <div>
            <!--<div style="display: inline-block; width: 45%; float: left;">-->
            <!--    <p class="m-0 subtitle ">Sales Office :</p>-->
            <!--    <p class="m-0">Address : {{$site_data->site_address}}</p>-->
            <!--    <p class="m-0">Mobile : {{$site_data->site_phone}}</p>-->
            <!--</div>-->
            <div class="title" style="text-align: center;">Chemist Summary</div>
        </div>
        @php
            $inv_user_data = $invoice_data->first();
        @endphp
        <table class="table border">
            <tr>
                <td >Summary ID: {{$inv_user_data->summary_id}}</td>
                <td >TSE ID: {{$inv_user_data->fieldOfficer->user_id}}</td>
                <td>Delivery Man ID: {{$inv_user_data->deliveredBy->user_id}}</td>
            </tr>
            <tr>
                <td>Summary Dt: {{ date('d-M-Y', strtotime($inv_user_data->delivery_date))}}</td>
                <td>TSE Name: {{$inv_user_data->fieldOfficer->name}}</td>
                <td>Delivery Man: {{$inv_user_data->deliveredBy->name}}</td>
            </tr>
            <tr>
                <td></td>
                <td>Mobile: {{$inv_user_data->fieldOfficer->mobile}}</td>
                <td>Mobile: {{$inv_user_data->deliveredBy->mobile}}</td>
            </tr>
        </table>
        <table class="table">
            <tr>
                <td>Total Invoice : {{$invoice_data->count()}}</td>
                <td>Invoices # : {{ $invoice_data->pluck('invoice_no')->implode(', ') }}</td>
                <td></td>
            </tr>
        </table>

        <table class="items text-center border">
            <tr class="border">
                <th class="border-start" style="width: 10%">Code</th>
                <th class="border-start" style="width: 50%">Customer Name</th>
                <th class="border-start" style="width: 10%">Invoice NO.</th>
                <th class="border-start" style="width: 10%">Net Amount</th>
                <th class="border-start" style="width: 10%">Return Amount</th>
                <th class="border-start" style="width: 10%">Collection</th>
            </tr>
            @foreach ($invoice_data as $invoice)
                <tr class="border">
                    <td class="border-start">{{$invoice->customer->user_id}}</td>
                    <td class="border-start text-start">{{$invoice->customer->name}}<br><span style="font-size: 11px;">Address : {{$invoice->customer->address}}</span><br><span style="font-size: 11px;">Mobile :{{$invoice->customer->mobile}}</span></td>
                    <td class="border-start">{{$site_data->site_invoice_prefix.($site_data->site_invoice_prefix ? '-' : '')}}{{$invoice->invoice_no}}</td>
                    <td class="border-start">{{$invoice->grand_total}}</td>
                    <td class="border-start"></td>
                    <td class="border-start">{{$invoice->paid}}</td>
                </tr>
                @php
                    $sumGrandTotal += $invoice->grand_total;
                    $sumTotalCollection += $invoice->paid;
                @endphp
            @endforeach
            <tr>
                <td class="border-start" colspan="3"> <b>Total</b> </td>
                <td class="border-start"><b>{{$sumGrandTotal}}</b></td>
                <td class="border-start">-</td>
                <td class="border-start"><b>{{$sumTotalCollection}}</b></td>
            </tr>
        </table>
        {{-- <table class="table">
            <tr class="text-start">
                <td>
                    <p class="underline text-bold">Return</p>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>TP:</span>
                        <span class="text-start" style='width:60%;'></span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>VAT:</span>
                        <span class="text-start" style='width:60%;'></span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Discount:</span>
                        <span class="text-start" style='width:60%;'></span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Special Dis:</span>
                        <span class="text-start" style='width:60%;'></span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Total:</span>
                        <span class="text-start" style='width:60%;'></span>
                    </div>
                </td>
                <td>
                    <p class="underline text-bold">Issue</p>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>TP:</span>
                        <span class="text-start" style='width:60%;'>{{$sumTotalPrice}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>VAT:</span>
                        <span class="text-start" style='width:60%;'>{{$sumVatAmount}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Discount:</span>
                        <span class="text-start" style='width:60%;'>{{$discount}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Special Dis:</span>
                        <span class="text-start" style='width:60%;'>{{$spl_discount}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Total:</span>
                        <span class="text-start" style='width:60%;'>{{$sumTotalPrice + $sumVatAmount - $discount - $spl_discount}}</span>
                    </div>
                </td>
                <td>
                    <p class="underline text-bold">Net Amount</p>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>TP:</span>
                        <span class="text-start" style='width:60%;'>{{$sumTotalPrice}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>VAT:</span>
                        <span class="text-start" style='width:60%;'>{{$sumVatAmount}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Discount:</span>
                        <span class="text-start" style='width:60%;'>{{$discount}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Special Dis:</span>
                        <span class="text-start" style='width:60%;'>{{$spl_discount}}</span>
                    </div>
                    <div style="width: 100%;">
                        <span class="text-start" style='width:40%;'>Total:</span>
                        <span class="text-start" style='width:60%;'>{{$sumTotalPrice + $sumVatAmount - $discount - $spl_discount}}</span>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4" class="text-center"><span class="text-italic">Net Payable Amount : </span><span class="text-uppercase">IN WORD: taka. {{ Illuminate\Support\Number::spell($sumTotalPrice + $sumVatAmount - $discount - $spl_discount, locale: 'en')}} only</span> </td>
            </tr>
        </table> --}}
    </div>

    {{-- Individual Invoice --}}
    @foreach ($invoice_data as $inv_data)
        <div class="p-1 h-100">
            <div>
                <!--<div style="display: inline-block; width: 45%; float: left;">-->
                <!--    <p class="m-0 subtitle ">Sales Office :</p>-->
                <!--    <p class="m-0">Address : {{$site_data->site_address}}</p>-->
                <!--    <p class="m-0">Mobile : {{$site_data->site_phone}}</p>-->
                <!--</div>-->
                <div class="title" style="text-align: center;">Invoice</div>
            </div>
            <table class="table border">
                <tr>
                    <td >Cust ID: {{$inv_data->customer->user_id}}</td>
                    <td >TSE ID: {{$inv_data->fieldOfficer->user_id}}</td>
                    <td>Category: {{$inv_data->customer->category}}</td>
                </tr>
                <tr>
                    <td>Name: {{$inv_data->customer->name}}</td>
                    <td>Name: {{$inv_data->fieldOfficer->name}}</td>
                    <td>Invoice No: {{$inv_data->invoice_no}}</td>
                </tr>
                <tr>
                    <td>Address: {{$inv_data->customer->address}}</td>
                    <td>Mobile: {{$inv_data->fieldOfficer->mobile}}</td>
                    <td>Invoice Date: {{ date('d-M-Y', strtotime($inv_data->created_at))}}</td>
                </tr>
                <tr>
                    <td>Mobile: {{$inv_data->customer->mobile}}</td>
                    <td>Route: {{$inv_data->customer->route}}</td>
                    <td>Delivery Date: {{ date('d-M-Y', strtotime($inv_data->delivery_date))}}</td>
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

                @foreach ($inv_data->salesMedicines as $medicine_list)
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
                    <td class="border" colspan="2">Discount on TP ({{$inv_data->discount+$inv_data->spl_discount}}%):</td>
                    <td class="border">{{$inv_data->dis_amount+$inv_data->spl_dis_amount}}</td>
                </tr>
                <tr>
                    <td colspan="5" class="subtitle text-uppercase">IN WORD: taka. {{ Illuminate\Support\Number::spell($inv_data->grand_total, locale: 'en')}} only</td>
                    <td class="border" colspan="2"><b>Net Payable Amount:</b></td>
                    <td class="border"><b>{{$inv_data->grand_total}}</b></td>
                </tr>
            </table>
        </div>
    @endforeach
</body>
<script>
    window.print();
</script>
</html>


@php

    $content    =   ob_get_clean();

    $conf       =   [
        'mode'          =>  'utf-8',
        // 'format'        =>  [224, 286],
        'tempDir'       =>  storage_path('temp'),
        'orientation'   => 'portrait',
        'margin_left' => 6,
        'margin_right' => 6,
        // 'orientation'   => 'L'
    ];

    $mpdf = new \Mpdf\Mpdf($conf);
    

    $dateTime = date("d/m/Y,  h:i A", time());
    $html = "
        <div style='width:100%; text-align:center; margin-bottom: 20px;'>
            <!--<table style='margin: 0 auto;'>-->
            <!--    <tr>-->
            <!--        <td style='vertical-align: middle;'>-->
            <!--            <img src='$pdf_logo' alt='' style='width: 50px;'>-->
            <!--        </td>-->
            <!--        <td style='vertical-align: middle; padding-left: 10px;'>-->
            <!--            <h1 style='margin: 0; text-transform: uppercase; font-style: italic;'>$pdf_title</h1>-->
            <!--        </td>-->
            <!--    </tr>-->
            <!--</table>-->
        </div>
    ";




    $mpdf->SetHTMLHeader($html);
    $mpdf->SetTopMargin(20);
    $mpdf->AddPage();
    // $mpdf->SetWatermarkImage(public_path('img/logo.png'), 0.1); // Path to watermark image
    $mpdf->showWatermarkImage = true;

    $mpdf->SetHTMLFooter("
        <div class='footer text-center' style='width:100%;'>
            <div style='width:19%; float: left;'>
                <p>-------------------</p>
                <p>Powered By</p>
            </div>
            <div style='width:19%; float: left;'>
                <p>-------------------</p>
                <p>Authorized By</p>
            </div>
            <div style='width:19%; float: left;'>
                <p>-------------------</p>
                <p>Delivered By</p>
            </div>
            <div style='width:19%; float: left;'>
                <p>-------------------</p>
                <p>Collected By</p>
            </div>
            <div style='width:19%; float: left;'>
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

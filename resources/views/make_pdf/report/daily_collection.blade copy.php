
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
        <table class="items text-center table-border">
            <tr>
                <th rowspan="2">SL</th>
                <th rowspan="2">Ecode</th>
                <th rowspan="2">Name of ZSE/TSE</th>
                <th rowspan="2">Territory</th>
                <th rowspan="2">Designation</th>
                <th rowspan="2">Joining Date</th>
                <th colspan="2">Invoice</th>
                <th rowspan="2">Target<br>({{Carbon\Carbon::parse($end_date)->format('M')}})</th>
                <th rowspan="2">Dise</th>
                <th rowspan="2">Total Vat</th>
                <th rowspan="2">Sales Return</th>
                <th rowspan="2">Total TP Sales</th>
                <th rowspan="2">Actual Sales<br>(TP+Vat)</th>
                <th rowspan="2">Total Collection</th>
                <th rowspan="2">Net Due</th>
                <th rowspan="2">Target Achieved</th>
            </tr>
            <tr>
                <th>C.Mon</th>
                <th>L.Mon</th>
            </tr>
            @php
                $count = 1;
                // $start_date_month = Carbon\Carbon::parse($start_date)->format('M');
                // $end_date_month = Carbon\Carbon::parse($end_date)->format('M');
                // if($start_date_month === $end_date_month && $start_date_month === date('M') && $end_date_month === date('M')){
                //     $c_mon_date = Carbon\Carbon::parse($end_date)->format('m');
                //     $l_mon_date = Carbon\Carbon::parse($start_date)->subMonth()->format('m');
                // }else{
                //     $c_mon_date = Carbon\Carbon::parse($end_date)->format('m');
                //     $l_mon_date = Carbon\Carbon::parse($start_date)->format('m');
                // }
                
                // dd($start_date_month,$end_date_month,$c_mon_date,$l_mon_date,date('m'));
            @endphp
            @foreach ($user_data_all as $user_data)
                @php
                    $under_user_datas = App\Models\User::where(rolesConvertShort($user_data->role).'_id', $user_data->id)->whereNotIn('role', ['Super Admin','Depo Incharge','Delivery Man','Customer'])->get();
                    $c_mon_total = 0;
                @endphp
                
                @foreach ($under_user_datas as $under_user_data)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $under_user_data->user_id }}</td>
                        <td>{{ $under_user_data->name }}</td>
                        <td>{{ $under_user_data->route }}</td>
                        <td>{{ rolesConvert($under_user_data->role) }}</td>
                        <td>{{ Carbon\Carbon::parse($under_user_data->joining_date)->format('d M Y') }}</td>
                        <td>
                            @if (!empty($under_user_data->role))
                                @php
                                    $roleShort = rolesConvertShort($under_user_data->role);
                                    $c_mon = 0;
                                    $l_mon = 0;
                                @endphp
                                @if ($roleShort)
                                    @php
                                        $c_mon = App\Models\Invoice::where($roleShort . '_id', $under_user_data->id)
                                            ->whereMonth('invoice_date', $c_mon_date)
                                            ->count();
                                        $c_mon_total += $c_mon;
                                    @endphp
                                    {{ $c_mon }} <!-- Output the count -->
                                @else
                                    0 <!-- or a message indicating an invalid role -->
                                @endif
                            @else
                                0
                            @endif
                        </td>                        
                        <td>
                            @if (!empty($under_user_data->role))
                                @php
                                    $roleShort = rolesConvertShort($under_user_data->role);
                                    $l_mon = 0;
                                @endphp
                                @if ($roleShort)
                                    @php
                                        $l_mon = App\Models\Invoice::where($roleShort . '_id', $under_user_data->id)
                                            ->whereMonth('invoice_date', date('m'))
                                            ->count();
                                        $l_mon_total += $l_mon;
                                    @endphp
                                    {{ $l_mon }} <!-- Output the count -->
                                @else
                                    0 <!-- or a message indicating an invalid role -->
                                @endif
                            @else
                                0
                            @endif
                        </td> 
                        <td>{{ App\Models\TargetReport::where('user_id', $under_user_data->id)->where('target_month', Carbon\Carbon::parse($end_date)->format('MM'))->where('target_year', Carbon\Carbon::parse($end_date)->format('Y'))->first()->target ?? 0 }}</td>                       
                    </tr>
                @endforeach
                <tr style="background-color: #f1f598cc;">
                    <td></td>
                    <td>{{ $user_data->user_id }}</td>
                    <td>{{ $user_data->name }}</td>
                    <td>{{ $user_data->route }}</td>
                    <td>{{ rolesConvert($user_data->role) }}</td>
                    <td>{{ Carbon\Carbon::parse($user_data->joining_date)->format('d M Y') }}</td>
                    <td>{{ $c_mon_total ?? 0 }}</td>
                    <td>{{ $$l_mon_total ?? 0 }}</td>
                    <td>{{ App\Models\TargetReport::where('user_id', $user_data->id)->where('target_month', Carbon\Carbon::parse($end_date)->format('F'))->where('target_year', Carbon\Carbon::parse($end_date)->format('Y'))->first()->sales_target ?? 0 }}</td>
                </tr>
                <tr style="border:none;">
                    <td colspan="14" style="height: 10px;"></td>
                </tr>

                {{-- @php
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
                        <td>{{$medicine_list->medicine->name}}</td>
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
                    <td>{{$sumTotalPrice}}</td>
                    <td>{{$sumVatAmount}}</td>
                    <td>{{$sumTotal}}</td>
                </tr>

                <tr></tr>
                <tr>
                    <td colspan="5"></td>
                    <td colspan="2">Discount on TP ({{$inv_data->discount+$inv_data->spl_discount}}%):</td>
                    <td>{{$inv_data->dis_amount+$inv_data->spl_dis_amount}}</td>
                </tr>
                <tr>
                    <td colspan="5" class="subtitle text-uppercase">IN WORD: taka. {{ Illuminate\Support\Number::spell($inv_data->grand_total, locale: 'en')}} only</td>
                    <td colspan="2"><b>Net Payable Amount:</b></td>
                    <td><b>{{$inv_data->grand_total}}</b></td>
                </tr> --}}
            @endforeach
        </table>
    </div>
</body>
<script>
    window.print();
</script>
</html>


@php

    $content    =   ob_get_clean();

    $conf       =   [
        'mode'          =>  'utf-8',
        'format'        =>  [224, 286],
        'tempDir'       =>  storage_path('temp'),
        'orientation'   => 'landscape',
        'margin_left' => 6,
        'margin_right' => 6,
        // 'orientation'   => 'L'
    ];

    $mpdf = new \Mpdf\Mpdf($conf);
    $dateTime = date("d/m/Y,  h:i A", time());
    $html = "<div style='width:100%; text-align:center;'>
                <div style='font-size:18px;text-align:center'>
                    <h3 class='fw-bolder border-bottom m-0'>$pdf_title</h3>
                    <h6 class='fw-bolder border-bottom m-0'>ZSE/TSE Wise Sales & Callection  Statement Period: $start_date to $end_date</h4>
                </div>
            </div>";

        $mpdf->SetHTMLHeader($html);
        $mpdf->SetTopMargin(20);

    $mpdf->SetHTMLFooter("
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

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
                <th rowspan="2">Ecode</th>
                <th rowspan="2">Name of ZSE/TSE</th>
                <th rowspan="2">Territory</th>
                <th rowspan="2">Designation</th>
                <th rowspan="2">Joining Date</th>
                <th colspan="2">Invoice</th>
                <th rowspan="2">Target<br>({{ $end_date->format('M') }})</th>
                <th rowspan="2">Dise</th>
                <th rowspan="2">Total Vat</th>
                <th rowspan="2">Sales Return</th>
                <th rowspan="2">Total TP Sales</th>
                <th rowspan="2">Actual Sales<br>(TP+Vat-Dise)</th>
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
            @endphp
            @foreach ($user_data_all as $user_data)
                @php
                    $under_users = App\Models\User::where(rolesConvertShort($user_data->role) . '_id', $user_data->id)
                        ->where('role', underRole($user_data->role))
                        ->get();
                    $sales_target_total = App\Models\TargetReport::where('user_id', $user_data->id)
                            ->where('target_month', $end_date->format('F'))
                            ->where('target_year', $end_date->format('Y'))
                            ->value('sales_target') ?? 0;
                    $c_mon_total = $c_dise_total = $c_vat_total = $c_sales_return_total = $c_tp_total = $c_actual_total = $c_collection_total = $c_due_total = $l_mon_total = 0;
                @endphp
                
                @foreach ($under_users as $under_user)
                    @php
                        $roleShort = rolesConvertShort($under_user->role);
                        $c_mon = $l_mon = $c_dise = $c_vat = $c_sales_return = $c_tp = $c_actual = $c_collection = $c_due = 0;

                        if ($roleShort) {
                            $invoice_data = App\Models\Invoice::where($roleShort . '_id', $under_user->id);
                            $c_invoice_data = (clone $invoice_data)->whereBetween('invoice_date', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')]);
                            
                            $c_mon = (clone $c_invoice_data)->count();
                            $l_mon = (clone $invoice_data)->whereBetween('invoice_date', [$start_date->copy()->subDays(30),$start_date])->count();
                            
                            $sales_target = App\Models\TargetReport::where('user_id', $under_user->id)
                                        ->where('target_month', $end_date->format('F'))
                                        ->where('target_year', $end_date->format('Y'))
                                        ->value('sales_target') ?? 0 ;

                            // $c_dise = (clone $c_invoice_data)->sum('dis_amount') + (clone $c_invoice_data)->sum('spl_dis_amount');

                            foreach ((clone $c_invoice_data)->get() as $invoice) {
                                $afterReturnPrice = $invoice->sub_total - $invoice->salesReturnMedicines->sum('total_price');
                                $afterReturnVat = $invoice->vat - $invoice->salesReturnMedicines->sum('vat');
                                // $sumReturnTotal = $invoice->salesReturnMedicines->sum('total');

                                $discount_data = json_decode($invoice->discount_data);
                                $newDiscount = App\Models\DiscountValue::where('discount_type', 'General')
                                            ->where('start_amount', '<=', $afterReturnPrice)
                                            ->where('end_amount', '>=', $afterReturnPrice)
                                            ->pluck('discount')
                                            ->first();

                                if (!empty($discount_data) && $discount_data->start_amount <= $afterReturnPrice && $afterReturnPrice <= $discount_data->end_amount) {
                                    $afterReturnDis = ($afterReturnPrice * $invoice->discount) / 100;
                                    $afterReturnDue = ($afterReturnPrice - $afterReturnDis) + $afterReturnVat; 
                                } elseif ($newDiscount !== null) {
                                    $afterReturnDis = ($afterReturnPrice * $newDiscount) / 100;
                                    $afterReturnDue = ($afterReturnPrice + $afterReturnVat) - ($afterReturnPrice * $newDiscount / 100);
                                } 
                                // else {
                                //     $afterReturnDue = $afterReturnPrice + $afterReturnVat;
                                // }
                                $actualDue = round(max($afterReturnDue - $invoice->paid, 0), 2);

                                $c_dise += $afterReturnDis;
                                $c_vat += $afterReturnVat;
                                $c_due += $actualDue;
                            }

                            // $c_vat = (clone $c_invoice_data)->sum('vat');
                            // $c_dis_amount = (clone $c_invoice_data)->sum('dis_amount');
                            // $c_spl_dis_amount = (clone $c_invoice_data)->sum('spl_dis_amount');
                            $c_sales_return = App\Models\ReturnMedicine::whereIn('invoice_id', $invoice_data->pluck('id'))->whereBetween('return_date', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])->sum('total');
                            $c_tp = (clone $c_invoice_data)->sum('sub_total');
                            $c_actual = $c_tp + $c_vat - ($c_dis_amount + $c_spl_dis_amount) - $c_sales_return;
                            $c_collection = (clone $c_invoice_data)->sum('paid');

                            $c_dise_total += round($c_dise);
                            $c_vat_total += round($c_vat);
                            $c_sales_return_total += round($c_sales_return);
                            $c_tp_total += round($c_tp);
                            $c_actual_total += round($c_actual);
                            $c_collection_total += round($c_collection);
                            $c_due_total += round($c_due);
                            $c_mon_total += round($c_mon);

                            $l_mon_total += $l_mon;
                        }
                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $under_user->user_id }}</td>
                        <td>{{ $under_user->name }}</td>
                        <td>{{ $under_user->route }}</td>
                        <td>{{ rolesConvert($under_user->role) }}</td>
                        <td>{{ Carbon\Carbon::parse($under_user->created_at)->format('d M Y') }}</td>
                        <td>{{ $c_mon }}</td>
                        <td>{{ $l_mon }}</td>
                        <td>{{ round($sales_target) }}</td>
                        <td>{{ round($c_dise) }}</td>
                        <td>{{ round($c_vat) }}</td>
                        <td>{{ round($c_sales_return) }}</td>
                        <td>{{ round($c_tp) }}</td>
                        <td>{{ round($c_actual) }}</td>
                        <td>{{ round($c_collection) }}</td>
                        <td>{{ round($c_due) }}</td>
                        <td>{{ round($sales_target == 0 ? 0 : ($c_tp / $sales_target) * 100, 2) }}</td>
                    </tr>
                @endforeach

                <tr style="background-color: #f1f598cc;">
                    <td></td>
                    <td>{{ $user_data->user_id }}</td>
                    <td>{{ $user_data->name }}</td>
                    <td>{{ $user_data->route }}</td>
                    <td>{{ rolesConvert($user_data->role) }}</td>
                    <td>{{ Carbon\Carbon::parse($user_data->created_at)->format('d M Y') }}</td>
                    <td>{{ $c_mon_total }}</td>
                    <td>{{ $l_mon_total }}</td>
                    <td>{{ round($sales_target_total) }}</td>
                    <td>{{ $c_dise_total }}</td>
                    <td>{{ $c_vat_total }}</td>
                    <td>{{ $c_sales_return_total }}</td>
                    <td>{{ $c_tp_total }}</td>
                    <td>{{ $c_actual_total }}</td>
                    <td>{{ $c_collection_total }}</td>
                    <td>{{ $c_due_total }}</td>
                    <td>{{ round($sales_target_total == 0 ? 0 : ($c_tp_total / $sales_target_total) * 100, 2) }}</td>
                </tr>
                <tr style="border:none;">
                    <td colspan="14" style="height: 10px;"></td>
                </tr>
                
                @php
                    $c_dise_grand_total += $c_dise_total;
                    $c_vat_grand_total += $c_vat_total;
                    $c_sales_return_grand_total += $c_sales_return_total;
                    $c_tp_grand_total += $c_tp_total;
                    $c_actual_grand_total += $c_actual_total;
                    $c_collection_grand_total += $c_collection_total;
                    $c_due_grand_total += $c_due_total;
                    $c_mon_grand_total += $c_mon_total;
                    $l_mon_grand_total += $$l_mon_total;
                @endphp
            @endforeach

            @if ($manager_data)    
                <tr style="background-color: #f5918acc;">
                    <td></td>
                    <td>{{$manager_data->user_id}}</td>
                    <td>{{$manager_data->name}}</td>
                    <td>{{$manager_data->route}}</td>
                    <td>{{$manager_data->role}}</td>
                    <td>{{Carbon\Carbon::parse($manager_data->created_at)->format('d M Y')}}</td>
                    
                    <td>{{ $c_mon_grand_total }}</td>
                    <td>{{ $l_mon_grand_total }}</td>
                    <td>{{ App\Models\TargetReport::where('user_id', $manager_data->id)
                        ->where('target_month', $end_date->format('F'))
                        ->where('target_year', $end_date->format('Y'))
                        ->value('sales_target') ?? 0 }}</td>
                    <td>{{ $c_dise_grand_total }}</td>
                    <td>{{ $c_vat_grand_total }}</td>
                    <td>{{ $c_sales_return_grand_total }}</td>
                    <td>{{ $c_tp_grand_total }}</td>
                    <td>{{ $c_actual_grand_total }}</td>
                    <td>{{ $c_collection_grand_total }}</td>
                    <td>{{ $c_due_grand_total }}</td>
                    <td></td>
                </tr>
            @endif
            <tr style="background-color: #77e5edcc;">
                <td colspan="6">Depo Total</td>
                <td>{{ $c_mon_grand_total }}</td>
                <td>{{ $l_mon_grand_total }}</td>
                <td></td>
                <td>{{ $c_dise_grand_total }}</td>
                <td>{{ $c_vat_grand_total }}</td>
                <td>{{ $c_sales_return_grand_total }}</td>
                <td>{{ $c_tp_grand_total }}</td>
                <td>{{ $c_actual_grand_total }}</td>
                <td>{{ $c_collection_grand_total }}</td>
                <td>{{ $c_due_grand_total }}</td>
                <td></td>
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
                    ZSE/TSE Wise Sales & Collection Statement 
                    Period: " . $start_date->format('d M Y') . " to " . $end_date->format('d M Y') . "
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
    // $mpdf->SetWatermarkImage(public_path('img/logo.png')); // Path to watermark image
    $mpdf->showWatermarkImage = true;
    $mpdf->Output();
@endphp

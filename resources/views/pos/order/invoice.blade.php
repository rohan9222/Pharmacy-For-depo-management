<div class="table-responsive">
<div id="invoice" class="row">
   <div class="col-xs-12 col-md-12">
		<style id="styles" type="text/css">
/*Common CSS*/
        .receipt-template {
            width:302px;
            margin:0 auto;
        }
        .receipt-template .text-small {
            font-size: 10px;
        }
        .receipt-template .block {
            display: block;
        }
        .receipt-template .inline-block {
            display: inline-block;
        }
        .receipt-template .bold {
            font-weight: 700;
        }
        .receipt-template .italic {
            font-style: italic;
        }
        .receipt-template .align-right {
            text-align: right;
        }
        .receipt-template .align-center {
            text-align: center;
        }
        .receipt-template .main-title {
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            margin: 10px 0 5px 0;
            padding: 0;
        }
        .receipt-template .heading {
            position: relation;
        }
        .receipt-template .title {
            font-size: 16px;
            font-weight: 700;
            margin: 10px 0 5px 0;
        }
        .receipt-template .sub-title {
            font-size: 12px;
            font-weight: 700;
            margin: 10px 0 5px 0;
        }
        .receipt-template table {
            width: 100%;
        }
        .receipt-template td,
        .receipt-template th {
            font-size:12px;
        }
        .receipt-template .info-area {
            font-size: 12px;
            line-height: 1.222;
        }
        .receipt-template .listing-area {
            line-height: 1.222;
        }
        .receipt-template .listing-area table {}
        .receipt-template .listing-area table thead tr {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            font-weight: 700;
        }
        .receipt-template .listing-area table tbody tr {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }
        .receipt-template .listing-area table tbody tr:last-child {
            border-bottom: none;
        }
        .receipt-template .listing-area table td {
            vertical-align: top;
        }
        .receipt-template .info-area table {}
        .receipt-template .info-area table thead tr {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

 /*Receipt Heading*/
        .receipt-template .receipt-header {
            text-align: center;
        }
        .receipt-template .receipt-header .logo-area {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }
        .receipt-template .receipt-header .logo-area img.logo {
            display: inline-block;
            max-width: 100%;
            max-height: 100%;
        }
        .receipt-template .receipt-header .address-area {
            margin-bottom: 5px;
            line-height: 1;
        }
        .receipt-template .receipt-header .info {
            font-size: 12px;
        }
        .receipt-template .receipt-header .store-name {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            padding: 0;
        }


/*Invoice Info Area*/
    .receipt-template .invoice-info-area {}

/*Customer Customer Area*/
    .receipt-template .customer-area {
        margin-top:10px;
    }

/*Calculation Area*/
    .receipt-template .calculation-area {
        border-top: 2px solid #000;
        font-weight: bold;
    }
    .receipt-template .calculation-area table td {
        text-align: right;
    }
    .receipt-template .calculation-area table td:nth-child(2) {
        border-bottom: 1px dashed #000;
    }

/*Item Listing*/
    .receipt-template .item-list table tr {
    }

/*Barcode Area*/
    .receipt-template .barcode-area {
        margin-top: 10px;
        text-align: center;
    }
    .receipt-template .barcode-area img {
        max-width: 100%;
        display: inline-block;
    }

/*Footer Area*/
    .receipt-template .footer-area {
        line-height: 1.222;
        font-size: 10px;
    }

/*Media Query*/
    @media print {
        .receipt-template {
            width:302px;
            margin:0 auto;
        }
        .receipt-template .text-small {
            font-size: 10px;
        }
        .receipt-template .block {
            display: block;
        }
        .receipt-template .inline-block {
            display: inline-block;
        }
        .receipt-template .bold {
            font-weight: 700;
        }
        .receipt-template .italic {
            font-style: italic;
        }
        .receipt-template .align-right {
            text-align: right;
        }
        .receipt-template .align-center {
            text-align: center;
        }
        .receipt-template .main-title {
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            margin: 10px 0 5px 0;
            padding: 0;
        }
        .receipt-template .heading {
            position: relation;
        }
        .receipt-template .title {
            font-size: 16px;
            font-weight: 700;
            margin: 10px 0 5px 0;
        }
        .receipt-template .sub-title {
            font-size: 12px;
            font-weight: 700;
            margin: 10px 0 5px 0;
        }
        .receipt-template table {
            width: 100%;
        }
        .receipt-template td,
        .receipt-template th {
            font-size:12px;
        }
        .receipt-template .info-area {
            font-size: 12px;
            line-height: 1.222;
        }
        .receipt-template .listing-area {
            line-height: 1.222;
        }
        .receipt-template .listing-area table {}
        .receipt-template .listing-area table thead tr {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            font-weight: 700;
        }
        .receipt-template .listing-area table tbody tr {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }
        .receipt-template .listing-area table tbody tr:last-child {
            border-bottom: none;
        }
        .receipt-template .listing-area table td {
            vertical-align: top;
        }
        .receipt-template .info-area table {}
        .receipt-template .info-area table thead tr {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        /*Receipt Heading*/
        .receipt-template .receipt-header {
            text-align: center;
        }
        .receipt-template .receipt-header .logo-area {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }
        .receipt-template .receipt-header .logo-area img.logo {
            display: inline-block;
            max-width: 100%;
            max-height: 100%;
        }
        .receipt-template .receipt-header .address-area {
            margin-bottom: 5px;
            line-height: 1;
        }
        .receipt-template .receipt-header .info {
            font-size: 12px;
        }
        .receipt-template .receipt-header .store-name {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            padding: 0;
        }


        /*Invoice Info Area*/
        .receipt-template .invoice-info-area {}

        /*Customer Customer Area*/
        .receipt-template .customer-area {
            margin-top:10px;
        }

        /*Calculation Area*/
        .receipt-template .calculation-area {
            border-top: 2px solid #000;
            font-weight: bold;
        }
        .receipt-template .calculation-area table td {
            text-align: right;
        }
        .receipt-template .calculation-area table td:nth-child(2) {
            border-bottom: 1px dashed #000;
        }

        /*Item Listing*/
        .receipt-template .item-list table tr {
        }

        /*Barcode Area*/
        .receipt-template .barcode-area {
            margin-top: 10px;
            text-align: center;
        }
        .receipt-template .barcode-area img {
            max-width: 100%;
            display: inline-block;
        }

        /*Footer Area*/
        .receipt-template .footer-area {
            line-height: 1.222;
            font-size: 10px;
        }
    }
    @media all and (max-width: 215px) {}</style>
<section class="receipt-template">

        <header class="receipt-header">

            <h2 class="store-name">{{Auth::user()->shop->name}}</h2>
            <div class="address-area">
                <span class="info address">{{Auth::user()->shop->address}}</span>
                <div class="block">
                    <span class="info phone">Mobile: {{Auth::user()->shop->phone}}</span>, <span class="info email">Email: {{Auth::user()->shop->email}}</span>
                </div>
            </div>
        </header>

        <section class="info-area">
            <table>
                <tr>
                    <td class="w-30"><span>Invoice ID:</td>
                    <td>{{$order->id}}</td>
                </tr>

                <tr>
                    <td class="w-30"><span>Date:</td>
                    <td>{{date('d F, Y', strtotime($order->date))}}</td>
                </tr>
               @if($order->customer_id != 0)
                <tr>
                    <td class="w-30">Customer Name:</td>
                    <td>{{$order->customer->name}}</td>
                </tr>
                <tr>
                    <td class="w-30">Phone:</td>
                    <td>{{$order->customer->phone}}</td>
                </tr>
                <tr>
                    <td class="w-30">Address:</td>
                    <td>{{$order->customer->address}}</td>
                </tr>
               @else

                <tr>
                    <td class="w-30">Customer Name:</td>
                    <td>Walking Customer</td>
                </tr>

               @endif
            </table>
        </section>

        <h4 class="main-title">INVOICE</h4>
         @php
                $total = 0;
                $paid = \App\Models\InvoicePay::where('invoice_id', $order->id)->sum('amount');
                $medicine = json_decode($order['medicines'], true);
                $count = count($medicine);
                @endphp
                <section class="listing-area item-list">
            <table>
                <thead>
                    <tr>
                        <td class="w-10 text-center">Sl.</td>
                        <td class="w-40 text-center">Name</td>
                        <td class="w-15 text-center">Qty</td>
                        <td class="w-15 text-right">Price</td>
                        <td class="w-20 text-right">Amount</td>
                    </tr>
                </thead>
                <tbody>
                         @for ($i = 0; $i < $count; $i++)

                                @php
                 if(isset($medicine[$i]['batch'])){
                 $batch = \App\Models\Batch::find($medicine[$i]['batch']);
                $detail = \App\Models\Medicine::find($medicine[$i]['id']);
                $amount = ($batch->price*$medicine[$i]['quantity']);
                $total += $amount;
                @endphp
                                            <tr>
                            <td class="text-center">{{($i+1)}}</td>
                            <td>{{ Str::limit($detail->name, 150) }}

                                    <small>[{{ Str::limit($detail->strength, 50) }}]</small>
                                                            </td>
                            <td class="text-center">{{$medicine[$i]['quantity']}} </td>
                            <td class="text-right">{{$batch->price}}</td>
                            <td class="text-right">{{$amount}}</td>
                        </tr>
                 @php
                }
                @endphp
                @endfor

                </tbody>
            </table>
        </section>

        <section class="info-area calculation-area">
            <table>
                <tr>
                    <td class="w-70">Sub Total:</td>
                    <td>{{round($total,2)}}</td>
                </tr>
                <tr>
                    <td class="w-70">Discount:</td>
                    <td>{{round($order->discount,2)}}</td>
                </tr>


                <tr>
                    <td class="w-70">Amount Paid:</td>
                    <td>{{round($paid,2)}}</td>
                </tr>

                <tr>
                    <td class="w-70">Due:</td>
                    <td>{{round($order->due_price,2)}}</td>
                </tr>
                 <tr>
                    <td class="w-70">Total Amt:</td>
                    <td>{{round($order->total_price,2)}}</td>
                </tr>
            </table>
        </section>
        @php
        $f = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );

$word = $f->format(round($order->total_price,2));
        @endphp
        <section class="info-area italic">
            <span class="text-small"><b>In Text:</b> {{strtoupper($word)}} {{Auth::user()->shop->currency}} ONLY</span><br>
                    </section>


                <section class="listing-area payment-list">
            <div class="heading">
                <h2 class="sub-title">Payments</h2>
            </div>
            <table>
                <thead>
                    <td class="w-10 text-center">Sl.</td>
                    <td class="w-50 text-center">Payment Method</td>
                    <td class="w-20 text-right">Amount</td>
                    <td class="w-20 text-right">Balance</td>
                </thead>
                <tbody>
                                            <tr>
                            <td class="text-center">1</td>
                            <td>{{$order->method->name}}</td>
                            <td class="text-right">{{round($paid,2)}}</td>
                            <td class="text-right">{{round($order->due_price,2)}}</td>
                        </tr>

                </tbody>
            </table>
        </section>




        <section class="info-area align-center footer-area">
            <span class="block bold">Thank you for choosing us!</span>
            <span class="block">Software Developed By Ayaantech Limited. www.ayaantec.com</span>
            </section>

 </section>

</div>
</div>
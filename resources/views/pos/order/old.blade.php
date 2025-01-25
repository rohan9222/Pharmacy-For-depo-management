<div class="pvl31">
    <div class="text-center pt-4 mb-3">
        <h2 class="pvl32">{{Auth::user()->shop->name}}</h2>
        <h5 class="pvl33">
            {{Auth::user()->shop->address}}
        <h5 class="pvl33">
            {{\App\CPU\translate('Phone')}}
            : {{Auth::user()->shop->phone}}
        </h5> 
    </div>

    <span>--------------------------------------------------------------------------------------</span>
    <div class="row mt-3">
        <div class="col-6">
            <h5>{{\App\CPU\translate('Order ID')}} : {{$order['id']}}</h5>
        </div>
        <div class="col-6">
            <h5 class="pvl34">
                {{date('d/M/Y h:i a',strtotime($order['created_at']))}}
            </h5>
        </div>
        @if($order->customer)
            <div class="col-12">
                <h5>{{\App\CPU\translate('Customer Name')}} : {{$order->customer->name}}</h5>
                @if ($order->customer->id !=0)
                    <h5>{{\App\CPU\translate('Phone')}} : {{$order->customer->phone}}</h5>
                @endif
                
            </div>
        @endif
    </div>
    <h5 class="text-uppercase"></h5>
    <span>--------------------------------------------------------------------------------------</span>
    <table class="table table-bordered mt-3 pvl35">
        <thead>
        <tr>
            <th class="">{{\App\CPU\translate('Name')}}</th>
            <th class="pvl36">{{\App\CPU\translate('QTY')}}</th>
            <th class="">{{\App\CPU\translate('Price')}}</th>
        </tr>
        </thead>

        <tbody>
        
        @php
        $total = 0;
        $paid = \App\Models\InvoicePay::where('invoice_id', $order->id)->sum('amount');
        $medicine = json_decode($order['medicines'], true);
        $count = count($medicine);
        @endphp
                @for ($i = 0; $i < $count; $i++)
               
                @php
                 if(isset($medicine[$i]['batch'])){
                 $batch = \App\Models\Batch::find($medicine[$i]['batch']);
                $detail = \App\Models\Medicine::find($medicine[$i]['id']);
                $amount = ($batch->price*$medicine[$i]['quantity']);
                $total += $amount;
                @endphp
                <tr>
                    <td class="">
                        <span class="pvl37"> {{ Str::limit($detail->name, 150) }} ({{ Str::limit($detail->strength, 50) }})</span>
                    </td>
                    <td class="">
                        {{$medicine[$i]['quantity']}}
                    </td>
                    
                    <td class="pvl38">
                        {{$amount}}
                    </td>
                </tr>
                @php
                }
                @endphp
                @endfor
            
        </tbody>
    </table>
    <span>---------------------------------------------------------------------------------------</span>
   
   
    <div class="row justify-content-md-end">
        <div class="col-md-8 col-lg-8">
            <dl class="row text-right pvl39">
                <dt class="col-7">{{\App\CPU\translate('Subtotal')}}:</dt>
                <dd class="col-5">{{\currency_converter(round($total,2))}}</dd>
                
                <dt class="col-7">{{\App\CPU\translate('extra_discount')}}:</dt>
                <dd class="col-5">{{\currency_converter(round($order->discount,2))}}</dd>
                
                <dt class="col-7" >{{\App\CPU\translate('paid')}}:</dt>
                <dd class="col-5">{{\currency_converter(round($paid,2))}}</dd>
                
                <dt class="col-7" >{{\App\CPU\translate('due')}}:</dt>
                <dd class="col-5">{{\currency_converter(round($order->due_price,2))}}</dd>
                
                <dt class="col-7 pvl40">{{\App\CPU\translate('Total')}}:</dt>
                <dd class="col-5 pvl40">{{\currency_converter(round($order->total_price,2))}}</dd>
            </dl>
        </div>
    </div>
    <div class="d-flex flex-row justify-content-between border-top">
        <span>{{\App\CPU\translate('Paid_by')}}: {{$order->method->name}}</span>
    </div>
    <span>---------------------------------------------------------------------------------------</span>
    <h5 class="text-center pt-3">
        ~~{{\App\CPU\translate('THANK YOU')}}~~
    </h5>
    <span>---------------------------------------------------------------------------------------</span>
     <div class="d-flex flex-row justify-content-between border-top">
        <span class="text-center">Software Developed By Ayaan Tech Limited</span>
    </div>
</div>

<?php
$subtotal = 0;
$addon_price = 0;
$tax = 0;
$discount = 0;
$discount_type = 'amount';
$discount_on_product = 0;
$total_tax = 0;
$ext_discount = 0;
$ext_discount_type = 'amount';
$coupon_discount = 0;
$product_subtotal = 0;
$gateway = \App\Models\Method::where('shop_id', Auth::user()->shop_id)->get();
?>

<div class="table-responsive">
    <table class="table table-bordered" id="cart-table">
        <thead>
        <tr>
            <th>Medicine</th>
            <th>Batch</th>
            <th>Expiry Date</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Discount %</th>
            <th>Total</th>
            <th></th>
        </tr>
        </thead>

        <tbody>

        </tbody>
    </table>
</div>

<div class="d-flex flex-row pvl11">
    <table class="table table-bordered">
        <thead class="text-muted">
        <tr>
            <th scope="col">{{\App\CPU\translate('item')}}</th>
            <th scope="col" class="text-center">{{\App\CPU\translate('qty')}}</th>
            <th scope="col">{{\App\CPU\translate('price')}}</th>
            <th scope="col">{{\App\CPU\translate('delete')}}</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $subtotal = 0;
        $addon_price = 0;
        $tax = 0;
        $discount = 0;
        $discount_type = 'amount';
        $discount_on_product = 0;
        $total_tax = 0;
        $ext_discount = 0;
        $ext_discount_type = 'amount';
        $coupon_discount = 0;
        $product_subtotal = 0;
        $gateway = \App\Models\Method::where('shop_id', Auth::user()->shop_id)->get();
        ?>
        @if(session()->has($cart_id) && count( session()->get($cart_id)) > 0)
            <?php
            $cart = session()->get($cart_id);
            if (isset($cart['tax'])) {
                $tax = $cart['tax'];
            }
            if (isset($cart['discount'])) {
                $discount = $cart['discount'];
                $discount_type = $cart['discount_type'];
            }
            if (isset($cart['ext_discount'])) {
                $ext_discount = $cart['ext_discount'];
                $ext_discount_type = $cart['ext_discount_type'];
            }
            if (isset($cart['coupon_discount'])) {
                $coupon_discount = $cart['coupon_discount'];
            }
            ?>
            @foreach(session()->get($cart_id) as $key => $cartItem)
                @if(is_array($cartItem))
                    <?php

                    $product_subtotal = ($cartItem['price']) * $cartItem['quantity'];
                    $discount_on_product += ($cartItem['discount'] * $cartItem['quantity']);
                    $subtotal += $product_subtotal;

                    //tax calculation
                    $product = \App\Models\Medicine::find($cartItem['id']);
                    $total_tax += 0;

                    ?>
                    <tr>
                        <td class="media align-items-center">
                            <img class="avatar avatar-sm mr-1"
                                 src="{{asset('storage/images/medicine/'.$cartItem['image'].'')}}"
                                 onerror="this.src='{{asset('pos/img/160x160/img2.jpg')}}'"
                                 alt="{{$cartItem['name']}} image">
                            <div class="media-body">
                                <h5 class="text-hover-primary mb-0">{{Str::limit($cartItem['name'], 10)}}</h5>
                                <small>{{trans('Batch')}}: {{Str::limit($cartItem['batch'], 20)}}</small>

                            </div>
                        </td>
                        <td class="align-items-center text-center">
                            <input type="number" data-batch="{{$cartItem['batch']}}" data-key="{{$key}}" class="pvl12"
                                   value="{{$cartItem['quantity']}}" min="1"
                                   onkeyup="updateQuantity('{{$cartItem['id']}}','{{$cartItem['batch']}}',this.value,event)">
                        </td>
                        <td class="text-center px-0 py-1">
                            <div class="btn">
                                {{Auth::user()->shop->currency}} {{$product_subtotal}}
                            </div> <!-- price-wrap .// -->
                        </td>
                        <td class="align-items-center text-center">
                            <a href="javascript:removeFromCart({{$key}})" class="btn btn-sm btn-outline-danger"> <i
                                        class="tio-delete-outlined"></i></a>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
</div>

<?php
$total = $subtotal;
$discount_amount = $discount_on_product;
$total -= $discount_amount;

$extra_discount = $ext_discount;
$extra_discount_type = $ext_discount_type;
if ($extra_discount_type == 'percent' && $extra_discount > 0) {
    $extra_discount = (($subtotal) * $extra_discount) / 100;
}
if ($extra_discount) {
    $total -= $extra_discount;
}

$total_tax_amount = $total_tax;
?>
<div class="box p-3">
    <dl class="row text-sm-right">

        <dt class="col-sm-6">{{\App\CPU\translate('sub_total')}} :</dt>
        <dd class="col-sm-6 text-right"> {{Auth::user()->shop->currency}} {{$subtotal}}</dd>


        <dt class="col-sm-6">{{\App\CPU\translate('discount')}} :</dt>
        <dd class="col-sm-6 text-right">
            <button id="extra_discount" class="btn btn-sm" type="button" data-toggle="modal"
                    data-target="#add-discount">
                <i class="tio-edit"></i></button>
            {{Auth::user()->shop->currency}} {{round($extra_discount,2)}}
        </dd>

    <!--- <dt  class="col-sm-6">{{\App\CPU\translate('tax')}} : </dt>
           <dd class="col-sm-6 text-right"> <button id="tax" class="btn btn-sm" type="button" data-toggle="modal" data-target="#add-tax">
                    <i class="tio-edit"></i></button> {{Auth::user()->shop->currency}} {{round($total_tax_amount,2)}}</dd> --->


        <dt class="col-sm-6">{{\App\CPU\translate('total')}} :</dt>
        <dd class="col-sm-6 text-right h4 b">{{Auth::user()->shop->currency}} {{round($total,2)}}</dd>
    </dl>
    <div class="row">
        <div class="col-md-6 mb-2">
            <a href="#" class="btn btn-lg btn-block" style="background-color:#10163A; color:#fff;"
               onclick="emptyCart()"><i
                        class="fa fa-times-circle "></i> {{\App\CPU\translate('Cancel')}} </a>
        </div>
        <div class="col-md-6">
            <button id="submit_order" type="button" class="btn btn-lg btn-block"
                    style="background-color:#10163A; color:#fff;" data-toggle="modal" data-target="#paymentModal"><i
                        class="fa fa-shopping-bag"></i>
                {{\App\CPU\translate('Order')}} </button>
        </div>
    </div>
</div>

<div class="modal fade" id="add-discount" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{\App\CPU\translate('update_discount')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="">{{\App\CPU\translate('discount')}}</label>
                        <input type="number" id="dis_amount" class="form-control" name="discount">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="">{{\App\CPU\translate('type')}}</label>
                        <select name="type" id="type_ext_dis" class="form-control">
                            <option value="amount" {{$discount_type=='amount'?'selected':''}}>{{\App\CPU\translate('amount')}}
                                ()
                            </option>
                            <option value="percent" {{$discount_type=='percent'?'selected':''}}>{{\App\CPU\translate('percent')}}
                                (%)
                            </option>
                        </select>
                    </div>

                    <div class="form-group col-sm-12">
                        <button class="btn btn-primary" onclick="extra_discount();"
                                type="submit">{{\App\CPU\translate('submit')}}</button>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-coupon-discount" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{\App\CPU\translate('coupon_discount')}}</h5>
                <button id="coupon_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group col-sm-12">
                    <label for="">{{\App\CPU\translate('coupon_code')}}</label>
                    <input type="text" id="coupon_code" class="form-control" name="coupon_code">
                    {{-- <input type="hidden" id="user_id" name="user_id" > --}}
                </div>

                <div class="form-group col-sm-12">
                    <button class="btn btn-primary" type="submit"
                            onclick="coupon_discount();">{{\App\CPU\translate('submit')}}</button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-tax" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{\App\CPU\translate('update_tax')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('pos.tax')}}" method="POST" class="row">
                    @csrf
                    <div class="form-group col-12">
                        <label for="">{{\App\CPU\translate('tax')}} (%)</label>
                        <input type="number" class="form-control" name="tax" min="0">
                    </div>

                    <div class="form-group col-sm-12">
                        <button class="btn btn-primary" type="submit">{{\App\CPU\translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content pvl14">
            <div class="modal-header">
                <h3 class="modal-title">{{\App\CPU\translate('payment')}}</h3>
                <button id="payment_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('pos.order')}}" id='order_place' method="post" class="pvl15">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="">Total Amount ({{Auth::user()->shop->currency}}
                            )</label>
                        <div class="col-sm-9">
                            <input type="number" id="pvltotal" class="form-control" name="amount" min="0" step="0.01"
                                   value="{{\App\CPU\BackEndHelper::usd_to_currency($total+$total_tax_amount-$coupon_discount)}}"
                                   readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class=" col-sm-3 col-form-label" for="">Total Payment ({{Auth::user()->shop->currency}}
                            )</label>
                        <div class="col-sm-9">
                            <input id="pvlpaid" type="number" class="form-control" name="paid" min="0" step="0.01"
                                   value="{{\App\CPU\BackEndHelper::usd_to_currency($total+$total_tax_amount-$coupon_discount)}}"
                                   onkeyup="evaluateTotal()" onkeydown="evaluateTotal()">
                        </div>

                    </div>

                    <div class="form-group row">
                        <label class=" col-sm-3 col-form-label" for="">{{\App\CPU\translate('Due')}}
                            ({{Auth::user()->shop->currency}})</label>
                        <div class="col-sm-9">
                            <input id="pvldue" type="number" class="form-control" name="due" min="0" step="0.01"
                                   max="{{($total+$total_tax_amount-$coupon_discount)}}"
                                   value="0" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class=" col-sm-3 col-form-label" for="">Returned Amount ({{Auth::user()->shop->currency}}
                            )</label>
                        <div class="col-sm-9">
                            <input id="returnedAmount" type="number" readonly class="form-control"
                                   name="returned_amount" min="0" step="0.01"
                                   value="0" onkeyup="evaluateTotal()" onkeydown="evaluateTotal()">
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="">{{\App\CPU\translate('type')}}</label>
                        <div class="col-sm-9">
                            <select name="type" class="form-control" required>
                                @foreach($gateway as $method)
                                    <option value="{{$method->id}}">{{$method->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <button class="btn pvl13" id="order_complete"
                                type="submit">{{\App\CPU\translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="short-cut-keys" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{\App\CPU\translate('short_cut_keys')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span>{{\App\CPU\translate('to_click_order')}} : alt + O</span><br>
                <span>{{\App\CPU\translate('to_click_payment_submit')}} : alt + S</span><br>
                <span>{{\App\CPU\translate('to_close_payment_submit')}} : alt + Z</span><br>
                <span>{{\App\CPU\translate('to_click_cancel_cart_item_all')}} : alt + C</span><br>
                <span>{{\App\CPU\translate('to_click_add_new_customer')}} : alt + A</span> <br>
                <span>{{\App\CPU\translate('to_submit_add_new_customer_form')}} : alt + N</span><br>
                <span>{{\App\CPU\translate('to_click_short_cut_keys')}} : alt + K</span><br>
                <span>{{\App\CPU\translate('to_print_invoice')}} : alt + P</span> <br>
                <span>{{\App\CPU\translate('to_cancel_invoice')}} : alt + B</span> <br>
                <span>{{\App\CPU\translate('to_focus_search_input')}} : alt + Q</span> <br>
                <span>{{\App\CPU\translate('to_click_extra_discount')}} : alt + E</span> <br>
                <span>{{\App\CPU\translate('to_click_coupon_discount')}} : alt + D</span> <br>
                <span>{{\App\CPU\translate('to_click_clear_cart')}} : alt + X</span> <br>
                <span>{{\App\CPU\translate('to_click_new_order')}} : alt + R</span> <br>

            </div>
        </div>
    </div>
</div>
   
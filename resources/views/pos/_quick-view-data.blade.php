<div class="modal-header p-2">
    <h4 class="modal-title product-title">
    </h4>
    <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="">
        <!-- Product gallery-->
        <div class="d-flex align-items-center justify-content-center pvl41 active">
            <img class="img-responsive pvl42" src="{{ asset('storage/images/medicine/' . $product->image . '') }}"
                alt="Product image" width="">
            <div class="cz-image-zoom-pane"></div>
        </div>
        <!-- Product details-->
        <div class="details pvl43">
            <a href="#" class="h1 mb-2 product-title pvl44">{{ Str::limit($product->name, 26) }}</a>

            <div class="mb-3 text-dark">
                <span class="h3 font-weight-normal text-accent mr-1">
                    @if (session('stock') == 'emergency-stock')
                        Price: {{ Auth::user()->shop->currency }}
                        {{ number_format(\App\Models\Batch::where('emergency_stock_id', $product->id)->where('shop_id', Auth::user()->shop_id)->orderBy('id', 'desc')->first()->price,2) }}
                    @else
                        Price: {{ Auth::user()->shop->currency }}
                        {{ number_format(\App\Models\Batch::where('medicine_id', $product->id)->where('shop_id', Auth::user()->shop_id)->orderBy('id', 'desc')->first()->price,2) }}
                    @endif
                </span>
            </div>
        </div>
    </div>
    <div class="row pt-2">
        <div class="col-12">
            <?php
            $cart = false;
            if (session()->has('cart')) {
                foreach (session()->get('cart') as $key => $cartItem) {
                    if (is_array($cartItem) && $cartItem['id'] == $product['id']) {
                        $cart = $cartItem;
                    }
                }
            }
            
            ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>{{ translate('common.strength') }}</th>
                        <th>{{ translate('common.shelf') }}</th>
                        <th>{{ translate('common.leaf') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $product->strength }}</td>
                        <td>{{ $product->shelf }}</td>
                        <td>{{ $product->leaf->name }}</td>
                    </tr>
                    <!-- and so on... -->
                </tbody>
            </table>
            <form id="add-to-cart-form" class="mb-2">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <div class="position-relative {{ Session::get('direction') === 'rtl' ? 'ml-n4' : 'mr-n4' }} mb-3">

                    @php
                        $qty = 0;
                        if (session('stock') == 'emergency-stock') {
                            $batch = \App\Models\Batch::where('emergency_stock_id', $product->id)
                                ->where('shop_id', Auth::user()->shop_id)
                                ->where('qty', '>', 0)
                                ->where('expire', '>=', date('Y-m-d'))
                                ->get();
                        } else {
                            $batch = \App\Models\Batch::where('medicine_id', $product->id)
                                ->where('shop_id', Auth::user()->shop_id)
                                ->where('qty', '>', 0)
                                ->where('expire', '>=', date('Y-m-d'))
                                ->get();
                        }

                    @endphp
                </div>
                @if ($batch->count() > 0)
                    <div class="select-option">
                        <div class="h3 p-0">Select {{ trans('Batch') }}
                        </div>

                        <div class="d-flex justify-content-left flex-wrap pvl49">
                            <select class="pvl50" name="batch" id="select">
                                @foreach ($batch as $key => $option)
                                    <option id="{{ $option->id }}-{{ $option->id }}" value="{{ $option->id }}"
                                        @if ($key == 0) selected @endif autocomplete="off">
                                        {{ trans('Expired') }} {{ date('d F, Y', strtotime($option->expire)) }}
                                        ({{ trans('Stock') }} : {{ $option->qty }})
                                        (MRP : {{ number_format($option->price, 2) }} TK)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <!-- Quantity + Add to cart -->
                <div class="d-flex justify-content-end mt-5">
                    <div class="product-description-label mt-2 text-dark h3">{{ __('Quantity') }}:</div>
                    <div class="product-quantity d-flex align-items-center">
                        <div class="input-group input-group--style-2 pr-3 pvl48">
                            <span class="input-group-btn">
                                <button class="btn btn-number text-dark pvl47" type="button" data-type="minus"
                                    data-field="quantity" disabled="disabled">
                                    <i class="tio-remove  font-weight-bold"></i>
                                </button>
                            </span>
                            <input type="text" name="quantity"
                                class="form-control input-number text-center cart-qty-field" placeholder="1"
                                value="1" min="1" max="100">
                            <span class="input-group-btn">
                                <button class="btn btn-number text-dark pvl46" type="button" data-type="plus"
                                    data-field="quantity">
                                    <i class="tio-add  font-weight-bold"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters mt-2 text-dark" id="chosen_price_div">
                    <div class="col-10">
                        <div class="product-price">
                            <strong id="chosen_price"></strong>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-2">
                    <button class="btn btn-primary pvl45" onclick="addToCart()" type="button">
                        <i class="tio-shopping-cart"></i>
                        {{ trans('pages.add_to_cart') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    cartQuantityInitialize();
</script>
<script>
    $(document).on('ready', function() {
        console.log($product - > id)
    });
</script>
<script>
    function color_change(val) {
        console.log(val.id);
        $('.color-border').removeClass("border-add");
        $('#label-' + val.id).addClass("border-add");
    }
</script>

@if (session('stock') == 'emergency-stock')
    <div class="product-card card shadow-sm pvl26" onclick="emrgQuickView('{{$product->id}}')">
        <div class="card-header inline_product clickable p-3 pvl27">
            <div class="d-flex align-items-center justify-content-center d-block">
                <img src="{{asset('storage/images/medicine/'.$product['image'] ?? ''.'')}}"
                     onerror="this.src='{{asset('pos/img/160x160/imag.png')}}'"
                     class="pvl28">
            </div>
        </div>
        <div class="card-body inline_product text-center p-1 py-2 clickable">
            <h5 class="product-title1 text-dark font-weight-bold pvl30">
                {{ Str::limit($product['name'], 13) }} ({{ Str::limit($product['strength'], 13) }})
            </h5>
        </div>
    </div>
@else
    <div class="position-relative">
        <div class="product-card card shadow-sm pvl26 position-relative"
             onclick="ADD_TO_CART('{{$product->id}}','{{ route('pos.add-to-cart') }}')">
            <div class="card-header inline_product clickable p-3 pvl27">
                <div class="d-flex align-items-center justify-content-center d-block">
                    <img src="{{asset('storage/images/medicine/'.$product['image'] ?? ''.'')}}"
                         onerror="this.src='{{asset('pos/img/160x160/imag.png')}}'"
                         class="pvl28">
                </div>
            </div>
            <div class="card-body inline_product text-center p-1 py-2 clickable">
                <h5 class="product-title1 text-dark font-weight-bold pvl30">
                    {{ Str::limit($product['name'], 13) }} ({{ Str::limit($product['strength'], 13) }})
                </h5>
            </div>
        </div>
        @if ($product['total_quantity'] > 0)
            <span class="position-absolute top-0 end-0 badge bg-primary text-white m-1">
           QTY: {{ $product['total_quantity'] }}
        </span>
        @else
            <div class="stockout">
                <span class="badge bg-danger text-white">Stockout</span>
            </div>
        @endif
    </div>
@endif


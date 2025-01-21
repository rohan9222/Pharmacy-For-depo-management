@forelse($products as $product)
    <div class="col-lg-3 col-6 col-xl-3 px-2 mb-3">
        @include('pos._single_product',['product'=>$product])
    </div>
@empty
    <div class="col-lg-12">
        <div class="empty">
            <h1><i class="tio-dropbox"></i></h1>
            <h2 class="text-center">No Product Available!</h2>
        </div>
    </div>
@endforelse

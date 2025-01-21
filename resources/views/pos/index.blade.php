<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>Create a new invoice | Point of Sale</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('pwa-icon/icon-72x72.png') }}">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset('pos/css/vendor.min.css') }}">
    <link rel="stylesheet" href="{{ asset('pos/vendor/icon-set/style.css') }}">
    <!-- CSS Front Template -->
    <meta name="_token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('pos/css/theme.css') }}?time={{ time() }}">
    @stack('css_or_js')

    <link rel="stylesheet" href="{{ asset('pos/css/style.css') }}?time={{ time() }}">

    <script src="{{ asset('pos/vendor/hs-navbar-vertical-aside/hs-navbar-vertical-aside-mini-cache.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('pos/css/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('slick/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('slick/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('pos/css/pos.css') }}">
    <!-- PWA  -->
    <meta name="theme-color" content="#7367f0">
    <link rel="apple-touch-icon" href="{{ asset('pwa-icon/icon-72x72.png') }}">
    <link rel="manifest" href="/manifest.json">
    <style>
        .user-profile-button .dropdown-menu.show {
            left: -126px;
        }

        .category-slider .slick-slide {
            padding: 0 4px;
        }

        div#product-list {
            height: 85vh;
            overflow: hidden;
            overflow-y: scroll;
        }

        .dropdown-item i {
            font-size: 23px;
            color: #44444473;
        }
        .stockout {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff6b;
            backdrop-filter: blur(1px);
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
        }
    </style>
</head>

<body class="footer-offset pos-interface">
    {{-- loader --}}
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="loading" class="d-none">
                    <div class="pvl1">
                        <img width="200" src="{{ asset('pos/img/loader.gif') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $setting = Auth::user()->shop;
        $gateway = \App\Models\Method::groupBy('name')->latest()->get();
    @endphp

    <main id="content" role="main" class="main pointer-event">
        <section class="section-content padding-y-sm bg-default mt-2">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-2 category-slider">
                            @foreach ($categories as $item)
                                <label for="category{{ $item->id }}"
                                    class="category-box {{ $category == $item->id ? 'active' : '' }}">
                                    <input class="d-none" id="category{{ $item->id }}" type="radio"
                                        value="{{ $item->id }}" name="category"
                                        onchange="set_category_filter(this.value)">
                                    <h6>{{ $item->name }}</h6>
                                </label>
                            @endforeach
                        </div>
                        <div class="row mb-2">
                            <div class="col-12 col-sm-12 col-md-8 col-lg-9 px-1">
                                <form class="search-box">
                                    <div class="pre-append">
                                        <i class="tio-search"></i>
                                    </div>
                                    <input id="search" onkeyup="medicineSearch(this.value)" autocomplete="off"
                                        type="text" value="{{ $keyword ? $keyword : '' }}" name="search"
                                        class="form-control search-bar-input" placeholder="Search here"
                                        aria-label="Search here">
                                    <!-- Search -->
                                    <div class="append">
                                        <i class="tio-barcode"></i>
                                    </div>
                                    <div class="input-group-overlay input-group-merge input-group-flush">
                                        <div class="input-group-prepend">

                                        </div>

                                        <diV class="card search-card w-4 pvl5" style="font-weight:600">
                                            <div id="search-box" class="card-body search-result-box d-none"></div>
                                        </diV>
                                    </div>
                                    <!-- End Search -->
                                </form>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 px-1 my-2 my-lg-0">
                                <div class="input-group float-right">
                                    <select name="vendor" id="vendor" class="form-control js-select2-custom mx-1"
                                        title="select category" onchange="set_vendor_filter(this.value)">
                                        <option value="0">Select Vendor</option>
                                        @foreach ($vendors as $vdrItem)
                                            <option value="{{ $vdrItem->id }}"
                                                {{ $vendor == $vdrItem->id ? 'selected' : '' }}>{{ $vdrItem->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2" id="product-list">
                            @include('pos.products')
                            <div class="col-lg-12">
                                @if (session()->has('stock'))
                                    {!! $products->appends(['stock' => session('stock')])->links() !!}
                                @else
                                    {!! $products->links() !!}
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 pr-0">
                        <form onsubmit="placeOrder()" id="placeOrder" action="{{ route('pos.order') }}">
                            <div class="card bg-transparent shadow-none pr-1 pl-1 cart-table">
                                <div class="row mb-2 align-items-center">
                                    <div class="col-lg-4 my-2 my-lg-2">
                                        <button class="w-100 d-inline-block btn btn-info btn rounded"
                                            id="add_new_customer" type="button" data-toggle="modal"
                                            data-target="#add-customer" title="Add Customer">
                                            <i class="tio-user-add"></i> Add new customer
                                        </button>
                                    </div>
                                    <div class="col-lg-4">
                                        <select onchange="customer_change(this.value);" id='customer'
                                            name="customer_id" data-placeholder="Walk In Customer"
                                            class="form-control js-select2-custom mx-1">
                                            <option value="0">{{ translate('walking_customer') }}</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4 text-right">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <button data-toggle="modal" data-target="#short-cut-keys"
                                                class="btn btn-warning rounded mx-2" type="button"
                                                title="Keyboard shortcuts">
                                                <i class="tio-keyboard text-white"></i>
                                            </button>
                                            <a href="{{ route('dashboard') }}"
                                                class="btn btn-primary rounded mx-2 mr-4" type="button"
                                                title="Back to Dashboard">
                                                <i class="tio-imac"></i>
                                            </a>
                                            <div class="dropdown user-profile-button">
                                                <a href="javascript:" class="avatar avatar-circle"
                                                    data-toggle="dropdown">
                                                    <img class="avatar-img"
                                                        onerror="this.src='{{ asset('pos/img/160x160/img1.jpg') }}'"
                                                        src="https://fgcucdn.fgcu.edu/_resources/images/faculty-staff-male-avatar-200x200.jpg"
                                                        alt="Image">
                                                    <span
                                                        class="avatar-status avatar-sm-status avatar-status-success"></span>
                                                </a>
                                                <div class="dropdown-menu"
                                                    style="transform: translate3d(-124px, 50px, 5px);">
                                                    <a class="dropdown-item" href="javascript:">
                                                        <i class="tio-user"></i>{{ auth()->user()->name }}
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('purchase.create') }}">
                                                        <i class="tio-shopping-basket"></i> Purchase
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('invoice.reports') }}">
                                                        <i class="tio-albums"></i> Reports
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('logout') }}">
                                                        <i class="tio-sign-out"></i> Logout
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='w-100' id="cart">
                                    @include('pos._cart')
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <button class="action-bar-switcher" onclick="bottomActionBar()">
            <i class="tio-chevron-down"></i>
            <i class="tio-chevron-up" style="display: none"></i>
        </button>
        <div class="action-bottom-bar">
            <div class="container">
                <div class="row align-items-center py-2">
                    <div class="col-lg-12 col-xl-8">
                        <div class="calculation d-lg-flex">
                            <div class="cal-box d-lg-flex align-items-lg-center mr-4 text-nowrap">
                                <label class="cal-label font-weight-bold mr-2 mb-0 text-white">
                                    Net Total:
                                </label>
                                <span class="amount text-white" id="net_total_text">0.00</span>
                                <input type="hidden" id="n_total" name="n_total" value="0.00">
                                <input type="hidden" id="txfieldnum" value="2">
                            </div>
                            <div class="cal-box d-lg-flex align-items-lg-center mr-4">
                                <div class="d-inline-flex align-items-center text-nowrap">
                                    <label class="cal-label font-weight-bold mr-2 mb-0 text-white">Paid Amount:</label>
                                    <input type="number" class="form-control form-control-sm valid_number"
                                        placeholder="0.00" onkeyup="invoice_paidamount(this.value)"
                                        onchange="invoice_paidamount(this.value)" id="paidAmount" name="paid_amount">
                                </div>
                            </div>
                            <div class="cal-box d-lg-flex align-items-lg-center mr-4">
                                <label class="cal-label font-weight-bold mr-2 mb-0 text-white">Due Amount:</label><span
                                    class="amount text-white" id="due_text">0.00</span>
                                <input type="hidden" id="due_amount" name="due_amount" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xl-4 text-xl-right">
                        <div class="action-btns d-flex justify-content-center justify-content-xl-end mt-2 mt-xl-0">
                            <input type="button" id="full_paid_tab" class="btn btn-warning font-weight-600 mr-2"
                                value="Full Paid" onclick="fullPaid()">
                            <button type="button" onclick="placeOrder('Cash Payment')" id="placeOrderWithCash"
                                class="btn btn-success font-weight-600 mr-2">
                                Cash Payment
                            </button>
                            <button type="button" class="btn btn-info font-weight-600 mr-2" data-toggle="modal"
                                data-target="#paymentModal" aria-expanded="false">
                                MFS Payment
                            </button>
                            <input type="hidden" name="bank_id" value="3" id="bank_id">
                            <input type="hidden" name="payment_type" value="2" id="payment_type">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="quick-view" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" id="quick-view-modal">

                </div>
            </div>
        </div>
        <div class="modal fade" id="emrg-quick-view" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" id="emrg-quick-view-modal">

                </div>
            </div>
        </div>
        @php($order = \App\Models\Invoice::find(session('last_order')))
        @if ($order)
            @php(session(['last_order' => false]))
        @endif
        <div class="modal fade" id="add-customer" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('add_new_customer') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('pos.customer-store') }}" method="post" id="product_form">
                            @csrf
                            <div class="row pl-2">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('name') }} <span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name') }}" placeholder="{{ translate('name') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('phone') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="tel" name="phone" class="form-control"
                                            value="{{ old('phone') }}" placeholder="{{ translate('phone') }}"
                                            required>
                                        <small>Enter phone with country code (+000)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-2">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Email') }}</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email') }}" placeholder="{{ translate('Email') }}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('due') }}</label>
                                        <input type="number" step="0.01" name="due" class="form-control"
                                            value="{{ old('due') }}" placeholder="{{ translate('Due') }}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-12">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('address') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control"
                                            value="{{ old('address') }}" placeholder="{{ translate('address') }}"
                                            required>
                                    </div>
                                </div>
                            </div>


                            <hr>
                            <button type="submit" id="submit_new_customer"
                                class="btn btn-primary">{{ translate('submit') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="paymentModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content pvl14">
                    <div class="modal-header">
                        <h3 class="modal-title">{{ translate('common.payment') }}</h3>
                        <button id="payment_close" onclick="placeOrder()" type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="">Payment Method</label>
                            <div class="col-sm-8">
                                <select name="payment_method" id="payment_method" class="form-control" required>
                                    @foreach ($gateway as $method)
                                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 text-center">
                            <button onclick="placeOrder('mfs')" class="btn btn-success px-5" id="order_complete"
                                type="submit">{{ translate('common.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <audio id="audio" src="{{ asset('music/beep.mp3') }}"></audio>
    <script type="text/javascript">
        // Initialize the service worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('serviceworker.js', {
                scope: '/'
            }).then(function(registration) {
                // Registration was successful
                console.log('Registration was successful')
            }, function(err) {
                // registration failed :(
                console.log('Pharmacy: ServiceWorker registration failed: ', err);
            });
        }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <!-- JS Front -->
    <script src="{{ asset('pos/js/vendor.min.js') }}"></script>
    <script src="{{ asset('pos/js/theme.min.js') }}"></script>
    <script src="{{ asset('pos/js/sweet_alert.js') }}"></script>
    <script src="{{ asset('pos/js/toastr.js') }}"></script>
    <script src="{{ asset('pos/js/cart.js') }}"></script>
    <script src="{{ asset('slick/slick.js') }}" type="text/javascript" charset="utf-8"></script>


    {!! Toastr::message() !!}
    <script type="text/javascript">
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": true,
            "timeOut": "1000",
            "positionClass": "toast-top-right",
        }

        $(document).on('ready', function() {
            $(".category-slider").slick({
                arrow: true,
                slidesToShow: 7,
                slidesToScroll: 6,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 5,
                            slidesToScroll: 4
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 2
                        }
                    }
                ]
            });
        });
    </script>
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            @endforeach
        </script>
    @endif
    <script>
        function bottomActionBar() {
            if ($('.action-bottom-bar').is(':visible')) {
                $('.action-bar-switcher').animate({
                    bottom: 0
                }, 700);
                $('.tio-chevron-down').hide();
                $('.tio-chevron-up').show();
                $('.action-bottom-bar').animate({
                    opacity: 0,
                    height: 0
                }, 700, function() {
                    $(this).hide();
                });
            } else {
                $('.action-bar-switcher').animate({
                    bottom: 56
                }, 700);
                $('.tio-chevron-down').show();
                $('.tio-chevron-up').hide();
                $('.action-bottom-bar').show().animate({
                    opacity: 1,
                    height: 56
                }, 700);
            }
        }

        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this,
                    args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function() {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        $(document).on('ready', function() {
            // INITIALIZATION OF UNFOLD
            // =======================================================
            $('.js-hs-unfold-invoker').each(function() {
                var unfold = new HSUnfold($(this)).init();
            });
        });
    </script>
    <script>
        document.addEventListener("keydown", function(event) {
            "use strict";
            if (event.altKey && event.code === "KeyO") {
                $('#submit_order').click();
                event.preventDefault();
            }
            if (event.altKey && event.code === "KeyZ") {
                $('#payment_close').click();
                event.preventDefault();
            }
            if (event.altKey && event.code === "KeyS") {
                $('#order_complete').click();
                event.preventDefault();
            }
            if (event.altKey && event.code === "KeyC") {
                emptyCart();
                event.preventDefault();
            }
            if (event.altKey && event.code === "KeyA") {
                $('#add_new_customer').click();
                event.preventDefault();
            }
            if (event.altKey && event.code === "KeyN") {
                $('#submit_new_customer').click();
                event.preventDefault();
            }
            if (event.altKey && event.code === "KeyK") {
                $('#short-cut').click();
                event.preventDefault();
            }
            if (event.altKey && event.code === "KeyP") {
                $('#print_invoice').click();
                event.preventDefault();
            }
            if (event.altKey && event.code === "KeyQ") {
                $('#search').focus();
                $("#search-box").css("display", "none");
                event.preventDefault();
            }
            if (event.altKey && event.code === "KeyE") {
                $("#search-box").css("display", "none");
                $('#extra_discount').click();
                event.preventDefault();
            }
            if (event.altKey && event.code === "KeyD") {
                $("#search-box").css("display", "none");
                $('#coupon_discount').click();
                event.preventDefault();
            }
            if (event.altKey && event.code === "KeyB") {
                $('#invoice_close').click();
                event.preventDefault();
            }
            if (event.altKey && event.code === "KeyX") {
                clear_cart();
                event.preventDefault();
            }
            if (event.altKey && event.code === "KeyR") {
                new_order();
                event.preventDefault();
            }

        });
    </script>
    <!-- JS Plugins Init. -->
    <script>
        function medicineSearch(keyword) {
            let searchKeyword = keyword.trim()
            $.get({
                url: '{{ route('pos.search-products') }}',
                dataType: 'json',
                data: {
                    keyword: keyword.trim(),
                },
                success: function(data) {
                    $('#product-list').empty().html(data.result);
                },
            });
        }
        {{-- jQuery(".search-bar-input").on('keyup', function () { --}}
        {{--    //$('#search-box').removeClass('d-none'); --}}
        {{--    $(".search-card").removeClass('d-none').show(); --}}
        {{--    let name = $(".search-bar-input").val(); --}}
        {{--    //console.log(name); --}}
        {{--    if (name.length > 0) { --}}
        {{--        $('#search-box').removeClass('d-none').show(); --}}
        {{--        $.get({ --}}
        {{--            url: '{{route('pos.search-products')}}', --}}
        {{--            dataType: 'json', --}}
        {{--            data: { --}}
        {{--                name: name --}}
        {{--            }, --}}
        {{--            beforeSend: function () { --}}
        {{--                $('#loading').removeClass('d-none'); --}}
        {{--            }, --}}
        {{--            success: function (data) { --}}
        {{--                //console.log(data.count); --}}
        {{--                $('.search-result-box').empty().html(data.result); --}}
        {{--                if (data.count == 1) { --}}
        {{--                    $('.search-result-box').empty().hide(); --}}
        {{--                    $('#search').val(''); --}}
        {{--                    quickView(data.id); --}}
        {{--                } --}}

        {{--            }, --}}
        {{--            complete: function () { --}}
        {{--                $('#loading').addClass('d-none'); --}}
        {{--            }, --}}
        {{--        }); --}}
        {{--    } else { --}}
        {{--        $('.search-result-box').empty(); --}}
        {{--    } --}}
        {{-- }); --}}
    </script>
    <script>
        "use strict";

        function customer_change(val) {
            //let  cart_id = $('#cart_id').val();
            $.post({
                url: '{{ route('pos.remove-discount') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    //cart_id:cart_id,
                    user_id: val
                },
                beforeSend: function() {
                    $('#loading').removeClass('d-none');
                },
                success: function(data) {
                    console.log(data);

                    var output = '';
                    for (var i = 0; i < data.cart_nam.length; i++) {
                        output +=
                            `<option value="${data.cart_nam[i]}" ${data.current_user == data.cart_nam[i] ? 'selected' : ''}>${data.cart_nam[i]}</option>`;
                    }
                    $('#cart_id').html(output);
                    $('#current_customer').text(data.current_customer);
                    $('#cart').empty().html(data.view);
                },
                complete: function() {
                    $('#loading').addClass('d-none');
                }
            });
        }
    </script>
    <script>
        "use strict";

        function clear_cart() {
            let url = "{{ route('pos.clear-cart-ids') }}";
            document.location.href = url;
        }
    </script>
    <script>
        "use strict";

        function new_order() {
            let url = "{{ route('pos.new-cart-id') }}";
            document.location.href = url;
        }
    </script>
    <script>
        "use strict";

        function cart_change(val) {
            let cart_id = val;
            let url = "{{ route('pos.change-cart') }}" + '/?cart_id=' + val;
            document.location.href = url;
        }
    </script>
    <script>
        "use strict";

        function extra_discount() {
            //let  user_id = $('#customer').val();
            let discount = $('#dis_amount').val();
            let type = $('#type_ext_dis').val();
            //let  cart_id = $('#cart_id').val();
            if (discount > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('pos.discount') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        discount: discount,
                        type: type,
                        //cart_id:cart_id
                    },
                    beforeSend: function() {
                        $('#loading').removeClass('d-none');
                    },
                    success: function(data) {
                        // console.log(data);
                        if (data.extra_discount === 'success') {
                            toastr.success('{{ translate('extra_discount_added_successfully') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        } else if (data.extra_discount === 'empty') {
                            toastr.warning('{{ translate('your_cart_is_empty') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });

                        } else {
                            toastr.warning(
                                '{{ translate('this_discount_is_not_applied_for_this_amount') }}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                        }

                        $('.modal-backdrop').addClass('d-none');
                        $('#cart').empty().html(data.view);

                        $('#search').focus();
                    },
                    complete: function() {
                        $('.modal-backdrop').addClass('d-none');
                        $(".footer-offset").removeClass("modal-open");
                        $('#loading').addClass('d-none');
                    }
                });
            } else {
                toastr.warning('{{ translate('amount_can_not_be_negative_or_zero!') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        }
    </script>
    <script>
        "use strict";


        function evaluateTotal() {

            var total = $("#pvltotal").val();

            var pvlpaid = $("#pvlpaid").val();


            var duetotal = (total - pvlpaid);
            var due = duetotal > 0 ? duetotal.toFixed(2) : 0;
            $('#pvldue').val(due);

            // Returned Amount
            var returned = (pvlpaid - total);
            var returned_amount = returned > 0 ? returned : 0;
            if (pvlpaid > total && returned_amount > 0) {
                $("#returnedAmount").val(returned_amount.toFixed(2));
            } else {
                $("#returnedAmount").val(returned_amount.toFixed(2));
            }


        }

        function coupon_discount() {

            let coupon_code = $('#coupon_code').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('pos.coupon-discount') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    coupon_code: coupon_code,
                },
                beforeSend: function() {
                    $('#loading').removeClass('d-none');
                },
                success: function(data) {
                    console.log(data);
                    if (data.coupon === 'success') {
                        toastr.success('{{ translate('coupon_added_successfully') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    } else if (data.coupon === 'amount_low') {
                        toastr.warning('{{ translate('this_discount_is_not_applied_for_this_amount') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    } else if (data.coupon === 'cart_empty') {
                        toastr.warning('{{ translate('your_cart_is_empty') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    } else {
                        toastr.warning('{{ translate('coupon_is_invalid') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }

                    $('#cart').empty().html(data.view);

                    $('#search').focus();
                },
                complete: function() {
                    $('.modal-backdrop').addClass('d-none');
                    $(".footer-offset").removeClass("modal-open");
                    $('#loading').addClass('d-none');
                }
            });

        }
    </script>
    <script>
        $(document).on('ready', function() {
            @if ($order)
                $('#print-invoice').modal('show');
            @endif
        });

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        function set_category_filter(id) {
            var nurl = new URL('{!! url()->full() !!}');
            nurl.searchParams.set('category_id', id);
            location.href = nurl;
        }

        function set_vendor_filter(id) {
            var nurl = new URL('{!! url()->full() !!}');
            nurl.searchParams.set('vendor_id', id);
            location.href = nurl;
        }

        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            var keyword = $('#datatableSearch').val();
            var nurl = new URL('{!! url()->full() !!}');
            nurl.searchParams.set('keyword', keyword);
            location.href = nurl;
        });

        function thermalapi(url) {

            $.get({
                url: url,

                success: function(data) {
                    console.log(data);
                    toastr.success('{{ translate('Success') }}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
            });
        }

        function store_key(key, value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
            $.post({
                url: '{{ route('pos.store-keys') }}',
                data: {
                    key: key,
                    value: value,
                },
                success: function(data) {
                    toastr.success(key + ' ' + '{{ translate('selected') }}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
            });
        }

        function addon_quantity_input_toggle(e) {
            var cb = $(e.target);
            if (cb.is(":checked")) {
                cb.siblings('.addon-quantity-input').css({
                    'visibility': 'visible'
                });
            } else {
                cb.siblings('.addon-quantity-input').css({
                    'visibility': 'hidden'
                });
            }
        }

        function quickView(product_id) {
            $.ajax({
                url: '{{ route('pos.quick-view') }}',
                type: 'GET',
                data: {
                    product_id: product_id
                },
                dataType: 'json', // added data type
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    // $("#quick-view").removeClass('fade');
                    // $("#quick-view").addClass('show');

                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });

            {{-- $.get({ --}}
            {{--    url: '{{route('pos.quick-view')}}', --}}
            {{--    dataType: 'json', --}}
            {{--    data: { --}}
            {{--        product_id: product_id --}}
            {{--    }, --}}
            {{--    beforeSend: function () { --}}
            {{--        $('#loading').show(); --}}
            {{--    }, --}}
            {{--    success: function (data) { --}}
            {{--        console.log("success...") --}}
            {{--        $('#quick-view').modal('show'); --}}
            {{--        $('#quick-view-modal').empty().html(data.view); --}}
            {{--    }, --}}
            {{--    complete: function () { --}}
            {{--        $('#loading').hide(); --}}
            {{--    }, --}}
            {{-- }); --}}
        }

        function emrgQuickView(product_id) {
            $.ajax({
                url: '{{ route('pos.emrg-quick-view') }}',
                type: 'GET',
                data: {
                    product_id: product_id
                },
                dataType: 'json', // added data type
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    console.log("success...");
                    $('#emrg-quick-view').modal('show');
                    $('#emrg-quick-view-modal').empty().html(data.view);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }

        function checkAddToCartValidity() {
            var names = {};
            $('#add-to-cart-form input:radio').each(function() { // find unique names
                names[$(this).attr('name')] = true;
            });
            var count = 0;
            $.each(names, function() { // then count them
                count++;
            });
            if ($('input:radio:checked').length == count) {
                return true;
            }
            return false;
        }

        function cartQuantityInitialize() {
            $('.btn-number').click(function(e) {
                e.preventDefault();

                var fieldName = $(this).attr('data-field');
                var type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt(input.val());

                if (!isNaN(currentVal)) {
                    if (type == 'minus') {

                        if (currentVal > input.attr('min')) {
                            input.val(currentVal - 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('min')) {
                            $(this).attr('disabled', true);
                        }

                    } else if (type == 'plus') {

                        if (currentVal < input.attr('max')) {
                            input.val(currentVal + 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('max')) {
                            $(this).attr('disabled', true);
                        }

                    }
                } else {
                    input.val(0);
                }
            });

            $('.input-number').focusin(function() {
                $(this).data('oldValue', $(this).val());
            });

            $('.input-number').change(function() {

                minValue = parseInt($(this).attr('min'));
                maxValue = parseInt($(this).attr('max'));
                valueCurrent = parseInt($(this).val());

                var name = $(this).attr('name');
                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cart',
                        text: 'Sorry, the minimum value was reached'
                    });
                    $(this).val($(this).data('oldValue'));
                }
                if (valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cart',
                        text: 'Sorry, stock limit exceeded.'
                    });
                    $(this).val($(this).data('oldValue'));
                }
            });
            $(".input-number").keydown(function(e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }

        function getVariantPrice() {
            if ($('#add-to-cart-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{ route('pos.variant_price') }}',
                    data: $('#add-to-cart-form').serializeArray(),
                    success: function(data) {

                        $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                        $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                        $('#set-discount-amount').html(data.discount);
                    }
                });
            }
        }

        function addToCart(form_id = 'add-to-cart-form') {
            if (checkAddToCartValidity()) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('pos.add-to-cart') }}',
                    data: $('#' + form_id).serializeArray(),
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    success: function(data) {
                        if (data.data == 1) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Cart',
                                text: "Product already added in cart"
                            });
                            return false;
                        } else if (data.data == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Cart',
                                text: 'Sorry, product is out of stock.'
                            });
                            return false;
                        }
                        $('.call-when-done').click();
                        toastr.success('Item has been added in your cart!', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        $('#cart').empty().html(data.view);
                        //updateCart();
                        $('.search-result-box').empty().hide();
                        $('#search').val('');
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            } else {
                Swal.fire({
                    type: 'info',
                    title: 'Cart',
                    text: 'Please choose all the options'
                });
            }
        }

        function addToBatch(form_id = 'add-to-batch-form') {
            if (checkAddToCartValidity()) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('pos.add-to-batch') }}',
                    data: $('#' + form_id).serializeArray(),
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    success: function(data) {
                        quickView(data.product_id)
                        if (data.data == 1) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Batch',
                                text: "Batch has been added!"
                            });
                            return false;
                        } else if (data.data == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Cart',
                                text: 'Sorry, product is out of stock.'
                            });
                            return false;
                        }
                        $('.call-when-done').click();

                        toastr.success('Item has been added in your cart!', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        $('#cart').empty().html(data.view);
                        //updateCart();
                        $('.search-result-box').empty().hide();
                        $('#search').val('');
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            } else {
                Swal.fire({
                    type: 'info',
                    title: 'Cart',
                    text: 'Please choose all the options'
                });
            }
        }

        function removeFromCart(key) {
            //console.log(key);
            $.post('{{ route('pos.remove-from-cart') }}', {
                _token: '{{ csrf_token() }}',
                key: key
            }, function(data) {

                $('#cart').empty().html(data.view);
                if (data.errors) {
                    for (var i = 0; i < data.errors.length; i++) {
                        toastr.error(data.errors[i].message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                } else {
                    //updateCart();

                    toastr.info('Item has been removed from cart', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }

        function emptyCart() {
            Swal.fire({
                title: '{{ translate('Are_you_sure?') }}',
                text: '{{ translate('You_want_to_remove_all_items_from_cart!!') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#161853',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.post('{{ route('pos.emptyCart') }}', {
                        _token: '{{ csrf_token() }}'
                    }, function(data) {
                        $('#cart').empty().html(data.view);
                        toastr.info('Item has been removed from cart', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    });
                }
            })
        }

        function updateCart() {
            $.post('<?php echo e(route('pos.cart_items')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>'
            }, function(data) {
                $('#cart').empty().html(data);
            });
        }

        $(function() {
            $(document).on('click', 'input[type=number]', function() {
                this.select();
            });
        });


        function updateQuantity(key, batch, qty, e) {

            if (qty !== "") {
                var element = $(e.target);
                var minValue = parseInt(element.attr('min'));
                // maxValue = parseInt(element.attr('max'));
                var valueCurrent = parseInt(element.val());

                //var key = element.data('key');

                $.post('{{ route('pos.updateQuantity') }}', {
                    _token: '{{ csrf_token() }}',
                    key: key,
                    batch: batch,
                    quantity: qty
                }, function(data) {

                    if (data.qty < 0) {
                        toastr.warning('{{ translate('product_quantity_is_not_enough!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                    if (data.upQty === 'zeroNegative') {
                        toastr.warning(
                            '{{ translate('Product_quantity_can_not_be_zero_or_less_than_zero_in_cart!') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                    }
                    if (data.qty_update == 1) {
                        toastr.success('{{ translate('Product_quantity_updated!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                    $('#cart').empty().html(data.view);
                });
            } else {
                var element = $(e.target);
                var minValue = parseInt(element.attr('min'));
                var valueCurrent = parseInt(element.val());

                $.post('{{ route('pos.updateQuantity') }}', {
                    _token: '{{ csrf_token() }}',
                    key: key,
                    quantity: minValue
                }, function(data) {

                    if (data.qty < 0) {
                        toastr.warning('{{ translate('product_quantity_is_not_enough!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                    if (data.upQty === 'zeroNegative') {
                        toastr.warning(
                            '{{ translate('Product_quantity_can_not_be_zero_or_less_than_zero_in_cart!') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                    }
                    if (data.qty_update == 1) {
                        toastr.success('{{ translate('Product_quantity_updated!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                    $('#cart').empty().html(data.view);
                });
            }

            // Allow: backspace, delete, tab, escape, enter and .
            if (e.type == 'keydown') {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            }

        };

        // INITIALIZATION OF SELECT2
        // =======================================================
        $('.js-select2-custom').each(function() {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });

        $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{ route('pos.customers') }}',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

        $('#order_place').submit(function(eventObj) {
            if ($('#customer').val()) {
                $(this).append('<input type="hidden" name="user_id" value="' + $('#customer').val() + '" /> ');
            }
            return true;
        });
    </script>

    <!-- IE Support -->
    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write(
            '<script src="{{ asset('pos') }}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
    </script>
</body>

</html>

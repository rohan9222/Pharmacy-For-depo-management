<div x-data="{ isTableData: true, isInvoiceData: false  }">
    <div x-show="isTableData" x-transition x-cloak>
        <div class="row">
            <div class="col">
                <div class="collapse multi-collapse show" id="multiCollapseExample2">
                    <div class="card card-body" style="position: unset;">
                        <div class="row mt-3">
                            <div class="row">
                                <div class="col">
                                    <h3 class="text-center">
                                        All Medicine Stock List with Invoice NO
                                    </h3>
                                </div>
                            </div>
                            <div class="row justify-content-end p-1">
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                    <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search" aria-label="Search By Name">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Invoice NO</th>
                                            <th>Invoice Date</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Due</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stock_invoices as $stock_invoice)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $stock_invoice->invoice_no }}</td>
                                                <td>{{ \Carbon\Carbon::parse($stock_invoice->invoice_date)->format('d M Y') }}</td>
                                                <td>{{ $stock_invoice->total }}</td>
                                                <td>{{ $stock_invoice->paid }}</td>
                                                <td>{{ $stock_invoice->due }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" wire:click="invoiceView({{ $stock_invoice->id }})" @click="isTableData = false, isInvoiceData = true">view invoice</button>
                                                    @can('return-medicine')
                                                        <button class="btn btn-sm btn-warning" wire:click="returnStockMedicine({{ $stock_invoice->id }})" @click="isTableData = false, isInvoiceData = true"><i class="bi bi-arrow-counterclockwise"></i></button>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                                {{ $stock_invoices->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="isInvoiceData" x-transition x-cloak>
        <button class="btn btn-sm btn-info mb-1" wire:click="invoiceView()" @click="isTableData = true, isInvoiceData = false">back</button>
        @if ($stock_invoice_data)
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="invoice-title">
                                    <h4 class="float-end font-size-15">Invoice #{{ $stock_invoice_data->invoice_no }}  <span class="badge bg-{{$stock_invoice_data->paid >= $stock_invoice_data->grand_total ? 'success' : 'danger'}} font-size-12 ms-2">{{$stock_invoice_data->paid >= $stock_invoice_data->grand_total ? 'Paid' : 'Unpaid'}}</span></h4>
                                    <div class="mb-4">
                                        <h2 class="mb-1 text-muted">{{url('/')}}</h2>
                                    </div>
                                    {{-- <div class="text-muted">
                                        <p class="mb-1">3184 Spruce Drive Pittsburgh, PA 15201</p>
                                        <p class="mb-1"><i class="uil uil-envelope-alt me-1"></i> xyz@987.com</p>
                                        <p><i class="uil uil-phone me-1"></i> 012-345-6789</p>
                                    </div> --}}
                                </div>

                                <hr class="my-4">

                                <div class="row">
                                    {{-- <div class="col-sm-6">
                                        <div class="text-muted">
                                            <h5 class="font-size-16 mb-3">Billed To:</h5>
                                            <h5 class="font-size-15 mb-2">Preston Miller</h5>
                                            <p class="mb-1">4068 Post Avenue Newfolden, MN 56738</p>
                                            <p class="mb-1">PrestonMiller@armyspy.com</p>
                                            <p>001-234-5678</p>
                                        </div>
                                    </div> --}}
                                    <!-- end col -->
                                    <div class="col-12 justify-end">
                                        <div class="text-muted text-sm-end">
                                            <div>
                                                <h5 class="font-size-15 mb-1">Invoice No: #{{$stock_invoice_data->invoice_no}}</h5>
                                            </div>
                                            <div class="mt-4">
                                                <h5 class="font-size-15 mb-1">Invoice Date:  {{ \Carbon\Carbon::parse($stock_invoice_data->invoice_date)->format('d M Y') }}</h5>
                                            </div>
                                            <div class="mt-4">
                                                <h5 class="font-size-15 mb-1">Create Time: {{ \Carbon\Carbon::parse($stock_invoice_data->created_at)->format('d M Y h:i A') }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                                <!-- end row -->

                                <div class="py-2">
                                    <h5 class="font-size-15">Order Summary</h5>

                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap table-centered mb-0">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>SN</th>
                                                    <th colspan="2">Medicine</th>
                                                    <th>Batch</th>
                                                    <th>Expiry Date</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th class="text-end">Total</th>
                                                </tr>
                                            </thead><!-- end thead -->
                                            <tbody>
                                                @foreach ($stock_invoice_data->stockLists as $index => $medicine_list)
                                                    <tr class="text-center">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td class="text-end">
                                                            <img src="{{ asset($medicine_list->medicine->image_url ?? 'img/medicine-logo.png') }}" alt="" width="50">
                                                        </td>
                                                        <td class="text-start">
                                                            <div>
                                                                <h5 class="text-truncate font-size-14 mb-1">{{ $medicine_list->medicine->name }}</h5>
                                                                <p class="text-muted mb-0">{{ $medicine_list->medicine->generic_name }}</p>
                                                                <p class="text-muted mb-0">{{ $medicine_list->medicine->category_name }}</p>
                                                            </div>
                                                        </td>
                                                        <td>{{ $medicine_list->batch_number }}</td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($medicine_list->expiry_date)->format('d M Y') }}
                                                        </td>
                                                        <td>{{ $medicine_list->quantity }}</td>
                                                        <td class="text-center">{{ $medicine_list->buy_price }}</td>
                                                        <td class="text-end">{{ $medicine_list->quantity * $medicine_list->buy_price }}</td>
                                                    </tr>
                                                @endforeach
                                                <!-- end tr -->
                                                <tr>
                                                    <th scope="row" colspan="7" class="text-end">Sub Total</th>
                                                    <td class="text-end">{{ $stock_invoice_data->sub_total }}৳</td>
                                                </tr>
                                                <!-- end tr -->
                                                <tr>
                                                    <th scope="row" colspan="7" class="border-0 text-end">
                                                        Discount :</th>
                                                    <td class="border-0 text-end">- {{ $stock_invoice_data->discount }}৳</td>
                                                </tr>
                                                <!-- end tr -->
                                                <tr>
                                                    <th scope="row" colspan="7" class="border-0 text-end">
                                                        Grand Total</th>
                                                    <td class="border-0 text-end">{{ $stock_invoice_data->total }}৳</td>
                                                </tr>
                                                <!-- end tr -->
                                                <tr>
                                                    <th scope="row" colspan="7" class="border-0 text-end">Paid</th>
                                                    <td class="border-0 text-end"><h5 class="m-0 fw-semibold">{{ $stock_invoice_data->paid }}৳</h5></td>
                                                </tr>
                                                <!-- end tr -->
                                                <tr>
                                                    <th scope="row" colspan="7" class="border-0 text-end">Due</th>
                                                    <td class="border-0 text-end"><h5 class="m-0 fw-semibold">{{ $stock_invoice_data->due }}৳</h5></td>
                                                </tr>
                                                <!-- end tr -->
                                            </tbody><!-- end tbody -->
                                        </table><!-- end table -->
                                    </div><!-- end table responsive -->

                                    
                                    <div class="table-responsive px-5 mx-5">
                                        <table class="table align-middle table-nowrap table-centered mb-0">
                                            <thead>
                                                <tr class="text-center table-bordered">
                                                    <th colspan="6"><h4>Return Medicines</h4></th>
                                                </tr>
                                                <tr class="text-center">
                                                    <th>SN</th>
                                                    <th colspan="2">Medicine</th>
                                                    <th>Batch</th>
                                                    <th>Quantity</th>
                                                    <th class="text-end">Price Value</th>
                                                </tr>
                                            </thead><!-- end thead -->
                                            <tbody>
                                                @foreach ($stock_invoice_data->stockLists as $index => $medicine_list)
                                                    <tr class="text-center">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td class="text-end">
                                                            <img src="{{ asset($medicine_list->medicine->image_url ?? 'img/medicine-logo.png') }}" alt="" width="50">
                                                        </td>
                                                        <td class="text-start">
                                                            <div>
                                                                <h5 class="text-truncate font-size-14 mb-1">{{ $medicine_list->medicine->name }}</h5>
                                                                <p class="text-muted mb-0">{{ $medicine_list->medicine->generic_name }}</p>
                                                                <p class="text-muted mb-0">{{ $medicine_list->medicine->category_name }}</p>
                                                            </div>
                                                        </td>
                                                        <td>{{ $medicine_list->batch_number }}</td>
                                                        <td>{{ $medicine_list->stockReturnList->sum('quantity') }}</td>
                                                        <td class="text-end">{{ $medicine_list->stockReturnList->sum('total') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody><!-- end tbody -->
                                        </table><!-- end table -->
                                    </div><!-- end table responsive -->
                                    <div class="d-print-none mt-4">
                                        <div class="float-end">
                                            {{-- <a href="javascript:window.print()" class="btn btn-success me-1"><i class="bi bi-printer"></i></a> --}}
                                            {{-- <a href="#" class="btn btn-primary w-md">Send</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div>
            </div>
        @elseif ($return_invoice_data)
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="invoice-title">
                                    <h2>Return Medicines</h2>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <form wire:submit.prevent="returnSubmit">
                                            <div class="row">
                                                <div class="col-sm-6 col-md-3">
                                                    <div class="mb-3">
                                                        <label for="return_date" class="form-label">Return Date</label>
                                                        <input type="date" class="form-control" wire:model="return_date">
                                                        @error('return_date') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-md-3">
                                                    <div class="mb-3">
                                                        <label for="return_medicine" class="form-label">Medicines</label>
                                                        <select name="return_medicine" id="return_medicine" class="form-select" wire:model="return_medicine">
                                                            <option value="">Select Medicine</option>
                                                            @foreach ($return_invoice_data->stockLists as $medicine_list)
                                                                <option value="{{ $medicine_list->id }}">
                                                                    {{ $medicine_list->medicine->name }} ({{ $medicine_list->quantity }}PC) - {{$site_settings->site_currency}} {{ $medicine_list->buy_price }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('return_medicine') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-md-3">
                                                    <div class="mb-3">
                                                        <label for="return_quantity" class="form-label">Return Quantity</label>
                                                        <input type="number" class="form-control" wire:model="return_quantity">
                                                        @error('return_quantity') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-md-3">
                                                    <div class="mb-3 mt-2">
                                                        <button type="submit" class="btn btn-primary btn-sm mt-4">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

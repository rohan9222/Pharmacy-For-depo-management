<div x-data="{ isTableData: true, isInvoiceData: false }">
    <div x-show="isTableData" x-transition x-cloak>
        <div class="row">
            <div class="col">
                <div class="" id="multiCollapseExample2">
                    <div class="card card-body" style="position: unset;">
                        <div class="row mt-3">
                            <div class="row justify-content-end">
                                <div class="col m-1 p-1">
                                    {{-- <h3 class="text-center">
                                        All Medicine Stock List with Invoice NO
                                    </h3> --}}
                                </div>
                                <div class="col-3">
                                    <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search" aria-label="Search">
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Invoice NO</th>
                                        <th>Return Date</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $invoice->invoiceData->invoice_no }}</td>
                                            <td>{{ \Carbon\Carbon::parse($invoice->return_date)->format('d M Y') }}</td>
                                            <td>{{ $invoice->quantity }}</td>
                                            <td>{{ $invoice->price }}</td>
                                            <td>{{ $invoice->total }}</td>
                                            <td>
                                                @can('invoice')
                                                    <button class="btn btn-sm btn-success" wire:click="invoiceView({{ $invoice->invoice_id }})" @click="isTableData = false, isInvoiceData = true">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $invoices->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="isInvoiceData" x-transition x-cloak>
        <button class="btn btn-sm btn-info" wire:click="invoiceView()" @click="isTableData = true, isInvoiceData = false, isReturnData = false">back</button>
        @if ($invoice_data)
        <div class="container">
            <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="invoice-title">
                                    <h4 class="float-end font-size-15">Invoice #{{ $invoice_data->invoice_no }} <span class="badge bg-{{$invoice_data->paid >= $invoice_data->grand_total ? 'success' : 'danger'}} font-size-12 ms-2">{{$invoice_data->paid >= $invoice_data->grand_total ? 'Paid' : 'Unpaid'}}</span></h4>
                                    <div class="mb-4">
                                        <h2 class="mb-1 text-muted">{{url('/')}}</h2>
                                    </div>
                                    <div class="text-muted">
                                        <p class="mb-1">{{$site_settings->site_name}}</p>
                                        <p class="mb-1">{{$site_settings->site_address}}</p>
                                        <p class="mb-1"><i class="uil uil-envelope-alt me-1"></i> {{$site_settings->site_email}}</p>
                                        <p><i class="uil uil-phone me-1"></i> {{$site_settings->site_phone}}</p>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="text-muted">
                                            <h5 class="font-size-16 mb-3">Billed To:</h5>
                                            <h5 class="font-size-15 mb-2">{{$invoice_data->customer->name}}</h5>
                                            <p class="mb-1">{{$invoice_data->customer->address}}</p>
                                            <p class="mb-1">{{$invoice_data->customer->email}}</p>
                                            <p>{{$invoice_data->customer->phone}}</p>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                    <div class="col-sm-6">
                                        <div class="text-muted text-sm-end">
                                            <div>
                                                <h5 class="font-size-15 mb-1">Invoice No: #{{$invoice_data->invoice_no}}</h5>
                                            </div>
                                            <div class="mt-4">
                                                <h5 class="font-size-15 mb-1">Invoice Date:  {{ \Carbon\Carbon::parse($invoice_data->invoice_date)->format('d M Y') }}</h5>
                                            </div>
                                            <div class="mt-4">
                                                <h5 class="font-size-15 mb-1">Create Time: {{ \Carbon\Carbon::parse($invoice_data->created_at)->format('d M Y h:i A') }}</h5>
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
                                                    <th>Pack Size</th>
                                                    <th>Quantity</th>
                                                    <th>MRP/Selling Price</th>
                                                    <th class="text-end">Total</th>
                                                </tr>
                                            </thead><!-- end thead -->
                                            <tbody>
                                                @foreach ($invoice_data->salesMedicines as $index => $medicine_list)

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
                                                        <td>
                                                            {{ $medicine_list->medicine->pack_size }}
                                                        </td>
                                                        <td>{{ $medicine_list->initial_quantity }}</td>
                                                        <td class="text-center">{{ $medicine_list->price }}</td>
                                                        <td class="text-end">{{ $medicine_list->initial_quantity * $medicine_list->price }}</td>
                                                    </tr>
                                                @endforeach
                                                <!-- end tr -->
                                                <tr>
                                                    <th scope="row" colspan="6" class="text-end">Sub Total</th>
                                                    <td class="text-end">{{ $invoice_data->sub_total }}৳</td>
                                                </tr>
                                                <!-- end tr -->
                                                <tr>
                                                    <th scope="row" colspan="6" class="border-0 text-end">
                                                        Discount :</th>
                                                    <td class="border-0 text-end">- {{ $invoice_data->discount }}৳</td>
                                                </tr>
                                                <!-- end tr -->
                                                <tr>
                                                    <th scope="row" colspan="6" class="border-0 text-end">
                                                        Grand Total</th>
                                                    <td class="border-0 text-end">{{ $invoice_data->grand_total }}৳</td>
                                                </tr>
                                                <!-- end tr -->
                                                <tr>
                                                    <th scope="row" colspan="6" class="border-0 text-end">Paid</th>
                                                    <td class="border-0 text-end"><h5 class="m-0 fw-semibold">{{ $invoice_data->paid }}৳</h5></td>
                                                </tr>
                                                <!-- end tr -->
                                                <tr>
                                                    <th scope="row" colspan="6" class="border-0 text-end">Due</th>
                                                    <td class="border-0 text-end"><h5 class="m-0 fw-semibold">{{ $invoice_data->due }}৳</h5></td>
                                                </tr>
                                                <!-- end tr -->
                                            </tbody><!-- end tbody -->
                                        </table><!-- end table -->
                                    </div><!-- end table responsive -->

                                    <div class="row">
                                        <h3 class="text-center">Return Medicine</h3>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Medicine Name</th>
                                                    <th>Return Date</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoice_data->salesReturnMedicines as $salesReturnMedicine)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $salesReturnMedicine->medicine->name }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($salesReturnMedicine->return_date)->format('d M Y') }}</td>
                                                        <td>{{ $salesReturnMedicine->quantity }}</td>
                                                        <td>{{ $salesReturnMedicine->price }}</td>
                                                        <td>{{ $salesReturnMedicine->total }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-print-none mt-4">
                                        <div class="float-end">
                                            <a href="" class="btn btn-success me-1"><i class="bi bi-printer"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div>
            </div>
        @endif
    </div>
</div>

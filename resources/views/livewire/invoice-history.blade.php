<div x-data="{ isTableData: true, isInvoiceData: false, isReturnData: false  }">
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
                                        <th>Invoice Date</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Customer Name</th>
                                        <th>Delivery Status</th>
                                        <th>Delivered By</th>
                                        <th>Delivered Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $invoice->invoice_no }}</td>
                                            <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</td>
                                            <td>{{ $invoice->grand_total }}</td>
                                            <td>{{ $invoice->paid }}</td>
                                            <td>{{ $invoice->due }}</td>
                                            <td>{{ $invoice->customer->name }}</td>
                                            <td>
                                                @if($invoice->delivery_status == 'pending')
                                                    <span class="badge text-bg-warning">Pending</span>
                                                @elseif($invoice->delivery_status == 'delivered')
                                                    <span class="badge text-bg-success">Delivered</span>
                                                @elseif($invoice->delivery_status == 'shipped')
                                                    <span class="badge text-bg-primary">Shipped</span>
                                                @elseif($invoice->delivery_status == 'cancelled')
                                                    <span class="badge text-bg-danger">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>{{ $invoice->deliveredBy->name ?? 'Not Assigned' }}</td>
                                            <td>{{ $invoice->delivered_date ? \Carbon\Carbon::parse($invoice->delivered_date)->format('d M Y') : 'N/A' }}</td>
                                            <td>
                                                @can('invoice')
                                                    @if($invoice->delivery_status == 'pending')
                                                        <button class="btn btn-sm btn-info" wire:click="invoiceEdit({{ $invoice->id }})" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                    @endif
                                                    <button class="btn btn-sm btn-success" wire:click="invoiceView({{ $invoice->id }})" @click="isTableData = false, isInvoiceData = true, isReturnData = false">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                @endcan
                                                @can('return-medicine')
                                                    <button class="btn btn-sm btn-warning" wire:click="returnMedicine({{ $invoice->id }})" @click="isTableData = false, isInvoiceData = false, isReturnData = true">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $invoices->links() }}

                            @if ($invoice_discount && $spl_discount !== '')
                                <div class="modal fade show d-block" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form wire:submit.prevent="invoiceUpdate({{ $invoice_discount->id ?? '' }})">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5">Update Invoice Discount</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                                        wire:click="$set('spl_discount', '')"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="invoice_no" class="form-label">Invoice NO</label>
                                                        <input type="text" class="form-control" id="invoice_no" disabled
                                                            value="{{ $invoice_discount->invoice_no ?? '' }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="spl_discount" class="form-label">Invoice Special Discount (%)</label>
                                                        <input type="number" class="form-control" id="spl_discount" wire:model.defer="spl_discount">
                                                        @error('spl_discount')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" wire:click="$set('spl_discount', '')">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-backdrop fade show"></div>
                            @endif
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
                                    <div class="d-print-none mt-4">
                                        <div class="float-end">
                                            <a href="{{ route('invoice.pdf', $invoice->invoice_no) }}" target="_blank" class="btn btn-success me-1"><i class="bi bi-printer"></i></a>
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

    <div x-show="isReturnData" x-transition x-cloak>
        <button class="btn btn-sm btn-info" wire:click="invoiceView()" @click="isTableData = true, isInvoiceData = false, isReturnData = false">back</button>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="invoice-title">
                                <h2>Return Medicines</h2>
                            </div>
                            <hr>
                            @if ($invoice_data)
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
                                                            @foreach ($invoice_data->salesMedicines as $medicine_list)
                                                                <option value="{{ $medicine_list->id }}">
                                                                    {{ $medicine_list->medicine->name }} ({{ $medicine_list->quantity }}PC) - {{$site_settings->site_currency}} {{ $medicine_list->price }}
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

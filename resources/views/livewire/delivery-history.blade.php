<div>
     <!-- Header -->
     <x-slot name="header">
        {{ __('Customer List') }}
    </x-slot>

    <div class="row g-1">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Delivery History</h4>
                </div>
                <div class="card-body">
                    <div class="row justify-content-end">
                        <div class="col-3">
                            <input id="search" class="form-control" type="search" wire:model.live="delivered_search" placeholder="Search" aria-label="Search">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Summary ID</th>
                                    <th>Invoice List</th>
                                    <th>Delivery Date</th>
                                    <th>Delivery Man</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($delivered_invoices->count() > 0)
                                    @foreach ($delivered_invoices as $index => $delivered_invoice)
                                        @php
                                            $firstInvoice = $delivered_invoice->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td> {{-- Display sequential row number --}}
                                            <td>{{ $firstInvoice->summary_id }}</td> {{-- Display actual summary_id --}}
                                            <td>
                                                @foreach ($delivered_invoice as $invoice)
                                                    {{ $invoice->invoice_no }}{{ !$loop->last ? ',' : '' }}
                                                @endforeach
                                            </td>
                                            <td>{{ $firstInvoice->delivery_date ? \Carbon\Carbon::parse($firstInvoice->delivery_date)->format('d M Y h:i A') : 'N/A' }}</td>
                                            <td>{{ $firstInvoice->deliveredBy->name ?? 'N/A' }}</td>
                                            <td>
                                                @can('view-delivery-history')
                                                    <a href="{{ route('summary.pdf', $firstInvoice->summary_id) }}" target="_blank"
                                                        title="View Summary"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-danger">No Delivered Invoice Found!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $delivered_invoices->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Pending List</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- <div class="col">
                            <select class="form-control form-control-sm" wire:change="updateInvoiceList('manager')" wire:model="manager_id">
                                <option value="">Select Manager</option>
                                @foreach ($managers ?? [] as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-control form-control-sm" wire:change="updateInvoiceList('sales_manager')" wire:model="sales_manager_id">
                                <option value="">Select Sales Manager</option>
                                @foreach ($sales_managers ?? [] as $sales_manager)
                                    <option value="{{ $sales_manager->id }}">{{ $sales_manager->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="col">
                            <select class="form-control form-control-sm" wire:change="updateInvoiceList('field_officer')" wire:model="field_officer_id">
                                <option value="">Select Field Officer</option>
                                @foreach ($field_officers ?? [] as $field_officer)
                                    <option value="{{ $field_officer->id }}">{{ $field_officer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-control form-control-sm" wire:change="updateInvoiceList()"  wire:model="customer_id">
                                <option value="">Select Customer</option>
                                @foreach ($customers ?? [] as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <form wire:submit.prevent="deliverInvoiceList()">
                        <div class="row mt-2">
                            <div class="col-7">
                                <div class="border border-rounded p-2">
                                    @if ($invoices === null)
                                        <div class="text-danger text-center">!!! Select Field Officer First !!!</div>
                                    @elseif ($invoices->isEmpty())
                                        <div class="text-danger text-center">!!! No Invoice Found !!!</div>
                                    @else
                                        @foreach ($invoices as $invoice)
                                            <input type="checkbox" wire:model="selected_invoices" value="{{ $invoice->id }}" class="btn-check" id="invoice_{{ $invoice->id }}" autocomplete="off">
                                            <label class="btn btn-sm btn-outline-info" for="invoice_{{ $invoice->id }}">{{ $invoice->invoice_no }}</label>
                                        @endforeach
                                    @endif
                                </div>
                                @error('selected_invoices')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-4">
                                <select class="form-control form-control-sm" wire:model="delivered_by">
                                    <option value="">Delivery Man</option>
                                    @foreach ($delivery_man_lists ?? [] as $delivery_man)
                                        <option value="{{ $delivery_man->id }}">{{ $delivery_man->name }}</option>
                                    @endforeach
                                </select>
                                @error('delivered_by')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-1">
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-truck"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


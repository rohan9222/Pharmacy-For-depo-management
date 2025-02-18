<div x-data="{ isTableData: true, isInvoiceData: false, isReturnData: false  }">

    <div x-show="isTableData" x-transition x-cloak>
        <div class="row">
            <div class="col">
                <div class="" id="multiCollapseExample2">
                    <div class="card card-body" style="position: unset;">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <h3 class="text-center">Invoice History</h3>
                            </div>
                            <div class="col-12">
                                <livewire:user-data-manage />
                            </div>
                        </div>
                        <div class="row mt-3" wire:ignore>
                            <table class="table" id="invoiceTable">
                                <thead>
                                    <tr>
                                        <th></th>
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
                                <tbody class="text-center">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal -->
    <div wire:ignore.self class="modal fade" id="duePaymentModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="duePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <form wire:submit.prevent="payDue">
                    <div class="modal-header">
                        <h5 class="modal-title" id="duePaymentModalLabel">Due Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if($selectedInvoice)
                            <input type="hidden" wire:model="selectedInvoice.id">
                            <div class="form-group">
                                <label class="form-label fw-bold">Due Amount</label>
                                <input type="text" class="form-control"
                                    value="{{ $site_settings->site_currency }}{{ $selectedInvoice->due }}"
                                    readonly>
                            </div>
                            <div class="form-group mt-2">
                                <label class="form-label fw-bold">Amount</label>
                                <input type="number" wire:model="amount" class="form-control" required>
                                @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @endif
                        @if($partialPayment)
                            <div class="form-group">
                                <label class="form-label fw-bold">Due Amount</label>
                                <input type="text" class="form-control"
                                    value="{{ $site_settings->site_currency }}{{ $customerData->total_due}}"
                                    readonly>
                            </div>
                            <div class="form-group mt-2">
                                <label class="form-label fw-bold">Amount</label>
                                <input type="number" wire:model="amount" class="form-control" required>
                                @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm">Pay Now</button>
                        <button type="button" class="btn btn-danger btn-sm closeModal" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="invoiceEditModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="invoiceEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <form wire:submit.prevent="invoiceUpdate({{ $invoice_discount->id ?? '' }})">
                    <div class="modal-header">
                        <h5 class="modal-title" id="duePaymentModalLabel">Update Invoice Discount</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="$set('spl_discount', '')"></button>
                    </div>
                    @if ($invoice_discount && $spl_discount !== '')
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
                    @endif
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                        <button type="button" class="btn btn-danger btn-sm closeModal" data-bs-dismiss="modal" wire:click="$set('spl_discount', '')">Close</button>
                    </div>
                </form>
            </div>
        </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var table = $('#invoiceTable').DataTable({
            processing: true,
            serverSide: true,
            order: [[ 1, 'desc' ]],
            ajax: {
                url: "{{ route('sales-medicines-table') }}", // Ensure correct route
                data: function(d) {
                    d.manager_id = $('#manager_id').val();
                    d.zse_id = $('#zse_id').val();
                    d.tse_id = $('#tse_id').val();
                    d.customer_id = $('#customer_id').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'invoice_no', name: 'invoice_no' },
                { data: 'invoice_date', name: 'invoice_date' },
                { data: 'grand_total', name: 'grand_total' },
                { data: 'paid', name: 'paid' },
                { data: 'due', name: 'due' },
                { data: 'customer.name', name: 'customer.name' },
                { data: 'delivery_status', name: 'delivery_status' },
                { data: 'deliveredBy.name', name: 'deliveredBy.name' },
                { data: 'delivery_date', name: 'delivery_date' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            columnDefs: [
                {
                    targets: [3, 10],  // Invoice Date and Delivery Date
                    render: function(data, type, row) {
                        return moment(data).format('D-MMM-YYYY');  // Format date
                    }
                },
                {
                    orderable: false,
                    render: DataTable.render.select(),
                    targets: 0
                }
            ],
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                'colvis',
                'pdf',
                'print'
            ],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            pageLength: 10,
            select: {
                style: 'os',
                selector: 'td:first-child'
            }
        });

        // Reload table on click of the search button
        $('#search, .closeModal').click(function () {
            table.ajax.reload();
        });
    });
</script>
@endpush

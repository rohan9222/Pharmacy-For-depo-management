<div class="row">
    <div class="col">
        <div class="" id="multiCollapseExample2">
            <div class="card card-body" style="position: unset;">
                <div class="row">
                    <div class="col-12 mb-2">
                        <h3 class="text-center">Collection Report</h3>
                    </div>
                    <div class="col-12">
                        <livewire:user-data-manage />
                    </div>
                </div>
                <div class="row mt-3 table-responsive" wire:ignore>
                    <table class="table" id="invoiceTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>SN</th>
                                <th>Invoice NO</th>
                                <th>Invoice Date</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Customer Mobile</th>
                                <th>Total</th>
                                <th>Return</th>
                                <th>Paid List</th>
                                <th>Total Paid</th>
                                <th>Due</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" style="text-align:right">Total:</th>
                                <th colspan="4"></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
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
            // serverSide: true,
            order: [[ 1, 'desc' ]],
            ajax: {
                url: "{{ route('collection-list-table') }}", // Ensure correct route
                data: function(d) {
                    d.manager_id = $('#manager_id').val();
                    d.zse_id = $('#zse_id').val();
                    d.tse_id = $('#tse_id').val();
                    d.customer_id = $('#customer_id').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                },
            },

            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'invoice_no', name: 'invoice_no' },
                { data: 'invoice_date', name: 'invoice_date' },
                { data: 'customer.user_id', name: 'customer.user_id' },
                { data: 'customer.name', name: 'customer.name' },
                { data: 'customer.mobile', name: 'customer.mobile' },
                { data: 'grand_total', name: 'grand_total' },
                { data: 'returnAmount', name: 'returnAmount' },
                {
                    data: 'payment_history',
                    name: 'payment_history',
                    render: function(data) {
                        if (data) {
                            return data.split('<br>').map(entry => `<span class="bg-info">${entry}</span>`).join('<br>');
                        }
                        return 'No Payments';
                    }
                },
                { data: 'paid', name: 'paid' },
                { data: 'due', name: 'due' },
            ],
            columnDefs: [
                {
                    targets: [3],  // Invoice Date and Delivery Date
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
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            pageLength: 10,
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            footerCallback: function (row, data, start, end, display) {
                let api = this.api();

                // Function to remove formatting and convert to a number
                let intVal = function (i) {
                    return typeof i === 'string'
                        ? parseFloat(i.replace(/[\$,]/g, '')) || 0
                        : typeof i === 'number'
                        ? i
                        : 0;
                };

                // Function to calculate and update totals
                function calculateTotal(columnIndex) {
                    let total = api
                        .column(columnIndex, { search: 'applied' }) // Grand total (all pages, filtered)
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);

                    let pageTotal = api
                        .column(columnIndex, { page: 'current' }) // Page total (current visible data)
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);

                    // Update footer for this column
                    $(api.column(columnIndex).footer()).html(
                        `Page Total: ${pageTotal.toFixed(2)}<br>(Grand Total: ${total.toFixed(2)})`
                    );
                }

                // Apply total calculation for columns 7, 8, 9, 10
                [10,11].forEach(calculateTotal);
            }
        });

        // Reload table on click of the search button
        $('#search, .closeModal').click(function () {
            table.ajax.reload();
        });
    });
</script>
@endpush

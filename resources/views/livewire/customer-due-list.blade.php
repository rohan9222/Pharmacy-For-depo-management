<div class="row">
    <div class="col">
        <div class="" id="multiCollapseExample2">
            <div class="card card-body" style="position: unset;">
                <div class="row">
                    <div class="col-12 mb-2">
                        <h3 class="text-center">Due Customer History</h3>
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
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Customer Mobile</th>
                                <th>Invoice List</th>
                                <th>Total</th>
                                <th>Return</th>
                                <th>Paid</th>
                                <th>Due</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" style="text-align:right">Total:</th>
                                <th></th>
                                <th></th>
                                <th></th>
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
                url: "{{ route('customer-due-list-table') }}", // Ensure correct route
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
                { data: null, defaultContent: '', orderable: false, className: 'select-checkbox' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'user_id', name: 'user_id' },
                { data: 'name', name: 'name' },
                { data: 'mobile', name: 'mobile' },
                { data: 'invoice_no', name: 'invoice_no' },
                { data: 'invoice_total', name: 'invoice_total' },
                { data: 'invoice_return', name: 'invoice_return' },
                { data: 'invoice_paid', name: 'invoice_paid' },
                { data: 'invoice_due', name: 'invoice_due' },
            ],
            columnDefs: [
                // {
                //     targets: [3],  // Invoice Date and Delivery Date
                //     render: function(data, type, row) {
                //         return moment(data).format('D-MMM-YYYY');  // Format date
                //     }
                // },
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
                [6, 7, 8, 9].forEach(calculateTotal);
            }

        });

        // Reload table on click of the search button
        $('#search, .closeModal').click(function () {
            table.ajax.reload();
        });
    });
</script>
@endpush

<div class="row">
    <div class="col">
        <div class="" id="multiCollapseExample2">
            <div class="card card-body" style="position: unset;">
                <div class="row">
                    <div class="col-12 mb-2">
                        <h3 class="text-center">Due Invoice History</h3>
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
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Customer Mobile</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var table = $('#invoiceTable').DataTable({
            processing: true,
            serverSide: true,
            order: [[ 1, 'desc' ]],
            ajax: {
                url: "{{ route('due-list-table') }}", // Ensure correct route
                data: function(d) {
                    d.manager_id = $('#manager_id').val();
                    d.sales_manager_id = $('#sales_manager_id').val();
                    d.field_officer_id = $('#field_officer_id').val();
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
                { data: 'customer.user_id', name: 'customer.user_id' },
                { data: 'customer.name', name: 'customer.name' },
                { data: 'customer.mobile', name: 'customer.mobile' },
                { data: 'grand_total', name: 'grand_total' },
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

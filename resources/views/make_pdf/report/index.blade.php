<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="row g-2">
        <div class="col-6">
            <div class="card m-1 text-center">
                <h5 class="card-header text-start">Daily Sales Collection</h5>
                <form action="{{route('report.daily.sales.collection')}}" method="POST">
                    @method('POST')
                    @csrf
                    <div class="row p-3 g-1">
                        <div class="col">
                            <select class="form-select form-select-sm" name="manager_id" id="manager_id" wire:change="updateUserList()">
                                <option value=''>Select Manager</option>
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <div id="reportrange" class="form-control form-control-sm" wire:ignore>
                                <i class="bi bi-calendar"></i>&nbsp;
                                <span></span> <i class="bi bi-caret-down"></i>
                                <input type="hidden" name="start_date" id="start_date" />
                                <input type="hidden" name="end_date" id="end_date" />
                            </div>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-sm" id="search">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-6">
            <div class="card m-1 text-center">
                <h5 class="card-header text-start">Stock Statement Report</h5>
                <form action="{{route('report.stock.statement')}}" method="POST">
                    @method('POST')
                    @csrf
                    <div class="row g-3 p-3 align-items-center">
                        <div class="col">
                            <label for="reportdate" class="form-label">Select Date</label>
                        </div>
                        <div class="col">
                            <div id="reportdate" class="form-control form-control-sm">
                                <i class="bi bi-calendar"></i>&nbsp;
                                <span></span> <i class="bi bi-caret-down"></i>
                                <input type="hidden" name="reportdate" id="report_date" />
                            </div>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-sm" id="search">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/moment.min.js') }}"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                $(function() {
                    var start = moment().subtract(29, 'days');
                    var end = moment();
                    function cb(start, end) {
                        $('#reportrange span').html(start.format('D MMM YY') + ' - ' + end.format('D MMM YY'));
                        $('#start_date').val(start.format('YYYY-MM-DD'));
                        $('#end_date').val(end.format('YYYY-MM-DD'));
                    }
                    $('#reportrange').daterangepicker({
                        startDate: start,
                        endDate: end,
                        alwaysShowCalendars: true,
                        drops: "auto",
                        showWeekNumbers: true,
                        showISOWeekNumbers: true,
                        autoApply: true,
                        maxSpan: {
                            "days": 30
                        },
                        maxDate: end, 
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        }
                    }, cb);
                    cb(start, end);
                });

                $(function() {
                    var date = moment();
                    function cb(date) {
                        $('#reportdate span').html(date.format('D MMM YY'));
                        $('#report_date').val(date.format('YYYY-MM-DD'));
                    }

                    var reportPicker = $('#reportdate').daterangepicker({
                        singleDatePicker: true,
                        drops: "auto",
                        showWeekNumbers: true,
                        showISOWeekNumbers: true,
                        autoApply: true,
                        maxDate: date, // Block next months
                    }, cb);

                    cb( date);
                });

            });
        </script>
    @endpush

</x-app-layout>
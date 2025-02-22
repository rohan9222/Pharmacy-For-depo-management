<div class="row">
    <div class="col-12 m-1 p-1">
        <div class="row m-1 text-center">
            @if (auth()->user()->role == 'Super Admin')
                <div class="row p-1 mb-1 g-1">
                    <div class="col">
                        <select class="form-select form-select-sm" name="manager_id" id="manager_id" wire:change="updateUserList()" wire:model="manager_id">
                            <option value=''>Select Manager</option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select form-select-sm" name="zse_id" id="zse_id" wire:change="updateUserList()" wire:model="zse_id">
                            <option value=''>Select Zonal Sales Executive</option>
                            @foreach ($zses as $zse)
                                <option value="{{ $zse->id }}">{{ $zse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select form-select-sm" name="tse_id" id="tse_id" wire:change="updateUserList()" wire:model="tse_id" >
                            <option value=''>Select Territory Sales Executive</option>
                            @foreach ($tses ?? [] as $tse)
                                <option value="{{ $tse->id }}">{{ $tse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select form-select-sm" name="customer_id" id="customer_id" wire:change="updateUserList()" wire:model="customer_id" >
                            <option value=''>Select Customer</option>
                            @foreach ($customers ?? [] as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
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
            @elseif ($type == 'manager')
                <div class="row p-1 mb-1 g-1">
                    <div class="col">
                        <select class="form-select form-select-sm" name="zse_id" id="zse_id" wire:change="updateUserList()" wire:model="zse_id">
                            <option value=''>Select Zonal Sales Executive</option>
                            @foreach ($zses as $zse)
                                <option value="{{ $zse->id }}">{{ $zse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select form-select-sm" name="tse_id" id="tse_id" wire:change="updateUserList()" wire:model="tse_id" >
                            <option value=''>Select Territory Sales Executive</option>
                            @foreach ($tses ?? [] as $tse)
                                <option value="{{ $tse->id }}">{{ $tse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select form-select-sm" name="customer_id" id="customer_id" wire:change="updateUserList()" wire:model="customer_id" >
                            <option value=''>Select Customer</option>
                            @foreach ($customers ?? [] as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
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
            @elseif ($type == 'zse')
                <div class="row p-1 mb-1 g-1">
                    <div class="col">
                        <select class="form-select form-select-sm" name="tse_id" id="tse_id" wire:change="updateUserList()" wire:model="tse_id" >
                            <option value=''>Select Territory Sales Executive</option>
                            @foreach ($tses ?? [] as $tse)
                                <option value="{{ $tse->id }}">{{ $tse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select form-select-sm" name="customer_id" id="customer_id" wire:change="updateUserList()" wire:model="customer_id" >
                            <option value=''>Select Customer</option>
                            @foreach ($customers ?? [] as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
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
            @elseif ($type == 'tse')
                <div class="row p-1 mb-1 g-1">
                    <div class="col">
                        <select class="form-select form-select-sm" name="customer_id" id="customer_id" wire:change="updateUserList()" wire:model="customer_id" >
                            <option value=''>Select Customer</option>
                            @foreach ($customers ?? [] as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
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
            @endif
        </div>
    </div>
</div>
@push('styles')
@endpush
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
    });
</script>

@endpush


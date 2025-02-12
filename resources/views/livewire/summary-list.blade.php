<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 border-r20 mb-3">
                <div class="card-header bg-transparent border-0">
                    <h4 class="card-title">Summary List</h4>
                </div>
                <div class="card-body">
                    <div class="row m-1">
                        @if (auth()->user()->role == 'Super Admin')
                            <div class="row p-1 mb-1">
                                <div class="col-4">
                                    <select class="form-select form-select-sm" wire:change="updateUserList(); $wire.set('sales_manager_id', null)" wire:model="manager_id">
                                        <option value=''>Select Sales Manager</option>
                                        @foreach ($managers as $manager)
                                            <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-select form-select-sm" wire:change="updateUserList(); $wire.set('field_officer_id', null)" wire:model="sales_manager_id">
                                        <option value=''>Select Sales Manager</option>
                                        @foreach ($sales_managers as $sales_manager)
                                            <option value="{{ $sales_manager->id }}">{{ $sales_manager->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-select form-select-sm" wire:change="updateUserList()" wire:model="field_officer_id" >
                                        <option value=''>Select Field Officer</option>
                                        @foreach ($field_officers ?? [] as $field_officer)
                                            <option value="{{ $field_officer->id }}">{{ $field_officer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @elseif ($type == 'manager')
                            <div class="row p-1 mb-1">
                                <div class="col-4">
                                    <select class="form-select form-select-sm" wire:change="updateUserList(); $wire.set('field_officer_id', null)" wire:model="sales_manager_id">
                                        <option value=''>Select Sales Manager</option>
                                        @foreach ($sales_managers as $sales_manager)
                                            <option value="{{ $sales_manager->id }}">{{ $sales_manager->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-select form-select-sm" wire:change="updateUserList()" wire:model="field_officer_id" >
                                        <option value=''>Select Field Officer</option>
                                        @foreach ($field_officers ?? [] as $field_officer)
                                            <option value="{{ $field_officer->id }}">{{ $field_officer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @elseif ($type == 'sales_manager')
                            <div class="row p-1 mb-1">
                                <div class="col-4">
                                    <select class="form-select form-select-sm" wire:change="updateUserList()" wire:model="field_officer_id" >
                                        <option value=''>Select Field Officer</option>
                                        @foreach ($field_officers ?? [] as $field_officer)
                                            <option value="{{ $field_officer->id }}">{{ $field_officer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row justify-content-end mb-2">
                        <div class="col-3">
                            <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search" aria-label="Search By Name">
                        </div>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>User Name</th>
                                <th>User Role</th>
                                <th>Target</th>
                                <th>Target Achieved</th>
                                <th>Target Month</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admin_targets ?? [] as $admin_target)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $admin_target->userData->name }}</td>
                                    <td>{{ $admin_target->userData->role }}</td>
                                    <td>{{ $admin_target->sales_target }}</td>
                                    <td>{{ $admin_target->sales_target_achieved }}</td>
                                    <td>{{ $admin_target->target_month }} {{ $admin_target->target_year }}</td>
                                    <td>{!! $admin_target->sales_target_achieved >= $admin_target->sales_target ? '<span class="badge text-bg-success">Completed</span>' : '<span class="badge text-bg-warning">Not Completed</span>' !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div  x-data="{ isOpen: false, isUserProfile: false}">
    <!-- Header -->
    <x-slot name="header">
        {{ __('Admin User List') }}
    </x-slot>

    <div class="d-flex justify-content-center">
        <div class="col-10" x-show="!isUserProfile" x-transition>
            <div class="row">
                <div class="col-12">
                    @canany(['create-user', 'edit-user', 'delete-user'])
                        <a class="btn btn-success col-md mx-1" href="{{ route('users.create') }}">
                            <i class="fa-solid fa-user-gear"></i>Add User</a>
                    @endcanany
                </div>
            </div>
            <div class="row mt-3">
                <div class="row justify-content-end">
                    <div class="col-sm-6 col-lg-3">
                        <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search" aria-label="Search By Name">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>User ID</th>
                                <th>Person Name</th>
                                <th>Email Address</th>
                                <th>mobile</th>
                                <th>Address</th>
                                <th>Target</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admin_users as $admin_user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $admin_user->user_id }}</td>
                                    <td>{{ $admin_user->name }}</td>
                                    <td>{{ $admin_user->email }}</td>
                                    <td>{{ $admin_user->mobile }}</td>
                                    <td>{{ $admin_user->address }}</td>
                                    <td>{{ $admin_user->sales_target }}</td>
                                    <td>
                                        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Depo Incharge'))
                                            <button class="btn btn-sm btn-info" wire:click="edit({{ $admin_user->id }})" @click="isOpen = true"><i class="bi bi-pencil-square"></i></button>
                                        @endif
                                        <button class="btn btn-sm btn-primary" wire:click="view({{ $admin_user->id }})" @click="isUserProfile = true, isUserList = false" ><i class="bi bi-eye"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($target_edit && $sales_target !== '')
                    <div class="modal fade show d-block" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form wire:submit.prevent="targetUpdate({{ $target_edit->id ?? '' }})">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5">Update Monthly Sales Target</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                            wire:click="$set('sales_target', '')"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="user_id" class="form-label">Person Data</label>
                                            <input type="text" class="form-control" id="user_id" disabled
                                                value="{{ $target_edit->name ?? '' }} (User ID: {{ $target_edit->user_id ?? '' }}) ({{ $target_edit->role ?? '' }})">
                                        </div>
                                        <div class="mb-3">
                                            <label for="sales_target" class="form-label">Sales Target Amount</label>
                                            <input type="number" class="form-control" id="sales_target" wire:model="sales_target">
                                            @error('sales_target')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" wire:click="$set('sales_target', '')">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal-backdrop fade show"></div>
                @endif
            </div>

            <div class="pagination">
                {{ $admin_users->links() }}
            </div>
        </div>

        <div class="col-10" x-show="isUserProfile" x-transition x-cloak>
            <button class="btn btn-sm btn-info" @click="isUserProfile = false; $wire.set('zse_id', ''); $wire.set('tse_id', ''); $wire.set('customer_id', '');">back</button>
            @if ($adminUserData)
                <div class="row pt-2">
                    <div class="col-xl-4 col-lg-5 col-md-5">
                        <div class="card border-0 shadow">
                            <div class="card-body">
                                <div class="user-avatar-section">
                                    <div class="d-flex align-items-center flex-column">
                                        <img class="img-fluid rounded-circle mb-2" src="{{ asset('img/noimage.png') }}" onerror="this.src='{{ asset('img/noimage.png') }}'" height="100" width="100" alt="User avatar">
                                        <div class="user-info text-center">
                                            <h4>{{ $adminUserData->name }}</h4>
                                            <span class="d-inline-flex px-2 text-success-emphasis bg-success-subtle border border-success-subtle rounded-2">{{ $adminUserData->role }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-around my-2 pt-75">
                                    <div class="d-flex align-items-start me-2">
                                        <span class="d-inline-flex px-2 py-1 text-info-emphasis bg-info-subtle border border-info-subtle rounded-2 mt-1">
                                            <i class="bi bi-arrow-left-right"></i>
                                        </span>
                                        <div class="ms-75">
                                            <h5 class="mb-0">{{ $site_settings->site_currency }}{{ $adminUserData->total_sales}}</h5>
                                            <small>Total Sales</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <span class="d-inline-flex px-2 py-1 text-danger-emphasis bg-danger-subtle border border-danger-subtle rounded-2 mt-1">
                                            <i class="bi bi-exclamation-triangle"></i>
                                        </span>
                                        <div class="ms-75">
                                            <h5 class="mb-0">{{ $site_settings->site_currency }}{{ $adminUserData->total_return}}</h4>
                                            <small>Total Return</small>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="fw-bolder border-bottom mb-1">Details</h4>

                                <div class="info-container">
                                    <ul class="list-unstyled">
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Name:</span>
                                            <span>{{ $adminUserData->name }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">User ID:</span>
                                            <span>{{ $adminUserData->user_id }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Phone:</span>
                                            <span>{{ $adminUserData->mobile }}</span>
                                        </li>

                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Total Invoice:</span>
                                            <span>{{ $adminUserData->total_invoice }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Total Sales:</span>
                                            <span class="fw-bold">{{ $site_settings->site_currency }}{{ $adminUserData->total_sales }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Total Paid:</span>
                                            <span class="fw-bold">{{ $site_settings->site_currency }}{{ $adminUserData->total_paid }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Total Due:</span>
                                            <span>{{ $adminUserData->total_due }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Address:</span>
                                            <span>{{ $adminUserData->address }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8 col-lg-7 col-md-7">
                        <div class="row p-1 mb-1 g-1">
                        @if ($type == 'manager')
                            <div class="col-lg-3 col-md-6">
                                <select class="form-select form-select-sm" wire:change="view({{$adminUserData->id}})" wire:model="zse_id">
                                    <option value=''>Select Zonal Sales Executive</option>
                                    @foreach ($zses as $zse)
                                        <option value="{{ $zse->id }}">{{ $zse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <select class="form-select form-select-sm" wire:change="view({{$adminUserData->id}})" wire:model="tse_id" >
                                    <option value=''>Select Territory Sales Executive</option>
                                    @foreach ($tses ?? [] as $tse)
                                        <option value="{{ $tse->id }}">{{ $tse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <select class="form-select form-select-sm" wire:change="view({{$adminUserData->id}})" wire:model="customer_id">
                                    <option value=''>Select Customer</option>
                                    @foreach ($customers ?? [] as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @elseif ($type == 'zse')
                            <div class="col-4">
                                <select class="form-select form-select-sm" wire:change="view({{$adminUserData->id}})" wire:model="tse_id">
                                    <option value=''>Select Territory Sales Executive</option>
                                    @foreach ($tses as $tse)
                                        <option value="{{ $tse->id }}">{{ $tse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <select class="form-select form-select-sm" wire:change="view({{$adminUserData->id}})" wire:model="customer_id">
                                    <option value=''>Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @elseif ($type == 'tse')
                            <div class="col-4">
                                <select class="form-select form-select-sm" wire:change="view({{$adminUserData->id}})" wire:model="customer_id">
                                    <option value=''>Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                            <div class="col-lg-3 col-md-6">
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control form-control-sm" placeholder="Start Date" wire:model="start_date">
                                    <span class="input-group-text">To</span>
                                    <input type="date" class="form-control" placeholder="End Date" wire:model="end_date">
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>Invoice List</h4>
                            </div>
                            <div class="card-body border-bottom">
                                <div class="table-responsive">
                                    <table class="table datatable-project">
                                        <thead>
                                            <tr>
                                                <th>Invoice No</th>
                                                <th>Total Price</th>
                                                <th>Return</th>
                                                <th>Paid Amount</th>
                                                <th>Due amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoices as $invoice)
                                                @php
                                                    $sumReturnTotal = $invoice->salesReturnMedicines->sum('total');
                                                    $afterReturnDue = $invoice->grand_total - $sumReturnTotal;
                                                    $discount_data = json_decode($invoice->discount_data);

                                                    if ($discount_data != null && $discount_data->start_amount <= $afterReturnDue && $afterReturnDue <= $discount_data->end_amount) {
                                                        $afterReturnDue = $afterReturnDue - $invoice->paid;
                                                    } elseif ($discount_data != null && $discount_data->start_amount > $afterReturnDue) {
                                                        $afterReturnDue += ($invoice->dis_amount - $invoice->paid);
                                                    }else{
                                                        $afterReturnDue = $afterReturnDue - $invoice->paid;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $site_settings->site_invoice_prefix }}-{{ $invoice->invoice_no }}</td>
                                                    <td>{{ $site_settings->site_currency }}{{ $invoice->grand_total }}</td>
                                                    <td>{{ $site_settings->site_currency }}{{ $sumReturnTotal }}</td>
                                                    <td>{{ $site_settings->site_currency }}{{ $invoice->paid }}</td>
                                                    <td class="border-end"><b>{{$sumReturnTotal > ($invoice->grand_total) ? 0 : round($afterReturnDue)}}</b></td>
                                                    {{-- <td>{{ $site_settings->site_currency }}{{ $invoice->salesReturnMedicines->sum('total') > $invoice->due ? 0 : $invoice->due - $invoice->salesReturnMedicines->sum('total') }}</td> --}}
                                                    <td>
                                                        <a href="{{ route('invoice.pdf', $invoice->invoice_no) }}" target="_blank"
                                                            title="View Invoice"
                                                            class="btn btn-warning btn-sm">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>


<div  x-data="{ isOpen: false, isUserProfile: false}">
    <!-- Header -->
    <x-slot name="header">
        {{ __('Admin User List') }}
    </x-slot>

    <div class="d-flex justify-content-center">
        <div class="col-10" x-show="!isUserProfile" x-transition>
            <div class="row mt-3">
                <div class="row justify-content-end">
                    <div class="col-3">
                        <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search By Name" aria-label="Search By Name">
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>SN</th>
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
                                <td>{{ $admin_user->name }}</td>
                                <td>{{ $admin_user->email }}</td>
                                <td>{{ $admin_user->mobile }}</td>
                                <td>{{ $admin_user->address }}</td>
                                <td>{{ $admin_user->sales_target }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" wire:click="edit({{ $admin_user->id }})" @click="isOpen = true"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn btn-sm btn-primary" wire:click="view({{ $admin_user->id }})" @click="isUserProfile = true, isUserList = false" ><i class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $admin_users->links() }}
            </div>
        </div>

        <div class="col-10" x-show="isUserProfile" x-transition x-cloak>
            <button class="btn btn-sm btn-info" @click="isUserProfile = false">back</button>
            @if ($adminUserData)
                <div class="row pt-2">
                    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
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

                    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                        <div class="card">
                            <div class="card-header">
                                <h4>Customer Invoice</h4>
                            </div>
                            <div class="card-body border-bottom">
                                <div class="table-responsive">
                                    <table class="table datatable-project">
                                        <thead>
                                            <tr>
                                                <th>Invoice No</th>
                                                <th>Total Price</th>
                                                <th>Paid Amount</th>
                                                <th>Due amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoices as $invoice)
                                                <tr>
                                                    <td>{{ $site_settings->site_invoice_prefix }}-{{ $invoice->invoice_no }}</td>
                                                    <td>{{ $site_settings->site_currency }}{{ $invoice->grand_total }}</td>
                                                    <td>{{ $site_settings->site_currency }}{{ $invoice->paid }}</td>
                                                    <td>{{ $site_settings->site_currency }}{{ $invoice->due }}</td>
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

                                    <!-- Bootstrap Modal -->
                                    <div wire:ignore.self class="modal fade" id="duePaymentModal" tabindex="-1" aria-labelledby="duePaymentModalLabel" aria-hidden="true">
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
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary btn-sm" wire:click="view({{ $adminUserData->id }})">Pay Now</button>
                                                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

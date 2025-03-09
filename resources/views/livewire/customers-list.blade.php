<div x-data="{ isOpen: false, isCustomerProfile: false, isCustomerList: true }">
    <!-- Header -->
    <x-slot name="header">
        {{ __('Customer List') }}
    </x-slot>

    <div class="d-flex justify-content-center">
        <div class="col-10" x-show="!isCustomerProfile" x-transition>
            @if(auth()->user()->can('create-customer') || $customerId)
                <div class="row">
                    <div class="col">
                        <div class="p-1">
                            <!-- Toggle Button -->
                            <button
                                @click="isOpen = !isOpen; if (!isOpen) { $wire.set('name', ''); $wire.set('email', ''); $wire.set('mobile', ''); $wire.set('address', ''); $wire.set('balance', ''); $wire.set('customerId', ''); }"
                                class="btn btn-sm btn-primary"
                                type="button">
                                <span x-text="isOpen ? 'Hide This' : 'Add Customer'"></span>
                            </button>
                        </div>

                        <!-- Collapse Section -->
                        <div x-show="isOpen" x-transition x-cloak>
                            <div class="card card-body">
                                <form wire:submit.prevent="submit">
                                    <div class="row g-2">
                                        <div class="col-3">
                                            <input class="form-control" type="text" id="name" wire:model="name" placeholder="customer Name" aria-label="customer Name">
                                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="text" id="email" wire:model="email" placeholder="Email Address" aria-label="Email Address">
                                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="text" id="mobile" wire:model="mobile" placeholder="mobile" aria-label="mobile">
                                            @error('mobile') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="address" id="address" wire:model="address" placeholder="address" aria-label="address">
                                            @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        {{-- <div class="col-3">
                                            <input class="form-control" type="number" id="balance" wire:model="balance" placeholder="Balance" aria-label="Balance">
                                            @error('balance') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div> --}}
                                        <div class="col-3">
                                            <select name="tse_team" id="tse_team" class="form-control" wire:model="tse_team">
                                                <option value="">Select Territory Sales Executive</option>
                                                @foreach ($tses as $tse)
                                                    <option value="{{ $tse->id }}"
                                                        {{ isset($tse->fieldOfficer) && $tse->fieldOfficer->id == $tse->id
                                                            ? 'selected'
                                                            : ($tse->id == auth()->user()->id ? 'selected' : '') }}>
                                                        {{ $tse->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('tse_team') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <input type="text" class="form-control" id="route" wire:model="route" placeholder="Route" aria-label="Route">
                                            @error('route') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <select name="category" id="category" wire:model="category" class="form-control">
                                                <option value="">Select Category</option>
                                                <option value="Institution">Institution</option>
                                                <option value="General">General</option>
                                            </select>
                                            @error('category') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <button class="btn btn-primary mt-2" type="submit">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row mt-3">
                <div class="row justify-content-end">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search" aria-label="Search By Name">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Customer ID</th>
                                <th>customer Name</th>
                                <th>Email Address</th>
                                <th>mobile</th>
                                {{-- <th>Balance</th> --}}
                                <th>Address</th>
                                <th>Territory Sales Executive</th>
                                <th>Route</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $customer->user_id }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->mobile }}</td>
                                    {{-- <td>{{ $customer->balance }}</td> --}}
                                    <td>{{ $customer->address }}</td>
                                    <td>{{ $customer->fieldOfficer->name ?? 'N/A' }}</td>
                                    <td>{{ $customer->route ?? 'N/A' }}</td>
                                    <td>{{ $customer->category ?? 'N/A' }}</td>
                                    <td>
                                        @can('view-customer')
                                            <button class="btn btn-sm btn-primary" wire:click="view({{ $customer->id }})" @click="isCustomerProfile = true, isCustomerList = false" ><i class="bi bi-eye"></i></button>
                                        @endcan

                                        @can('edit-customer')
                                            <button class="btn btn-sm btn-info" wire:click="edit({{ $customer->id }})" @click="isOpen = true"><i class="bi bi-pencil-square"></i></button>
                                        @endcan
    
                                        @can('delete-customer')
                                            <button class="btn btn-sm btn-danger" wire:click="delete({{ $customer->id }})"><i class="bi bi-trash"></i></button>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pagination">
                {{ $customers->links() }}
            </div>
        </div>

        <div class="col-10" x-show="isCustomerProfile" x-transition x-cloak>
            <button class="btn btn-sm btn-info" @click="isCustomerProfile = false">back</button>
            @if ($customerData)
                <div class="row pt-2">
                    <div class="col-xl-4 col-lg-5 col-md-5">
                        <div class="card border-0 shadow">
                            <div class="card-body">
                                <div class="user-avatar-section">
                                    <div class="d-flex align-items-center flex-column">
                                        <img class="img-fluid rounded-circle mb-2" src="{{ asset('img/noimage.png') }}" onerror="this.src='{{ asset('img/noimage.png') }}'" height="100" width="100" alt="User avatar">
                                        <div class="user-info text-center">
                                            <h4>{{ $customerData->name }}</h4>
                                            <span class="d-inline-flex px-2 text-success-emphasis bg-success-subtle border border-success-subtle rounded-2">{{ $customerData->role }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-around my-2 pt-75">
                                    <div class="d-flex align-items-start me-2">
                                        <span class="d-inline-flex px-2 py-1 text-info-emphasis bg-info-subtle border border-info-subtle rounded-2 mt-1">
                                            <i class="bi bi-arrow-left-right"></i>
                                        </span>
                                        <div class="ms-75">
                                            <h5 class="mb-0">{{ $site_settings->site_currency }}{{ $customerData->total_buy}}</h5>
                                            <small>Total Buy</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <span class="d-inline-flex px-2 py-1 text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded-2 mt-1">
                                            <i class="bi bi-box-arrow-left"></i>
                                        </span>
                                        <div class="ms-75">
                                            <h5 class="mb-0">{{ $site_settings->site_currency }}{{ $customerData->total_return}}</h4>
                                            <small>Total Return</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <span class="d-inline-flex px-2 py-1 text-danger-emphasis bg-danger-subtle border border-danger-subtle rounded-2 mt-1">
                                            <i class="bi bi-exclamation-triangle"></i>
                                        </span>
                                        <div class="ms-75">
                                            <h5 class="mb-0">{{ $site_settings->site_currency }}{{ $customerData->total_due - $customerData->total_return}}</h4>
                                            <small>Total Due</small>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="fw-bolder border-bottom mb-1">Details</h4>

                                <div class="info-container">
                                    <ul class="list-unstyled">
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Name:</span>
                                            <span>{{ $customerData->name }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Customer ID:</span>
                                            <span>{{ $customerData->user_id }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Phone:</span>
                                            <span>{{ $customerData->mobile }}</span>
                                        </li>

                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Total Invoice:</span>
                                            <span>{{ $customerData->total_invoice }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Total Transaction:</span>
                                            <span>{{ $customerData->total_transaction }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Total Buy:</span>
                                            <span class="fw-bold">{{ $site_settings->site_currency }}{{ $customerData->total_buy }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Total Paid:</span>
                                            <span class="fw-bold">{{ $site_settings->site_currency }}{{ $customerData->total_paid }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Address:</span>
                                            <span>{{ $customerData->address }}</span>
                                        </li>
                                        <li class="mb-75 d-flex justify-content-end">
                                            @if ($customerData->total_due > 0 && auth()->user()->can('make-payment'))
                                                <button class="btn btn-info btn-sm" wire:click="partialPay({{ $customerData->id }})" data-bs-toggle="modal" data-bs-target="#duePaymentModal">Pay Now</button>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8 col-lg-7 col-md-7">
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
                                                <th>Return</th>
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
                                                    <td>{{ $site_settings->site_currency }}{{ $invoice->salesReturnMedicines->sum('total') }}</td>
                                                    <td>{{ $site_settings->site_currency }}{{ $invoice->paid }}</td>
                                                    <td>{{ $site_settings->site_currency }}{{ $invoice->due - $invoice->salesReturnMedicines->sum('total') }}</td>
                                                    <td>
                                                        @if ($invoice->due-$invoice->salesReturnMedicines->sum('total')  > 0 && auth()->user()->can('make-payment'))
                                                            <button wire:click="setInvoice({{ $invoice->id }}, {{ $customerData->id }})"
                                                                class="btn btn-primary btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#duePaymentModal">
                                                                <i class="bi bi-credit-card"></i>
                                                            </button>
                                                        @endif
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
                                                                    value="{{ $site_settings->site_currency }}{{ $selectedInvoice->due - $selectedInvoice->salesReturnMedicines->sum('total') }}"
                                                                    readonly>
                                                            </div>
                                                            <div class="form-group mt-2">
                                                                <label class="form-label fw-bold">Amount</label>
                                                                <input type="text" wire:model="amount" class="form-control" required>
                                                                @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                                                        @endif
                                                        @if($partialPayment)
                                                            <div class="form-group">
                                                                <label class="form-label fw-bold">Due Amount</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $site_settings->site_currency }}{{ $partialPayment}}"
                                                                    readonly>
                                                            </div>
                                                            <div class="form-group mt-2">
                                                                <label class="form-label fw-bold">Amount</label>
                                                                <input type="text" wire:model="amount" class="form-control" required>
                                                                @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary btn-sm">Pay Now</button>
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

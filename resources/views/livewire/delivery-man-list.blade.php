<div x-data="{ isOpen: false }">
    <!-- Header -->
    <x-slot name="header">
        {{ __('Delivery Man List') }}
    </x-slot>

    <div class="d-flex justify-content-center">
        <div class="col-8">
            @if(auth()->user()->can('create-delivery-man') || $customerId)
                <div class="row">
                    <div class="col">
                        <div class="p-1">
                            <!-- Toggle Button -->
                            <button
                                @click="isOpen = !isOpen; if (!isOpen) { $wire.set('name', ''); $wire.set('email', ''); $wire.set('mobile', ''); $wire.set('address', ''); $wire.set('balance', ''); $wire.set('customerId', '') }"
                                class="btn btn-sm btn-primary"
                                type="button">
                                <span x-text="isOpen ? 'Hide This' : 'Add Delivery Man'"></span>
                            </button>
                        </div>

                        <!-- Collapse Section -->
                        <div x-show="isOpen" x-transition x-cloak>
                            <div class="card card-body">
                                <form wire:submit.prevent="submit">
                                    <div class="row g-2">
                                        <div class="col-3">
                                            <input class="form-control" type="text" id="name" wire:model="name" placeholder="Delivery Man Name" aria-label="Delivery Man Name">
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
                                        <div class="col-3">
                                            <input class="form-control" type="number" id="balance" wire:model="balance" placeholder="Balance" aria-label="Balance">
                                            @error('balance') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        {{-- <div class="col-3">
                                            <select name="supplier_type" id="supplier_type" class="form-control" wire:model="supplier_type">
                                                <option value="">Select Customer Type</option>
                                                @can('create-customer')
                                                    <option value="customer">Customer</option>
                                                @endcan
                                                @can('create-supplier')
                                                    <option value="supplier">Supplier</option>
                                                @endcan
                                            </select>
                                            @error('supplier_type') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div> --}}
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
                    <div class="col-3">
                        <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search" aria-label="Search By Name">
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>User ID</th>
                            <th>Delivery Man Name</th>
                            <th>Email Address</th>
                            <th>mobile</th>
                            {{-- <th>Balance</th> --}}
                            <th>Address</th>
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
                                <td>
                                    @can('edit-delivery-man')
                                        <button class="btn btn-sm btn-info" wire:click="edit({{ $customer->id }})" @click="isOpen = true"><i class="bi bi-pencil-square"></i></button>
                                    @endcan
                                    @can('delete-delivery-man')
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $customer->id }})"><i class="bi bi-trash"></i></button>
                                    @endcan

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>

<div x-data="{ isOpen: false }">
    <!-- Header -->
    <x-slot name="header">
        {{ __('Customer List') }}
    </x-slot>

    <div class="d-flex justify-content-center">
        <div class="col-8">
            @if(auth()->user()->can('create-customer') || auth()->user()->can('create-supplier'))
                <div class="row">
                    <div class="col">
                        <div class="p-1">
                            <!-- Toggle Button -->
                            <button
                                @click="isOpen = !isOpen; if (!isOpen) { $wire.set('customer_name', ''); $wire.set('email', ''); $wire.set('mobile', ''); $wire.set('address', ''); $wire.set('balance', ''); }"
                                class="btn btn-sm btn-primary"
                                type="button">
                                <span x-text="isOpen ? 'Hide This' : 'Add Customer'"></span>
                            </button>
                        </div>

                        <!-- Collapse Section -->
                        <div x-show="isOpen" x-transition x-cloak>
                            <div class="card card-body">
                                <form wire:submit.prevent="submit">
                                    <div class="row">
                                        <div class="col">
                                            <input class="form-control" type="text" id="customer_name" wire:model="customer_name" placeholder="customer Name" aria-label="customer Name">
                                            @error('customer_name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" id="email" wire:model="email" placeholder="Email Address" aria-label="Email Address">
                                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" id="mobile" wire:model="mobile" placeholder="mobile" aria-label="mobile">
                                            @error('mobile') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="address" id="address" wire:model="address" placeholder="address" aria-label="address">
                                            @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="number" id="balance" wire:model="balance" placeholder="Balance" aria-label="Balance">
                                            @error('balance') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col">
                                            <select name="customer_type" id="customer_type" class="form-control" wire:model="customer_type">
                                                @can('create-customer')
                                                    <option value="customer">Customer</option>
                                                @endcan
                                                @can('create-supplier')
                                                    <option value="supplier">Supplier</option>
                                                @endcan
                                            </select>
                                            @error('customer_type') <span class="text-danger">{{ $message }}</span> @enderror
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
                <table class="table">
                    <thead>
                        <tr>
                            <th>customer Name</th>
                            <th>Email Address</th>
                            <th>mobile</th>
                            <th>Balance</th>
                            <th>Address</th>
                            <th>Action</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->customer_name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->mobile }}</td>
                                <td>{{ $customer->balance }}</td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $customer->id }})"><i class="bi bi-trash"></i></button>
                                    <button class="btn btn-sm btn-info" wire:click="edit({{ $customer->id }})" @click="isOpen = true"><i class="bi bi-pencil-square"></i></button>
                                    <button
                                        x-data="{
                                            dataSyncFunction(customer_id) {
                                                $(customer_id).text('');
                                                let spinnerSpan = document.createElement('span');
                                                spinnerSpan.classList.add('spinner-border', 'spinner-border-sm');
                                                spinnerSpan.setAttribute('aria-hidden', 'true');
                                                $(customer_id).append(spinnerSpan);
                                            }
                                        }"
                                        x-on:click="dataSyncFunction('#customers-' + {{ $customer->id }})"
                                        class="btn btn-sm btn-primary" wire:click="dataSync({{ $customer->id }})"><i class="bi bi-arrow-repeat"></i></button>
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

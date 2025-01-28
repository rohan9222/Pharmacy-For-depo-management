<div x-data="{ isOpen: false }">
    <!-- Header -->
    <x-slot name="header">
        {{ __('Customer List') }}
    </x-slot>

    <div class="d-flex justify-content-center">
        <div class="col-8">
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
                                        <div class="col-3">
                                            <input class="form-control" type="number" id="balance" wire:model="balance" placeholder="Balance" aria-label="Balance">
                                            @error('balance') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <select name="field_officer_team" id="field_officer_team" class="form-control" wire:model="field_officer_team">
                                                <option value="">Select Field Officer</option>
                                                @foreach ($field_officers as $field_officer)
                                                    <option value="{{ $field_officer->id }}"
                                                        {{ isset($field_officer->fieldOfficer) && $field_officer->fieldOfficer->id == $field_officer->id
                                                            ? 'selected'
                                                            : ($field_officer->id == auth()->user()->id ? 'selected' : '') }}>
                                                        {{ $field_officer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('field_officer_team') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <input type="text" class="form-control" id="route" wire:model="route" placeholder="Route" aria-label="Route">
                                            @error('route') <span class="text-danger">{{ $message }}</span> @enderror
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
                    <div class="col-3">
                        <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search By Name" aria-label="Search By Name">
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>customer Name</th>
                            <th>Email Address</th>
                            <th>mobile</th>
                            {{-- <th>Balance</th> --}}
                            <th>Address</th>
                            <th>Field Officer</th>
                            <th>Route</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->mobile }}</td>
                                {{-- <td>{{ $customer->balance }}</td> --}}
                                <td>{{ $customer->address }}</td>
                                <td>{{ $customer->fieldOfficer->name ?? 'N/A' }}</td>
                                <td>{{ $customer->route ?? 'N/A' }}</td>
                                <td>
                                    @can('view-customer')
                                        <button class="btn btn-sm btn-primary" ><i class="bi bi-eye"></i></button>
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

            <div class="pagination">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>

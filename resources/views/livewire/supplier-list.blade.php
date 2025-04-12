<div x-data="{ isOpen: false }">
    <!-- Header -->
    <x-slot name="header">
        {{ __('supplier List') }}
    </x-slot>

    <div class="d-flex justify-content-center">
        <div class="col-10">
            @if(auth()->user()->can('create-supplier') || $supplierId)
                <div class="row">
                    <div class="col">
                        <div class="p-1">
                            <!-- Toggle Button -->
                            <button
                                @click="isOpen = !isOpen; if (!isOpen) { $wire.set('name', ''); $wire.set('email', ''); $wire.set('mobile', ''); $wire.set('address', ''); $wire.set('balance', ''); $wire.set('supplierId', ''); }"
                                class="btn btn-sm btn-primary"
                                type="button">
                                <span x-text="isOpen ? 'Hide This' : 'Add supplier'"></span>
                            </button>
                        </div>

                        <!-- Collapse Section -->
                        <div x-show="isOpen" x-transition x-cloak>
                            <div class="card card-body">
                                <form wire:submit.prevent="submit">
                                    <div class="row g-2">
                                        <div class="col-lg-3 col-md-6">
                                            <input class="form-control" type="text" id="name" wire:model="name" placeholder="supplier Name" aria-label="supplier Name">
                                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <input class="form-control" type="text" id="email" wire:model="email" placeholder="Email Address" aria-label="Email Address">
                                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <input class="form-control" type="text" id="mobile" wire:model="mobile" placeholder="mobile" aria-label="mobile">
                                            @error('mobile') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <input class="form-control" type="address" id="address" wire:model="address" placeholder="address" aria-label="address">
                                            @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <input class="form-control" type="number" id="balance" wire:model="balance" placeholder="Balance" aria-label="Balance">
                                            @error('balance') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <select name="supplier_type" id="supplier_type" class="form-control" wire:model="supplier_type">
                                                <option value="">Select Supplier Type</option>
                                                @can('create-supplier')
                                                    <option value="manufacturer">Manufacturer</option>
                                                    <option value="supplier">Supplier</option>
                                                @endcan
                                            </select>
                                            @error('supplier_type') <span class="text-danger">{{ $message }}</span> @enderror
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
                    <div class="col-lg-3 col-md-6">
                        <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search" aria-label="Search By Name">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Supplier Name</th>
                                <th>Email Address</th>
                                <th>mobile</th>
                                <th>Balance</th>
                                <th>Address</th>
                                <th>Supplier Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->email }}</td>
                                    <td>{{ $supplier->mobile }}</td>
                                    <td>{{ $supplier->balance }}</td>
                                    <td>{{ $supplier->address }}</td>
                                    <td>{!! ($supplier->supplier_type == 'supplier') ? '<span class="badge text-bg-info">Supplier</span>' : '<span class="badge text-bg-success">Manufacturer</span>' !!}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" wire:click="edit({{ $supplier->id }})" @click="isOpen = true"><i class="bi bi-pencil-square"></i></button>
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $supplier->id }})"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pagination">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>

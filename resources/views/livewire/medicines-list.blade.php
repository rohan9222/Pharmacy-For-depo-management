<div x-data="{ isOpen: false }">
    <!-- Header -->
    <x-slot name="header">
        {{ __('medicine List') }}
    </x-slot>

    <div class="d-flex justify-content-center">
        <div class="col-8">
            @if(auth()->user()->can('create-medicine') || $medicineId)
                <div class="row">
                    <div class="col">
                        <div class="p-1">
                            <!-- Toggle Button -->
                            <button
                                @click="isOpen = !isOpen; if (!isOpen) { $wire.set('name', ''); $wire.set('generic_name', ''); $wire.set('description', ''); $wire.set('shelf', ''); $wire.set('category_name', ''); $wire.set('medicineId', ''); }"
                                class="btn btn-sm btn-primary"
                                type="button">
                                <span x-text="isOpen ? 'Hide This' : 'Add medicine'"></span>
                            </button>
                        </div>

                        <!-- Collapse Section -->
                        <div x-show="isOpen" x-transition x-cloak>
                            <div class="card card-body">
                                <form wire:submit.prevent="submit">
                                    <div class="row g-2">
                                        <div class="col-3">
                                            <input class="form-control" type="text" id="name" wire:model="name" placeholder="medicine Name" aria-label="medicine Name">
                                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="text" id="generic_name" wire:model="generic_name" placeholder="Generic Name" aria-label="Generic Name">
                                            @error('generic_name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="text" id="description" wire:model="description" placeholder="description" aria-label="description">
                                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="shelf" id="shelf" wire:model="shelf" placeholder="shelf" aria-label="shelf">
                                            @error('shelf') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <select name="supplier_name" id="supplier_name" class="form-control" wire:model="supplier_name">
                                                <option value="">Select Supplier Name</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->name }}" {{ ($supplier->name ?? '') == $supplier_name ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <select name="category_name" id="category_name" class="form-control" wire:model="category_name">
                                                <option value="">Select Category Name</option>
                                                @foreach ($categoryLists as $categoryList)
                                                    <option value="{{ $categoryList->name }}" {{ ($categoryList->name ?? '') == $category_name ? 'selected' : '' }}>
                                                        {{ $categoryList->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_name') <span class="text-danger">{{ $message }}</span> @enderror
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
                <div class="row justify-content-end mb-1">
                    <div class="col-3">
                        <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search By Name" aria-label="Search By Name">
                    </div>
                </div>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Image</th>
                            <th>Bar Code</th>
                            <th>Medicine Name</th>
                            <th>Generic Name</th>
                            <th>Description</th>
                            <th>Shelf No</th>
                            <th>Category Name</th>
                            <th>Supplier Name</th>
                            <th>Supplier Price</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medicines as $medicine)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $medicine->image_url }}</td>
                                <td>{{ $medicine->bar_code }}</td>
                                <td>{{ $medicine->name }}</td>
                                <td>{{ $medicine->generic_name }}</td>
                                <td>{{ $medicine->description }}</td>
                                <td>{{ $medicine->shelf }}</td>
                                <td>{{ $medicine->category_name }}</td>
                                <td>{{ $medicine->supplier }}</td>
                                <td>{{ $medicine->supplier_price }}</td>
                                <td>{{ $medicine->price }}</td>
                                <td>{{ $medicine->quantity }}</td>
                                <td>{{ ($medicine->deleted_at != null) ? 'Deleted' : (($medicine->status == 'active') ? 'Active' : 'Inactive') }}</td>
                                <td>
                                    @can('edit-medicine')
                                        <button class="btn btn-sm btn-info" wire:click="edit({{ $medicine->id }})" @click="isOpen = true"><i class="bi bi-pencil-square"></i></button>
                                    @endcan

                                    @can('delete-medicine')
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $medicine->id }})"><i class="bi bi-trash"></i></button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $medicines->links() }}
            </div>
        </div>
    </div>
</div>

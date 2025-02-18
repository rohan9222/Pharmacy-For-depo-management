<div x-data="{ isOpen: false }">
    <!-- Header -->
    <x-slot name="header">
        {{ __('medicine List') }}
    </x-slot>

    <div class="d-flex justify-content-center">
        <div class="col-11">
            @if(auth()->user()->can('create-medicine') || $medicineId)
                <div class="row">
                    <div class="col">
                        <div class="p-1">
                            <!-- Toggle Button -->
                            <button
                                @click="isOpen = !isOpen; if (!isOpen) { $wire.set('name', ''); $wire.set('generic_name', ''); $wire.set('description', ''); $wire.set('shelf', ''); $wire.set('category_name', ''); $wire.set('medicineId', ''); $wire.set('supplier_name', ''); $wire.set('supplier_price', ''); $wire.set('pack_size', ''); $wire.set('medicineId', ''); $wire.set('price', ''); $wire.set('quantity', ''); $wire.set('image_url', ''); $wire.set('barcode', '') }"
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
                                            <div class="input-group">
                                                <input class="form-control" type="text" id="barcode" wire:model="barcode" placeholder="Bar Code" aria-label="Bar Code">
                                                <span class="input-group-text bg-info bg-opacity-10"><i class="bi bi-upc-scan"></i></span>
                                            </div>
                                            @error('barcode') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="text" id="name" wire:model="name" placeholder="Medicine Name" aria-label="Medicine Name">
                                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="text" id="generic_name" wire:model="generic_name" placeholder="Generic Name" aria-label="Generic Name">
                                            @error('generic_name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="text" id="description" wire:model="description" placeholder="Description" aria-label="Description">
                                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="text" id="shelf" wire:model="shelf" placeholder="Shelf No" aria-label="Shelf No">
                                            @error('shelf') <span class="text-danger">{{ $message }}</span> @enderror
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
                                        <div class="col-3">
                                            <select name="pack_size" id="pack_size" class="form-control" wire:model="pack_size">
                                                <option value="">Select Package</option>
                                                @foreach ($packSizeLists as $packSizeLists)
                                                    <option value="{{ $packSizeLists->pack_size }}" {{ ($packSizeLists->pack_size ?? '') == $pack_size ? 'selected' : '' }}>
                                                        {{ $packSizeLists->pack_name }} ({{ $packSizeLists->pack_size }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('pack_size') <span class="text-danger">{{ $message }}</span> @enderror
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
                                            <div class="input-group">
                                                <input type="text" name="supplier_price" placeholder="Supplier Price" id="supplier_price" class="form-control" wire:model="supplier_price">
                                                <span class="input-group-text bg-info bg-opacity-10">৳</span>
                                            </div>
                                            @error('supplier_price') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <div class="input-group">
                                                <input type="text" name="price" id="price" placeholder="Price" class="form-control" wire:model="price">
                                                <span class="input-group-text bg-info bg-opacity-10">৳</span>
                                            </div>
                                            @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- <div class="col-3">
                                            <input type="text" name="discount" id="discount" placeholder="Discount" class="form-control" wire:model="discount">
                                            @error('discount') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <select name="dis_type" id="dis_type" class="form-control" wire:model="dis_type">
                                                <option value="percentage" {{ ($dis_type ?? '') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                                <option value="fixed" {{ ($dis_type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                            </select>
                                            @error('dis_type') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                         --}}
                                        <div class="col-3">
                                            <div class="input-group">
                                                <input type="text" name="vat" id="vat" placeholder="VAT" class="form-control" wire:model="vat">
                                                <span class="input-group-text bg-info bg-opacity-10">%</span>
                                            </div>
                                            @error('vat') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        @if (!$medicineId)
                                            <div class="col-3">
                                                <input type="text" name="quantity" id="quantity" placeholder="Opening Stock" class="form-control" wire:model="quantity">
                                                @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        @endif

                                        <div class="col-3">
                                            <select name="status" class="form-control" id="status" wire:model="status">
                                                <option value="1" selected>Available</option>
                                                <option value="0">Not Available</option>
                                            </select>
                                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            <input type="file" name="image_url" wire:model="image_url" id="image_url" class="form-control">
                                            @error('image_url') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-3">
                                            @if ($image_url)
                                                <label class="form-label" for="upload_image_url">Photo Preview:</label>
                                                <img id="upload_image_url" src="{{ $medicineId ? asset($image_url) : $image_url->temporaryUrl() }}" alt="Image Preview" class="img-fluid img-thumbnail" style="max-width: 200px; max-height: 200px;"><button type="button" class="btn btn-white btn-sm text-danger mx-2 fs-4" wire:click="removePhoto"><i class="bi bi-x-circle-fill"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                    <button class="btn btn-primary mt-2" type="submit">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @can('view-medicine')
                <div class="row mt-3 table-responsive">
                    <div class="row justify-content-end mb-1">
                        <div class="col-3">
                            <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search By Name" aria-label="Search By Name">
                        </div>
                    </div>
                    <table class="table table-bordered table-sm table-responsive">
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
                                <th>Pack Size</th>
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
                                    <td><img src="{{ url($medicine->image_url ?? url('img/medicine-logo.png')) }}" alt="{{ $medicine->name }}" class="img-fluid img-thumbnail" style="max-width: 100px; max-height: 100px;" title="{{ $medicine->name }}"></td>
                                    <td class="text-center">{!! $medicine->barcode_html !!} <br> {{ $medicine->barcode }}</td>
                                    <td>{{ $medicine->name }}</td>
                                    <td>{{ $medicine->generic_name }}</td>
                                    <td>{{ $medicine->description }}</td>
                                    <td>{{ $medicine->shelf }}</td>
                                    <td>{{ $medicine->category_name }}</td>
                                    <td>{{ $medicine->pack_size }}</td>
                                    <td>{{ $medicine->supplier }}</td>
                                    <td>{{ $medicine->supplier_price }}</td>
                                    <td>{{ $medicine->price }}</td>
                                    <td>{{ $medicine->quantity }}</td>
                                    <td>{{ ($medicine->deleted_at != null) ? 'Deleted' : (($medicine->status == '1') ? 'Available' : 'Not Available') }}</td>
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
            @endcan
        </div>
    </div>
</div>

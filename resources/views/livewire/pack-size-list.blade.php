<div x-data="{ isOpen: false }">
    <!-- Header -->
    <x-slot name="header">
        {{ __('Packages List') }}
    </x-slot>

    <div class="d-flex justify-content-center">
        <div class="col-8">
            @if(auth()->user()->can('create-packSize') || $packSizeId)
                <div class="row">
                    <div class="col">
                        <div class="p-1">
                            <!-- Toggle Button -->
                            <button
                                @click="isOpen = !isOpen; if (!isOpen) { $wire.set('name', ''); $wire.set('description', ''); $wire.set('status', ''); $wire.set('address', ''); $wire.set('balance', ''); $wire.set('packSizeId', ''); }"
                                class="btn btn-sm btn-primary"
                                type="button">
                                <span x-text="isOpen ? 'Hide This' : 'Add packSize'"></span>
                            </button>
                        </div>

                        <!-- Collapse Section -->
                        <div x-show="isOpen" x-transition x-cloak>
                            <div class="card card-body">
                                <form wire:submit.prevent="submit">
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <input class="form-control" type="text" id="pack_name" wire:model="pack_name" placeholder="Packages Name" aria-label="Packages Name">
                                            @error('pack_name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-4">
                                            <input class="form-control" type="text" id="pack_size" wire:model="pack_size" placeholder="Packages Size" aria-label="Packages Size">
                                            @error('pack_size') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-4">
                                            <input class="form-control" type="text" id="description" wire:model="description" placeholder="Description" aria-label="Description">
                                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-4">
                                            <select name="status" class="form-control" id="status" wire:model="status">
                                                <option value="">Select Status</option>
                                                <option value="1">Available</option>
                                                <option value="0">Not Available</option>
                                            </select>
                                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
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
                        <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search" aria-label="Search By Name">
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Packages Name</th>
                            <th>Packages Size</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($packSizes as $packSize)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $packSize->pack_name }}</td>
                                <td>{{ $packSize->pack_size }}</td>
                                <td>{{ $packSize->description }}</td>
                                <td>{!! ($packSize->status == '1') ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-danger">Inactive</span>' !!}</td>
                                <td>
                                    @can('edit-packSize')
                                        <button class="btn btn-sm btn-info" wire:click="edit({{ $packSize->id }})" @click="isOpen = true"><i class="bi bi-pencil-square"></i></button>
                                    @endcan

                                    @can('delete-packSize')
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $packSize->id }})"><i class="bi bi-trash"></i></button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $packSizes->links() }}
            </div>
        </div>
    </div>
</div>

<div x-data="{ isOpen: false }">
    <!-- Header -->
    <x-slot name="header">
        {{ __('category List') }}
    </x-slot>

    <div class="d-flex justify-content-center">
        <div class="col-8">
            @if(auth()->user()->can('create-category') || $categoryId)
                <div class="row">
                    <div class="col">
                        <div class="p-1">
                            <!-- Toggle Button -->
                            <button
                                @click="isOpen = !isOpen; if (!isOpen) { $wire.set('name', ''); $wire.set('description', ''); $wire.set('status', ''); $wire.set('address', ''); $wire.set('balance', ''); $wire.set('categoryId', ''); }"
                                class="btn btn-sm btn-primary"
                                type="button">
                                <span x-text="isOpen ? 'Hide This' : 'Add category'"></span>
                            </button>
                        </div>

                        <!-- Collapse Section -->
                        <div x-show="isOpen" x-transition x-cloak>
                            <div class="card card-body">
                                <form wire:submit.prevent="submit">
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <input class="form-control" type="text" id="name" wire:model="name" placeholder="category Name" aria-label="category Name">
                                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-4">
                                            <input class="form-control" type="text" id="description" wire:model="description" placeholder="description Address" aria-label="description Address">
                                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-4">
                                            <select name="status" class="form-control" id="status" wire:model="status">
                                                <option value="">Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
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
                        <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search By Name" aria-label="Search By Name">
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->description }}</td>
                                <td>{!! ($category->status == '1') ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-danger">Inactive</span>' !!}</td>
                                <td>
                                    @can('edit-category')
                                        <button class="btn btn-sm btn-info" wire:click="edit({{ $category->id }})" @click="isOpen = true"><i class="bi bi-pencil-square"></i></button>
                                    @endcan

                                    @can('delete-category')
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $category->id }})"><i class="bi bi-trash"></i></button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>

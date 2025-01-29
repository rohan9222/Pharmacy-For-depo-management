<div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row"></div>
        <div class="content-body">
            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Settings</h4>
                        </div>
                        <div class="card-body">
                            <form class="form" wire:submit.prevent="updateSettings">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="site_name">Site Name</label>
                                            <input type="text" id="site_name" class="form-control" placeholder="Name" wire:model='site_name' name="name">
                                            @error('site_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="site_title">Site title</label>
                                            <input type="text" id="site_title" class="form-control" placeholder="Site title" wire:model='site_title' name="site_title">
                                            @error('site_title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="email" id="email" class="form-control" placeholder="Enter your email" wire:model='site_email' name="email">
                                            @error('site_email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="phone">Phone</label>
                                            <input type="text" id="phone" class="form-control" placeholder="Enter your phone" wire:model='site_phone' name="phone">
                                            @error('site_phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="site_logo">Site Logo</label>
                                            <input type="file" id="site_logo" class="form-control" wire:model='site_logo' name="site_logo">
                                            @error('site_logo')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div wire:loading wire:target="site_logo">Uploading...</div>
                                        <div class="col-3">
                                            @if ($site_logo)
                                                <label class="form-label" for="upload_site_logo">Photo Preview:</label>
                                                <img id="upload_site_logo" src="{{ $site_logo->temporaryUrl() }}" alt="Image Preview" class="img-fluid img-thumbnail" style="max-width: 200px; max-height: 200px;"><button type="button" class="btn btn-white btn-sm text-danger mx-2 fs-4" wire:click="removePhoto('logo')"><i class="bi bi-x-circle-fill"></i></button>
                                            @elseif($preview_site_logo)
                                                <label class="form-label" for="upload_site_logo">Photo Preview:</label>
                                                <img id="upload_site_logo" src="{{ asset($preview_site_logo) }}" alt="Image Preview" class="img-fluid img-thumbnail" style="max-width: 200px; max-height: 200px;"><button type="button" class="btn btn-white btn-sm text-danger mx-2 fs-4" wire:click="removePreviewPhoto('logo')"><i class="bi bi-x-circle-fill"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="favicon">Site Favicon</label>
                                            <input type="file" id="favicon" class="form-control" wire:model='site_favicon' name="favicon">
                                            @error('site_favicon')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div wire:loading wire:target="site_favicon">Uploading...</div>
                                        <div class="col-3">
                                            @if ($site_favicon)
                                                <label class="form-label" for="upload_site_favicon">Photo Preview:</label>
                                                <img id="upload_site_favicon" src="{{ $site_favicon->temporaryUrl() ?? asset($site_favicon) }}" alt="Image Preview" class="img-fluid img-thumbnail" style="max-width: 200px; max-height: 200px;"><button type="button" class="btn btn-white btn-sm text-danger mx-2 fs-4" wire:click="removePhoto('favicon')"><i class="bi bi-x-circle-fill"></i></button>
                                            @elseif($preview_site_favicon)
                                                <label class="form-label" for="upload_site_favicon">Photo Preview:</label>
                                                <img id="upload_site_favicon" src="{{ asset($preview_site_favicon) }}" alt="Image Preview" class="img-fluid img-thumbnail" style="max-width: 200px; max-height: 200px;"><button type="button" class="btn btn-white btn-sm text-danger mx-2 fs-4" wire:click="removePreviewPhoto('favicon')"><i class="bi bi-x-circle-fill"></i></button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="last-name-column">Address</label>
                                            <input type="text" id="last-name-column" class="form-control" placeholder="Address" wire:model='site_address' name="address">
                                            @error('site_address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="city-column">Currency</label>
                                            <input type="text" id="city-column" class="form-control" placeholder="Currency" wire:model='site_currency' name="currency">
                                            @error('site_currency')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="country-floating">Invoice Prefix</label>
                                            <input type="text" id="country-floating" class="form-control" name="prefix" placeholder="Invoice Prefix" wire:model='site_invoice_prefix'>
                                            @error('site_invoice_prefix')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">Upcoming Expire Alert</label>
                                            <input type="number" class="form-control" name="medicine_expiry_days" placeholder="Ex: 15" wire:model='medicine_expiry_days'>
                                            <small class="text-warning"><i class="fa fa-warning"></i>
                                                Enter number of day</small>
                                                @error('medicine_expiry_days')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">Low Stock Alert Quantity</label>
                                            <input type="number" class="form-control" name="low_stock_alert" placeholder="Ex: 2" wire:model='medicine_low_stock_quantity'>
                                            <small class="text-warning"><i class="fa fa-warning"></i>
                                                Enter number of quantity</small>
                                                @error('medicine_low_stock_quantity')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="card">
                        <div class="card-header">
                            <table class="table table-striped table-bordered" x-data="{ isOpen: false }">
                                <thead>
                                    <tr class="text-center"><th colspan="4"><h3>Bonuses for refills</h3></th></tr>
                                    <tr class="text-start">
                                        <th colspan="4">
                                            <div class="p-1">
                                                <!-- Toggle Button -->
                                                <button
                                                    @click="isOpen = !isOpen; if (!isOpen) { $wire.set('start_amount', ''); $wire.set('end_amount', ''); $wire.set('discount', ''); $wire.set('discountId', ''); }"
                                                    class="btn btn-sm btn-primary"
                                                    type="button">
                                                    <span x-text="isOpen ? 'Hide This' : 'Add Discount'"></span>
                                                </button>
                                            </div>

                                            <!-- Collapse Section -->
                                            <div x-show="isOpen" x-transition x-cloak>
                                                <div class="card card-body">
                                                    <form wire:submit.prevent="addDiscountValue">
                                                        <div class="row g-2">
                                                            <div class="col-8">
                                                                <div class="input-group mb-3">
                                                                    <input class="form-control" type="text" id="start_amount" wire:model="start_amount" placeholder="Start Amount" aria-label="start_amount">
                                                                    <span class="input-group-text">To</span>
                                                                    <input class="form-control" type="text" id="end_amount" wire:model="end_amount" placeholder="End Amount" aria-label="end_amount">
                                                                  </div>
                                                                @error('start_amount') <span class="text-danger">{{ $message }}</span> @enderror
                                                                @error('end_amount') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                                                            <div class="col-4">
                                                                <div class="input-group mb-3">
                                                                    <input class="form-control" type="text" id="discount" wire:model="discount" placeholder="Discount" aria-label="discount">
                                                                    <span class="input-group-text" id="basic-addon2">%</span>
                                                                </div>
                                                                @error('discount') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                                                        </div>
                                                        <button class="btn btn-primary mt-2" type="submit">Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Id</th>
                                        <th>Amount</th>
                                        <th>Discount Value</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($discount_values as $discount_value)
                                        <tr class="text-center">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $discount_value->start_amount }} - {{ $discount_value->end_amount }}</td>
                                            <td>{{ $discount_value->discount }}</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" wire:click="editDiscountValue({{ $discount_value->id }})" @click="isOpen = true"><i class="bi bi-pencil-square"></i></button>
                                                <button class="btn btn-danger btn-sm" wire:click="deleteDiscountValue({{ $discount_value->id }})"><i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

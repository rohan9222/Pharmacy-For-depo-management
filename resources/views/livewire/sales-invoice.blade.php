<div class="row">
    <div class="col-md-5 card">
        <div class="row px-4 py-1 slick-category" wire:ignore>
            <div class="btn btn-outline-success btn-sm" wire:click="medicinesCategory(null)" >All</div>
            @foreach ($category_lists as $category_list)
                <div class="btn btn-outline-success btn-sm" wire:click="medicinesCategory('{{ $category_list->name }}')" >{{ $category_list->name }}</div>
            @endforeach
        </div>
        {{-- <div class="row px-4 py-1 slick-category" wire:ignore>
            <div class="btn btn-outline-success btn-sm {{ $category == '' ? 'active' : '' }}" wire:click="medicinesCategory(null)" >All</div>
            @foreach ($category_lists as $category_list)
                <div class="btn btn-outline-success btn-sm {{ $category == $category_list->name ? 'active' : '' }}" wire:click="medicinesCategory('{{ $category_list->name }}')" >{{ $category_list->name }}</div>
            @endforeach
        </div> --}}

        <div class="row px-4 py-1">
            <div class="col-12">
                <input id="search" class="form-control from-control-sm" type="search" wire:model.live="search" placeholder="Search By Name" aria-label="Search By Name" autofocus>
            </div>
            <div class="col-12 mt-1">
                {{$category == '' ?  'All' : $category}} Medicines List ({{ count($medicines) }})
            </div>
        </div>

        <div class="row g-1 nav">
            @if (!empty($medicines))
                @foreach ($medicines as  $medicine)
                        {{-- @dump($medicine) --}}
                    <div class="col-lg-2 col-lg-3 col-md-4 col-6 col-xl-2">
                        <div wire:click="addMedicine('{{ $medicine->id }}')" class="p-1 w-100 nav-item btn border border-0 {{ $medicine->quantity > 0 ? '' : 'disabled' }}" style="cursor: pointer;" wire:key="medicine-{{ $medicine->id }}">
                            <div class="card shadow-sm position-relative nav-link p-0">
                                <div class="position-relative p-1">
                                    <div class="card-header p-2">
                                        <div class="d-flex align-items-center justify-content-center d-block w-100" style="height: 75px;">
                                            <img class="w-100 h-100 img-fluid img-thumbnail" src="{{ asset($medicine->image_url ?? 'img/medicine-logo.png') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="position-relative p-0">
                                    <div class="card-body p-1 text-center">
                                        <h6 class="fs-6 text-dark m-0">
                                            {{ $medicine->name }}
                                        </h6>
                                        {{-- <span class="text-muted fs-6">(Batch : {{ $medicine->batch_number }})</span> --}}
                                    </div>
                                </div>
                                @if ($medicine->quantity > 0)
                                    <span class="position-absolute top-0 end-0 badge bg-primary text-white m-1">
                                        QTY: {{$medicine->quantity}}
                                    </span>
                                @else
                                    <span class="position-absolute top-0 end-0 badge bg-danger text-white m-1">
                                        Out of Stock
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        @error('medicine_list')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-7 card">
        <form wire:submit.prevent="submit">
            <div class="row g-2">
                <div class="col-4">
                    <label for="invoice_date" class="form-label">Date</label>
                    <input type="date" class="form-control" wire:model.live.debounce.1000ms="invoice_date">
                    @error('invoice_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                {{-- <div class="col-4">
                    <label for="invoice_no" class="form-label">Invoice No</label>
                    <input type="text" class="form-control" wire:model.live.debounce.1000ms="invoice_no" readonly>
                </div> --}}
                <div class="col-6">
                    <label for="customer" class="form-label">Customer</label>
                    <div class="input-group mb-3">
                        <select class="form-select tom-select p-0" wire:model.live.debounce.1000ms="customer" x-init="initTomSelect()">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{$customer->user_id}}-{{ $customer->name }} ({{$customer->mobile}})</option>
                            @endforeach
                        </select>
                        <span class="input-group-text" wire:click="refreshCustomer()"><i class="bi bi-arrow-counterclockwise"></i></span>
                        <span class="input-group-text"><a class="text-info" data-bs-toggle="modal" data-bs-target="#addCustomer">
                            <i class="bi bi-person-plus-fill"></i>
                        </a></span>
                    </div>
                    @error('customer') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="table-responsive row mt-3 p-2">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr class="text-center">
                            <th>SN</th>
                            <th>Medicine</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>MRP/Selling Price</th>
                            <th>Sub Total</th>
                            <th>Vat (%)</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockMedicines as $index => $medicine)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td class="text-start">
                                    <input type="hidden" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.medicine_id">
                                    {{ $medicine['medicine_name'] }}
                                </td>
                                <td class="text-start">{{ $medicine['category_name'] }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a class="btn btn-sm btn-outline-danger" wire:click="decreaseQuantity({{ $index }})"><i class="bi bi-dash-lg"></i></a>
                                        <input type="number" class="form-control form-control-sm  text-center p-0" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.quantity" style="min-width: 50px">
                                        <a class="btn btn-sm btn-outline-success" wire:click="increaseQuantity({{ $index }})"><i class="bi bi-plus-lg"></i></a>
                                    </div>
                                    @error("stockMedicines.{$index}.quantity") <span class="text-danger">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    {{-- <input type="text" class="form-control form-control-sm" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.price" disabled> --}}
                                    <span>{{ $stockMedicines[$index]['price'] }}</span>
                                    @error("stockMedicines.{$index}.price") <span class="text-danger">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    {{-- <input type="text" class="form-control form-control-sm" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.sub_total" disabled> --}}
                                    <span>{{ $stockMedicines[$index]['sub_total'] }}</span>
                                </td>
                                <td>
                                    {{-- <input type="text" class="form-control form-control-sm" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.vat" disabled> --}}
                                    <span>{{ $stockMedicines[$index]['vat'] }}</span>
                                    @error("stockMedicines.{$index}.vat") <span class="text-danger">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    {{-- <input type="text" class="form-control form-control-sm" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.total" disabled> --}}
                                    <span>{{ $stockMedicines[$index]['total'] }}</span>
                                </td>
                                <td><button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeMedicine({{ $index }})"><i class="bi bi-x"></i></button></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8" class="text-center">
                                <b>
                                    @error('stockMedicines')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </b>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <hr>
            <div class="row d-flex justify-content-end">
                <div class="col-5">
                    <table class="table table-sm">
                        <tr>
                            <th>Sub Total</th>
                            <th>:</th>
                            <td>{{$sub_total}} ৳</td>
                        </tr>
                        <tr>
                            <th>Vat(%)</th>
                            <th>:</th>
                            <td>{{$vat}} ৳</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <th>:</th>
                            <td>{{$total}} ৳</td>
                        </tr>
                        <tr>
                            <th>Discount(%)</th>
                            <th>:</th>
                            <td>
                                <div class="input-group w-75 ">
                                    {{-- <input type="text" placeholder="Spacial Discount Price" class="form-control form-control-sm" wire:model.live.debounce.1000ms="discount" disabled readonly> --}}
                                    <input type="text" placeholder="Discount Price" class="form-control form-control-sm" value="{{$discount}}" disabled readonly>
                                    <span class="input-group-text bg-info bg-opacity-10">{{$discount_amount}}৳</span>
                                </div>
                                @error('discount') <span class="text-danger">{{ $message }}</span> @enderror
                            </td>
                        </tr>
                        @if (auth()->user()->role != 'Territory Sales Executive')
                            <tr>
                                <th>Spacial Discount (%)</th>
                                <th>:</th>
                                <td>
                                    <div class="input-group w-75 ">
                                        <input type="text" placeholder="Spacial Discount Price" class="form-control form-control-sm" wire:model.live.debounce.1000ms="spl_discount" >
                                        <span class="input-group-text bg-info bg-opacity-10">{{$spl_discount_amount}}৳</span>
                                    </div>
                                    @error('spl_discount') <span class="text-danger">{{ $message }}</span> @enderror
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>Grand Total</th>
                            <th>:</th>
                            <td>{{$grand_total}}</td>
                        </tr>
                        @if (auth()->user()->role != 'Territory Sales Executive')
                            <tr>
                                <th>Paid Amount</th>
                                <th>:</th>
                                <td>
                                    <div class="input-group w-75">
                                        <input type="text" placeholder="Paid Amount" class="form-control form-control-sm" wire:model.live.debounce.1000ms="paid_amount">
                                        <span class="input-group-text bg-info bg-opacity-10">৳</span>
                                    </div>
                                    @error('paid_amount') <span class="text-danger">{{ $message }}</span> @enderror
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>Total Due</th>
                            <th>:</th>
                            <td>{{$due_amount}}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-end">Save</button>
                </div>
            </div>
        </form>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="addCustomerLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="addCustomerLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="customerSubmit">
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
                            <select name="customer_category" id="customer_category" wire:model="customer_category" class="form-control">
                                <option value="">Select Category</option>
                                <option value="Institution">Institution</option>
                                <option value="General">General</option>
                            </select>
                            @error('customer_category') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <button class="btn btn-primary mt-2" type="submit">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
</div>
@push('styles')
    <style>
        .slick-slide{
            padding: 1px;
        }
        .slick-prev {
            left: -3px !important;
        }
        .slick-next {
            right: -3px !important;
        }
        .slick-prev, .slick-next {
            color: black !important;
            background: black !important;
            border: 1px solid black;
            border-radius: 50%;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.slick-category').slick({
                infinite: true,
                slidesToShow: 6,
                slidesToScroll: 1
            });
            // Listen for the 'focusInput' event from Livewire
            Livewire.on('focusInput', () => {
                // Set focus to the search input after form submission
                document.getElementById('search').focus();
            });
        });
    </script>
@endpush


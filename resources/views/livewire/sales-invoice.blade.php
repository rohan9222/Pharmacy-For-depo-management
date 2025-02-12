<div class="row">
    <div class="col-md-5 card z-2">
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
                <div class="col-4">
                    <label for="customer" class="form-label">Customer</label>
                    <div class="input-group mb-3">
                        <select class="form-select" wire:model.live.debounce.1000ms="customer">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        <span class="input-group-text" wire:click="refreshCustomer()"><i class="bi bi-arrow-counterclockwise"></i></span>
                        <span class="input-group-text"><a href="{{route('customers')}}" target="blank"><i class="bi bi-person-plus-fill"></i></a></span>
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
                                        <input type="number" class="form-control form-control-sm  text-center p-0" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.quantity">
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
                                    <input type="text" placeholder="Spacial Discount Price" class="form-control form-control-sm" value="{{$discount}}" disabled readonly>
                                    <span class="input-group-text bg-info bg-opacity-10">{{$discount_amount}}৳</span>
                                </div>
                                @error('discount') <span class="text-danger">{{ $message }}</span> @enderror
                            </td>
                        </tr>
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
                        <tr>
                            <th>Grand Total</th>
                            <th>:</th>
                            <td>{{$grand_total}}</td>
                        </tr>
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
</div>

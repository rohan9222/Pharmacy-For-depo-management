<div>
    <form wire:submit.prevent="submit">
        <div class="row g-2">
            <div class="col-4">
                <label for="invoice_date" class="form-label">Date</label>
                <input type="date" class="form-control" wire:model.live.debounce.1000ms="invoice_date">
                @error('invoice_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-4">
                <label for="invoice_no" class="form-label">Invoice No</label>
                <input type="text" class="form-control" wire:model.live.debounce.1000ms="invoice_no" readonly>
            </div>
            <div class="col-4">
                <label for="manufacturer" class="form-label">Manufacturer / Supplier</label>
                <select class="form-select" wire:model="manufacturer">
                    <option value="">Select Manufacturer</option>
                    @foreach ($manufacturers as $manufacturer)
                        <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                    @endforeach
                </select>
                @error('manufacturer') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-12" wire:ignore.self>
                <input
                    type="search"
                    name="medicine_list"
                    class="form-control w-100"
                    placeholder="Search Medicine Name"
                    wire:model.live.debounce.1000ms="medicine_list"
                    wire:focus="fetchMedicines"
                    wire:blur="clearMedicines"
                    autocomplete="off"
                    tabindex="1"
                    wire:keydown.arrow-down="incrementHighlight"
                    wire:keydown.arrow-up="decrementHighlight"
                    wire:keydown.enter="selectHighlightedMedicine"
                    id="medicine_list"
                >
                @if (!empty($medicines))
                    <ul class="list-group position-absolute" style="z-index: 1000;">
                        @foreach ($medicines as $index => $medicine)
                            <li
                                wire:click="addMedicine('{{ $medicine->id }}')"
                                class="list-group-item {{ $index === $highlightedIndex ? 'active' : '' }}"
                                style="cursor: pointer;"
                                wire:key="medicine-{{ $medicine->id }}"
                            >
                                {{ $medicine->id }}, {{ $medicine->medicine_name }}
                            </li>
                        @endforeach
                    </ul>
                @endif
                @error('medicine_list')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- <div class="table-responsive row mt-3">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Medicine</th>
                        <th>Batch</th>
                        <th>Expiry Date</th>
                        <th>Quantity</th>
                        <th>MRP/Selling Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockMedicines as $index => $medicine)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <input type="hidden" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.medicine_id">
                                <img src="{{ asset($medicine['medicine_image'] ?? 'default.png') }}" alt="" width="50">

                                {{ $stockMedicines[$index]['medicine_name'] }}
                            </td>
                            <td><input type="text" class="form-control" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.batch"></td>
                            <td><input type="date" class="form-control" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.expiry_date"></td>
                            <td><input type="number" class="form-control" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.quantity"></td>
                            <td><input type="number" class="form-control" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.price"></td>
                            <td>
                                <input type="number" class="form-control"
                                       wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.total"
                                       value="{{ $stockMedicines[$index]['quantity'] * $stockMedicines[$index]['price'] }}"
                                       readonly>
                            </td>
                            <td><button type="button" class="btn btn-danger" wire:click="removeMedicine({{ $index }})">Remove</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-12"></div>
            <hr>
            <div class="col-6">
                <label for="total" class="form-label">Total</label>
                <input type="text" class="form-control" wire:model.live.debounce.1000ms="total" readonly>
            </div>
            <div class="col-6">
                <label for="total" class="form-label">Discount</label>
                <input type="text" class="form-control" wire:model.live.debounce.1000ms="discount" readonly>
            </div>
            <div class="col-6">
                <label for="total" class="form-label">Grand Total</label>
                <input type="text" class="form-control" wire:model.live.debounce.1000ms="grand_total" readonly>
            </div>
            <div class="col-6">
                <label for="total" class="form-label">Paid Amount</label>
                <input type="text" class="form-control" wire:model.live.debounce.1000ms="paid_amount">
            </div>
            <div class="col-6">
                <label for="total" class="form-label">Due Amount</label>
                <input type="text" class="form-control" wire:model.live.debounce.1000ms="due_amount" readonly>
            </div>
        </div> --}}
        <div class="table-responsive row mt-3">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Medicine</th>
                        <th>Batch</th>
                        <th>Expiry Date</th>
                        <th>Quantity</th>
                        <th>MRP/Selling Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockMedicines as $index => $medicine)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <input type="hidden" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.medicine_id">
                                <img src="{{ asset($medicine['medicine_image'] ?? 'img/medicine-logo.png') }}" alt="" width="50">
                                {{ $medicine['medicine_name'] }}
                            </td>
                            <td>
                                <input type="text" class="form-control" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.batch">
                                @error("stockMedicines.{$index}.batch") <span class="text-danger">{{ $message }}</span> @enderror
                            </td>
                            <td>
                                <input type="date" class="form-control" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.expiry_date">
                                @error("stockMedicines.{$index}.expiry_date") <span class="text-danger">{{ $message }}</span> @enderror
                            </td>
                            <td>
                                <input type="number" class="form-control" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.quantity">
                                @error("stockMedicines.{$index}.quantity") <span class="text-danger">{{ $message }}</span> @enderror
                            </td>
                            <td>
                                <input type="text" class="form-control" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.price" >
                                @error("stockMedicines.{$index}.price") <span class="text-danger">{{ $message }}</span> @enderror
                            </td>
                            <td><input type="text" class="form-control" wire:model.live.debounce.1000ms="stockMedicines.{{ $index }}.total" readonly></td>
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
            <div class="col-4">
                <table class="table table-sm">
                    <tr>
                        <th>Sub Total</th>
                        <th>:</th>
                        <td>{{$total}} ৳</td>
                    </tr>
                    <tr>
                        <th>Discount</th>
                        <th>:</th>
                        <td>
                            <div class="input-group w-75 ">
                                <input type="text" placeholder="Discount Price" class="form-control form-control-sm" wire:model.live.debounce.1000ms="discount" >
                                <span class="input-group-text bg-info bg-opacity-10">৳</span>
                            </div>
                            @error('discount') <span class="text-danger">{{ $message }}</span> @enderror
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
    </form>
</div>

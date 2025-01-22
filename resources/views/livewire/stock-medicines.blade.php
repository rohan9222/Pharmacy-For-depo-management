<div>
    <form wire:submit.prevent="submit">
        <div class="row g-2">
            <div class="col-4">
                <label for="invoice_date" class="form-label">Date</label>
                <input type="date" class="form-control" wire:model.live.debounce.150ms="invoice_date">
                @error('invoice_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-4">
                <label for="invoice_no" class="form-label">Invoice No</label>
                <input type="text" class="form-control" wire:model.live.debounce.150ms="invoice_no" readonly>
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
                    wire:model.live.debounce.150ms="medicine_list"
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
                                <input type="hidden" wire:model.live.debounce.150ms="stockMedicines.{{ $index }}.medicine_id">
                                <img src="{{ asset($medicine['medicine_image'] ?? 'default.png') }}" alt="" width="50">

                                {{ $stockMedicines[$index]['medicine_name'] }}
                            </td>
                            <td><input type="text" class="form-control" wire:model.live.debounce.150ms="stockMedicines.{{ $index }}.batch"></td>
                            <td><input type="date" class="form-control" wire:model.live.debounce.150ms="stockMedicines.{{ $index }}.expiry_date"></td>
                            <td><input type="number" class="form-control" wire:model.live.debounce.150ms="stockMedicines.{{ $index }}.quantity"></td>
                            <td><input type="number" class="form-control" wire:model.live.debounce.150ms="stockMedicines.{{ $index }}.price"></td>
                            <td>
                                <input type="number" class="form-control"
                                       wire:model.live.debounce.150ms="stockMedicines.{{ $index }}.total"
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
                <input type="text" class="form-control" wire:model.live.debounce.150ms="total" readonly>
            </div>
            <div class="col-6">
                <label for="total" class="form-label">Discount</label>
                <input type="text" class="form-control" wire:model.live.debounce.150ms="discount" readonly>
            </div>
            <div class="col-6">
                <label for="total" class="form-label">Grand Total</label>
                <input type="text" class="form-control" wire:model.live.debounce.150ms="grand_total" readonly>
            </div>
            <div class="col-6">
                <label for="total" class="form-label">Paid Amount</label>
                <input type="text" class="form-control" wire:model.live.debounce.150ms="paid_amount">
            </div>
            <div class="col-6">
                <label for="total" class="form-label">Due Amount</label>
                <input type="text" class="form-control" wire:model.live.debounce.150ms="due_amount" readonly>
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
                                <input type="hidden" wire:model.live.debounce.150ms="stockMedicines.{{ $index }}.medicine_id">
                                <img src="{{ asset($medicine['medicine_image'] ?? 'default.png') }}" alt="" width="50">
                                {{ $medicine['medicine_name'] }}
                            </td>
                            <td><input type="text" class="form-control" wire:model.live.debounce.150ms="stockMedicines.{{ $index }}.batch"></td>
                            <td><input type="date" class="form-control" wire:model.live.debounce.150ms="stockMedicines.{{ $index }}.expiry_date"></td>
                            <td><input type="number" class="form-control" wire:model.live.debounce.150ms="stockMedicines.{{ $index }}.quantity"></td>
                            <td><input type="number" class="form-control" wire:model.live.debounce.150ms="stockMedicines.{{ $index }}.price" value="{{ number_format($medicine['price'], 2) }}"></td>
                            <td>
                                <input type="number" class="form-control"
                                       wire:model.live.debounce.150ms="stockMedicines.{{ $index }}.total"
                                       value="{{ number_format($medicine['quantity'] * $medicine['price'], 2) }}" readonly>
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
                <input type="text" class="form-control" wire:model.live.debounce.150ms="total" value="{{ number_format($total, 2) }}" readonly>
            </div>
            <div class="col-6">
                <label for="discount" class="form-label">Discount</label>
                <input type="number" class="form-control" wire:model.live.debounce.150ms="discount" >
            </div>
            <div class="col-6">
                <label for="grand_total" class="form-label">Grand Total</label>
                <input type="number" class="form-control" wire:model.live.debounce.150ms="grand_total" value="{{ number_format($grand_total, 2) }}" readonly>
            </div>
            <div class="col-6">
                <label for="paid_amount" class="form-label">Paid Amount</label>
                <input type="number" class="form-control" wire:model.live.debounce.150ms="paid_amount" wire:keydown="calculateTotals">
            </div>
            <div class="col-6">
                <label for="due_amount" class="form-label">Due Amount</label>
                <input type="number" class="form-control" wire:model.live.debounce.150ms="due_amount" value="{{ number_format($due_amount, 2) }}" readonly>
            </div>
        </div>



        <div class="row mt-3">
            <div class="col-12">
                <button type="submit" class="btn btn-primary float-end">Save</button>
            </div>
    </form>
</div>

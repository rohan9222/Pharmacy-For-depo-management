<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\Invoice;
use App\Models\StockList;
use App\Models\SalesMedicine;
use App\Models\SiteSetting;
use App\Models\DiscountValue;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

use Livewire\Component;

class SalesInvoice extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search, $medicines, $customers, $invoice_date, $customer;
    public $highlightedIndex = 0;
    public $stockMedicines = []; // Medicine stock data
    public $sub_total = 0;
    public $total = 0;
    public $vat = 0;
    public $spl_discount = 0;
    public $spl_discount_amount = 0;
    public $discount = 0;
    public $discount_amount = 0;
    public $grand_total = 0;
    public $paid_amount = 0;
    public $due_amount = 0;

    public function render()
    {
        $this->invoice_date = Carbon::now()->toDateString();
        $customers = User::where('role', 'customer');
        if(auth()->user()->role == 'Field Officer'){
            $customers = $customers->where('field_officer_id', auth()->user()->id);
        }
        $this->customers = $customers->get();
        $this->medicines = Medicine::search($this->search)->get();
        return view('livewire.sales-invoice')->layout('layouts.app');
    }

    public function refreshCustomer()
    {
        $this->customers = User::where('role', 'customer')->get();
        flash()->info('Customer list refreshed!');
    }

    public function calculateTotals()
    {
        $this->total = 0;
        $this->sub_total = 0;
        $this->vat = 0;

        // Loop through each medicine and calculate totals
        foreach ($this->stockMedicines as $index => $medicine) {
            // Ensure both quantity and price are numeric (default to 0 if null or not set)
            $quantity = isset($medicine['quantity']) && is_numeric($medicine['quantity']) ? (float) $medicine['quantity'] : 0;
            $price = isset($medicine['price']) && is_numeric($medicine['price']) ? (float) $medicine['price'] : 0;
            $vat = isset($medicine['vat']) && is_numeric($medicine['vat']) ? (float) $medicine['vat'] : 0;

            // Calculate total for the current medicine with VAT
            $medicineTotal = $quantity * $price * (1 + $vat / 100);

            // Format the medicine total to 2 decimal places
            $this->stockMedicines[$index]['sub_total'] = round($quantity * $price, 2);
            // $this->stockMedicines[$index]['vat'] = round($medicineTotal * $vat / 100, 2);
            $this->stockMedicines[$index]['total'] = round($medicineTotal, 2);

            // Add to the overall total
            $this->sub_total += round($quantity * $price, 2);
            $this->total += round($medicineTotal, 2);

            // Calculate VAT for the current medicine
            $this->vat += round($quantity * $price * $vat / 100, 2);
        }

        // Handle null values for spacial discount and paid amount safely, defaulting to 0
        $this->spl_discount = isset($this->spl_discount) && is_numeric($this->spl_discount) ? (float) $this->spl_discount : 0;
        $this->spl_discount_amount = isset($this->spl_discount_amount) && is_numeric($this->spl_discount_amount) ? (float) $this->spl_discount_amount : 0;
        $this->spl_discount_amount = round($this->spl_discount * $this->sub_total / 100, 2);

        $disValue = DiscountValue::where('start_amount', '<=', $this->sub_total)->where('end_amount', '>=', $this->sub_total)->first();
        $this->discount = $disValue ? $disValue->discount : 0;
        $this->discount_amount = round($disValue ? $this->sub_total * $disValue->discount / 100 : 0 , 2);

        // $this->discount = isset($this->discount) && is_numeric($this->discount) ? (float) $this->discount : 0;
        // $this->discount_amount = isset($this->discount_amount) && is_numeric($this->discount_amount) ? (float) $this->discount_amount : 0;
        // $this->discount_amount = $disValue ? $disValue->discount : 0;

        $this->paid_amount = isset($this->paid_amount) && is_numeric($this->paid_amount) ? (float) $this->paid_amount : 0;

        // Calculate grand total and due amount, rounded to 2 decimals and ensuring they aren't negative
        $this->grand_total = round(max($this->total - ($this->spl_discount_amount + $this->discount_amount), 0), 2);
        $this->due_amount = round(max($this->grand_total - $this->paid_amount, 0), 2);
    }

    public function addMedicine($index)
    {
        $medicine = Medicine::find($index);

        if ($medicine) {
            // Check if the medicine already exists in the stockMedicines array
            $existingIndex = collect($this->stockMedicines)->search(fn($item) => $item['medicine_id'] === $medicine->id);

            if ($existingIndex !== false) {
                // If the medicine exists, increase the quantity
                $this->stockMedicines[$existingIndex]['quantity'] += 1;
                $this->stockMedicines[$existingIndex]['total'] = round(
                    $this->stockMedicines[$existingIndex]['quantity'] *
                    $this->stockMedicines[$existingIndex]['price'] *
                    (1 + ($this->stockMedicines[$existingIndex]['vat'] ?? 0) / 100),
                    2
                );
            } else {
                // If the medicine does not exist, add it to the list
                $this->stockMedicines[] = [
                    'medicine_id' => $medicine->id,
                    'medicine_image' => $medicine->image_url ?? null,
                    'medicine_name' => $medicine->name ?? 'Unknown',
                    'category_name' => $medicine->category_name ?? 'Uncategorized',
                    'quantity' => 1, // Start with a quantity of 1
                    'price' => (float) $medicine->price ?? 0.0,
                    'sub_total' => (float) $medicine->price ?? 0.0,
                    'vat' => (float) ($medicine->vat ?? 0), // Default VAT to 0 if not set
                    'total' => round(($medicine->price ?? 0.0) * (1 + ($medicine->vat ?? 0) / 100), 2),
                ];
            }

            // Recalculate totals
            $this->calculateTotals();
        } else {
            session()->flash('error', 'Medicine not found!'); // Flash message if no medicine is found
        }
    }

    public function removeMedicine($index)
    {
        unset($this->stockMedicines[$index]);
        $this->stockMedicines = array_values($this->stockMedicines);
        $this->calculateTotals();
    }

    public function increaseQuantity($index, $quantity = 1)
    {
        $medicineQty = Medicine::find($this->stockMedicines[$index]['medicine_id']);
        if ($medicineQty->quantity < $this->stockMedicines[$index]['quantity'] + $quantity) {
            $this->stockMedicines[$index]['quantity'] = $medicineQty->quantity;
            flash()->error('Not enough stock available!');
            return;
        }
        $this->stockMedicines[$index]['quantity'] += $quantity;
        $this->stockMedicines[$index]['total'] = $this->stockMedicines[$index]['quantity'] * $this->stockMedicines[$index]['price'];
        $this->calculateTotals();
    }

    public function decreaseQuantity($index, $quantity = 1)
    {
        // Check if quantity is less than the requested decrement amount
        if ($this->stockMedicines[$index]['quantity'] <= $quantity) {
            flash()->error('Quantity cannot be less than 1!'); // Flash error
            $this->stockMedicines[$index]['quantity'] = 1; // Reset quantity to 1
            return;
        }

        // Decrease the quantity
        $this->stockMedicines[$index]['quantity'] -= $quantity;
        $this->stockMedicines[$index]['total'] = $this->stockMedicines[$index]['quantity'] * $this->stockMedicines[$index]['price'];

        // Recalculate totals
        $this->calculateTotals();
    }

    public function updatedStockMedicines($value, $key)
    {
        // Extract index and field from the key (e.g., "2.quantity" => $index = 2, $field = 'quantity')
        [$index, $field] = explode('.', $key);

        // Ensure index is numeric
        if (!is_numeric($index)) {
            return;
        }
        if($field === 'quantity' && $value > 1){
            $medicineQty = Medicine::find($this->stockMedicines[$index]['medicine_id']);
            if ($medicineQty->quantity < $this->stockMedicines[$index]['quantity'] + $value) {
                $this->stockMedicines[$index]['quantity'] = $medicineQty->quantity;
                flash()->error('Not enough stock available!');
                return;
            }
        }
        // If quantity is updated and less than 1, reset it to 1
        if ($field === 'quantity' && $value < 1) {
            $this->stockMedicines[$index]['quantity'] = 1;
            flash()->error('Quantity cannot be less than 1!');
        }

        // If price is updated and less than 0, reset it to 0
        if ($field === 'price' && $value < 0) {
            $this->stockMedicines[$index]['price'] = 0;
            flash()->error('Price cannot be negative!');
        }

        // Recalculate totals
        $this->calculateTotals();
    }

    public function updatedSplDiscount()
    {
        $this->calculateTotals();
    }

    public function updatedPaidAmount()
    {
        $this->calculateTotals();
    }


    public function submit()
    {
        $this->validate([
            'invoice_date' => 'required|date',
            'customer' => 'required|exists:users,id',
            'stockMedicines' => 'required|array|min:1',
            'stockMedicines.*.medicine_id' => 'required|exists:medicines,id',
            'stockMedicines.*.quantity' => 'required|numeric|min:1',
            'stockMedicines.*.price' => 'required|numeric|min:0',
            'stockMedicines.*.total' => 'required|numeric|min:0',
        ]);


        DB::beginTransaction();

        try {
            $userRoles = User::where('id', $this->customer)->first();

            $latestInvoiceNo = Invoice::orderByDesc('id')->value('invoice_no');
            $invoice_no = ($latestInvoiceNo) ? ((int) filter_var($latestInvoiceNo, FILTER_SANITIZE_NUMBER_INT) + 1) : 1000;
            // Save logic for invoice and related medicines
            $invoice = Invoice::create([
                'invoice_no' => $invoice_no,
                'invoice_date' => $this->invoice_date,
                'customer' => $this->customer,
                'field_officer' => $userRoles->field_officer_id,
                'sales_manager' => $userRoles->sales_manager_id,
                'manager' => $userRoles->manager_id,
                'sub_total' => $this->sub_total,
                'vat' => $this->vat,
                'discount' => $this->discount,
                'dis_type' => 'percentage',
                'dis_amount' => $this->discount_amount,
                'spl_discount' => $this->spl_discount,
                'spl_dis_type' => 'percentage',
                'spl_dis_amount' => $this->spl_discount_amount,
                'grand_total' => $this->grand_total,
                'paid' => $this->paid_amount,
                'due' => $this->due_amount,
            ]);

            foreach ($this->stockMedicines as $medicine) {
                SalesMedicine::create([
                    'invoice_id' => $invoice->id,
                    'medicine_id' => $medicine['medicine_id'],
                    'initial_quantity' => $medicine['quantity'],
                    'quantity' => $medicine['quantity'],
                    'price' => $medicine['price'],
                    'vat' => $medicine['vat'],
                    'total' => $medicine['total'],
                ]);

                $medicineQuantity = Medicine::find($medicine['medicine_id']);
                $medicineQuantity->quantity -= $medicine['quantity'];
                $medicineQuantity->save();

                $stockList = StockList::where('medicine_id', $medicine['medicine_id'])->where('quantity', '>=', $medicine['quantity'])->orderBy('expiry_date', 'asc')->first();
                if ($stockList) {
                    $stockList->quantity -= $medicine['quantity'];
                    $stockList->save();
                }
            }

            DB::commit();

            flash()->success('Invoice created successfully!');
            $this->reset();

        } catch (\Exception $e) {
            flash()->error('Error: ' . $e->getMessage());
            return;
        }
    }
}

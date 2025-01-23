<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\StockInvoice;
use App\Models\Supplier;
use Carbon\Carbon;
use Livewire\Component;

class StockMedicines extends Component
{
    public $medicine_list, $medicines, $manufacturers, $invoice_no, $invoice_date, $manufacturer, $stockInvoice;
    public $highlightedIndex = 0;
    public $stockMedicines = []; // Medicine stock data
    public $total = 0;
    public $discount = 0;
    public $grand_total = 0;
    public $paid_amount = 0;
    public $due_amount = 0;
    public function mount()
    {
        $this->invoice_date = Carbon::now()->toDateString();
    }

    public function render()
    {
        $this->manufacturers = Supplier::all();
        $latestInvoiceNo = StockInvoice::orderByDesc('id')->value('invoice_no');
        $nextNumber = ($latestInvoiceNo) ? ((int) filter_var($latestInvoiceNo, FILTER_SANITIZE_NUMBER_INT) + 1000) : 1000;
        $this->invoice_no = "STOCKINV" . $nextNumber;

        return view('livewire.stock-medicines')->layout('layouts.app');
    }

    public function updatedMedicineList()
    {
        $this->fetchMedicines();
    }

    public function fetchMedicines()
    {
        if ($this->medicine_list) {
            // Fetch filtered medicines based on search term
            $this->medicines = Medicine::where('medicine_name', 'like', '%' . $this->medicine_list . '%')
                ->take(10)
                ->get();
        } else {
            // Show first 10 medicines when input is empty or focused
            $this->medicines = Medicine::take(10)->get();
        }

        $this->highlightedIndex = 0;
    }

    public function clearMedicines()
    {
        $this->medicines = [];
    }

    public function incrementHighlight()
    {
        if ($this->highlightedIndex < count($this->medicines) - 1) {
            $this->highlightedIndex++;
        }
    }

    public function decrementHighlight()
    {
        if ($this->highlightedIndex > 0) {
            $this->highlightedIndex--;
        }
    }

    public function selectHighlightedMedicine()
    {
        if (isset($this->medicines[$this->highlightedIndex])) {
            $selectedmedicine = $this->medicines[$this->highlightedIndex];
            $this->addMedicine($selectedmedicine->id);
            $this->clearMedicines(); // Optional to clear medicine list after selection
        }
    }


    public function addMedicine($index)
    {
        $medicine = Medicine::find($index);
        if ($medicine) {
            $this->stockMedicines[] = [
                'medicine_id' => $medicine->id,
                'medicine_image' => $medicine->image_url,
                'medicine_name' => $medicine->name,
                'batch' => '',
                'expiry_date' => '',
                'quantity' => 0,
                'price' => $medicine->price,
                'total' => 0.00,
            ];
        }
    }

    // public function calculateTotals()
    // {
    //     $this->total = 0;

    //     // Calculate total for each item and overall total
    //     foreach ($this->stockMedicines as $index => $medicine) {
    //         $medicine['price'] = (float) number_format($medicine['price'] ?? 0, 2, '.', ''); // Ensure price is float
    //         $medicine['total'] = (float) number_format($medicine['quantity'] * $medicine['price'], 2, '.', ''); // Ensure total is float
    //         $this->stockMedicines[$index] = $medicine;
    //         $this->total += $medicine['quantity'] * $medicine['price'];
    //     }

    //     // Ensure discount, paid_amount, and grand_total are floats before calculation
    //     $this->total = (float) $this->total;
    //     $this->discount = (float) $this->discount;
    //     $this->grand_total = (float) ($this->total - $this->discount);
    //     $this->paid_amount = (float) $this->paid_amount;
    //     $this->due_amount = (float) ($this->grand_total - $this->paid_amount);
    // }

    // public function calculateTotals()
    // {
    //     $this->total = 0;

    //     // Calculate total for each item and overall total
    //     foreach ($this->stockMedicines as $index => $medicine) {
    //         $quantity = (float) number_format($medicine['quantity'] ?? 0, 2, '.', ''); // Ensure quantity is a float
    //         $price = (float) number_format($medicine['price'] ?? 0, 2, '.', ''); // Ensure price is a float

    //         // Calculate and update the total for each medicine
    //         $medicine['total'] = $quantity * $price;
    //         $this->stockMedicines[$index] = $medicine;

    //         // Add to the overall total
    //         $this->total += $medicine['total'];
    //     }

    //     // Convert other fields to float and handle null values
    //     $this->discount = (float) ($this->discount ?? 0);
    //     $this->paid_amount = (float) ($this->paid_amount ?? 0);

    //     // Calculate grand total and due amount
    //     $this->grand_total = $this->total - $this->discount;
    //     $this->due_amount = $this->grand_total - $this->paid_amount;
    // }

    public function calculateTotals()
    {
        $this->total = 0;

        // Loop through each medicine and calculate totals
        foreach ($this->stockMedicines as $index => $medicine) {
            // Ensure both quantity and price are numeric (default to 0 if null or not set)
            $quantity = isset($medicine['quantity']) ? (float) $medicine['quantity'] : 0;
            $price = isset($medicine['price']) ? (float) $medicine['price'] : 0;

            // Calculate total for the current medicine
            $medicineTotal = $quantity * $price;

            // Format the medicine total to 3 decimal places
            $this->stockMedicines[$index]['total'] = round($medicineTotal, 3);

            // Add to the overall total
            $this->total += round($medicineTotal, 3); // Ensure total is rounded to 3 decimal places
        }

        // Handle null values for discount and paid amount, and format to 3 decimal places
        $this->discount = (float) ($this->discount ?? 0);
        $this->paid_amount = (float) ($this->paid_amount ?? 0);

        // Calculate grand total and due amount, also rounded to 3 decimals
        $this->grand_total = round(max($this->total - $this->discount, 0), 3); // Ensure grand total is not negative
        $this->due_amount = round(max($this->grand_total - $this->paid_amount, 0), 3); // Ensure due amount is not negative
    }

    public function updatedStockMedicines()
    {
        $this->calculateTotals();
    }

    public function updatedDiscount()
    {
        $this->calculateTotals();
    }

    public function updatedPaidAmount()
    {
        $this->calculateTotals();
    }

    public function removeMedicine($index)
    {
        unset($this->stockMedicines[$index]);
        $this->stockMedicines = array_values($this->stockMedicines); // Re-index array
        $this->calculateTotals();
    }

    public function submit()
    {
        $this->validate([
            'invoice_date' => 'required|date',
            'manufacturer' => 'required|exists:suppliers,id',
            'stockMedicines' => 'required|array|min:1',
            'stockMedicines.*.medicine_id' => 'required|exists:medicines,id',
            'stockMedicines.*.batch' => 'required|string|max:50',
            'stockMedicines.*.expiry_date' => 'required|date',
            'stockMedicines.*.quantity' => 'required|integer|min:1',
            'stockMedicines.*.price' => 'required|numeric|min:0',
            'stockMedicines.*.total' => 'required|numeric|min:0',
        ]);

        // Save logic for StockInvoice and related medicines
        $stockInvoice = StockInvoice::create([
            'invoice_no' => $this->invoice_no,
            'invoice_date' => $this->invoice_date,
            'supplier_id' => $this->manufacturer,
            'sub_total' => $this->total,
            'discount' => $this->discount,
            'dis_type' => 'fixed',
            'dis_amount' => $this->discount,
            'total' => $this->grand_total,
            'paid' => $this->paid_amount,
            'due' => $this->due_amount,
        ]);

        foreach ($this->stockMedicines as $medicine) {
            dd($medicine);
            $stockInvoice->medicines()->create([
                'medicine_id' => $medicine['medicine_id'],
                'batch' => $medicine['batch'],
                'expiry_date' => $medicine['expiry_date'],
                'quantity' => $medicine['quantity'],
                'price' => $medicine['price'],
                'total' => $medicine['total'],
            ]);
        }

        session()->flash('message', 'Stock invoice created successfully.');
        $this->reset(['medicine_list', 'stockMedicines', 'manufacturer']);
    }

}

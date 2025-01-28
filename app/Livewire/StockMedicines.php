<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\StockInvoice;
use App\Models\StockList;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

use Livewire\Component;

class StockMedicines extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $medicine_list, $medicines, $manufacturers, $invoice_no, $invoice_date, $manufacturer;
    public $highlightedIndex = 0;
    public $stockMedicines = []; // Medicine stock data
    public $total = 0;
    public $discount = 0;
    public $grand_total = 0;
    public $paid_amount = 0;
    public $due_amount = 0;

    public function render()
    {
        $this->invoice_date = Carbon::now()->toDateString();
        $this->manufacturers = Supplier::all();
        $latestInvoiceNo = StockInvoice::orderByDesc('id')->value('invoice_no');
        $nextNumber = ($latestInvoiceNo) ? ((int) filter_var($latestInvoiceNo, FILTER_SANITIZE_NUMBER_INT) + 1) : 1000;
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
            $this->medicines = Medicine::where('name', 'like', '%' . $this->medicine_list . '%')
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
                'quantity' => 1,
                'buy_price' => $medicine->supplier_price,
                'total' => 0.00,
            ];
        }
    }

    public function calculateTotals()
    {
        $this->total = 0;

        // Loop through each medicine and calculate totals
        foreach ($this->stockMedicines as $index => $medicine) {
            // Ensure both quantity and buy_price are numeric (default to 0 if null or not set)
            $quantity = isset($medicine['quantity']) ? (float) $medicine['quantity'] : 0;
            $buy_price = isset($medicine['buy_price']) ? (float) $medicine['buy_price'] : 0;

            // Calculate total for the current medicine
            $medicineTotal = $quantity * $buy_price;

            // Format the medicine total to 2 decimal places
            $this->stockMedicines[$index]['total'] = round($medicineTotal, 2);

            // Add to the overall total
            $this->total += round($medicineTotal, 2); // Ensure total is rounded to 2 decimal places
        }

        // Handle null values for discount and paid amount, and format to 2 decimal places
        $this->discount = (float) ($this->discount ?? 0);
        $this->paid_amount = (float) ($this->paid_amount ?? 0);

        // Calculate grand total and due amount, also rounded to 2 decimals
        $this->grand_total = round(max($this->total - $this->discount, 0), 2); // Ensure grand total is not negative
        $this->due_amount = round(max($this->grand_total - $this->paid_amount, 0), 2); // Ensure due amount is not negative
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
            'stockMedicines.*.quantity' => 'required|numeric|min:1',
            'stockMedicines.*.buy_price' => 'required|numeric|min:0',
            'stockMedicines.*.total' => 'required|numeric|min:0',
        ]);


        DB::beginTransaction();

        try {
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
                StockList::create([
                    'medicine_id' => $medicine['medicine_id'],
                    'stock_invoice_id' => $stockInvoice->id,
                    'batch_number' => $medicine['batch'],
                    'expiry_date' => $medicine['expiry_date'],
                    'initial_quantity' => $medicine['quantity'],
                    'quantity' => $medicine['quantity'],
                    'buy_price' => $medicine['buy_price'],
                    'total' => $medicine['total'],
                ]);

                $medicineQuantity = Medicine::find($medicine['medicine_id']);
                $medicineQuantity->quantity += $medicine['quantity'];
                $medicineQuantity->save();
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

<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\Invoice;
use App\Models\TargetReport;
use App\Models\StockList;
use App\Models\SalesMedicine;
use App\Models\Category;
use App\Models\SiteSetting;
use App\Models\PaymentHistory;
use App\Models\DiscountValue;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

use Livewire\Component;

class SalesInvoice extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search, $medicines, $customers, $invoice_date, $customer,$category,$category_lists, $tses, $invoice_number, $tse,$name,$email,$mobile,$address,$balance,$tse_team,$route,$customer_category;
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

    public function mount()
    {
        if(!(auth()->user()->hasRole('Super Admin') || auth()->user()->can('invoice'))) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function render()
    {
        $tses = User::select('id', 'name')->role('Territory Sales Executive');
        if (auth()->user()->hasRole('Manager')) {
            $tses = $tses->where('manager_id', auth()->user()->id);
        } elseif (auth()->user()->hasRole('Zonal Sales Executive')) {
            $tses = $tses->where('zse_id', auth()->user()->id);
        } elseif (auth()->user()->hasRole('Territory Sales Executive')) {
            $tses = $tses->where('id', auth()->user()->id);
        }
        $this->tses = $tses->get();
        $this->category_lists = Category::select('name')->get();
        $this->invoice_date = Carbon::now()->toDateString();
        $customers = User::where('role', 'customer');

        if (auth()->user()->role == 'Territory Sales Executive') {
            $customers = $customers->where('tse_id', auth()->user()->id);
        }

        $this->customers = $customers->get();

        if($this->category) {
            $this->medicines = Medicine::search($this->search)->where('category_name', $this->category)->get();
        } else {
            $this->medicines = Medicine::search($this->search)->get();
        }
        return view('livewire.sales-invoice')->layout('layouts.app');
    }

    public function medicinesCategory($category) {
        $this->category = $category;
        $this->dispatch('slickCategory');
    }

    public function customerSubmit()
    {
        $this->validate( [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255',
                function ($attribute, $value, $fail) {
                    $users = User::where('email', $value)->first();
                    if ($users) {
                        $fail('The email address is already associated with a '.ucfirst($users->role).' list. Please use a different email address');
                    }
                }
            ],
            'mobile' => 'required|numeric|digits:11',
            'address' => 'required|string|max:255',
            'balance' => 'nullable|numeric',
            'route' => 'required|string|max:255',
            'customer_category' => 'required|string|max:255',
            'tse_team' => ['required','exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value); // Retrieve the user once to avoid multiple queries
                    if (!$user || (!$user->zse_id && !$user->manager_id)) {
                        $fail('This Territory Sales Executive team does not exist or not assigned to any manager and Zonal Sales Executive.');
                    }
                }
            ],
        ]);

        try {
            $latestNo = User::orderByDesc('user_id')->value('user_id');
            $user_id = ($latestNo) ? ((int) filter_var($latestNo, FILTER_SANITIZE_NUMBER_INT) + 1) : 010500;
            $newCustomer = User::Create(
                [
                    'user_id' => $user_id,
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->email.$this->mobile.$this->name),
                    'mobile' => $this->mobile,
                    'address' => $this->address,
                    'balance' => $this->balance ?? 0.00,
                    'role' => 'Customer',
                    'route' => $this->route,
                    'category' => $this->customer_category,
                    'tse_id' => $this->tse_team,
                    'zse_id' => User::where('id', $this->tse_team)->first()->zse_id,
                    'manager_id' => User::where('id', $this->tse_team)->first()->manager_id,
                ]
            );

            $this->reset();
            flash()->success('Customer added successfully!');
        } catch (\Exception $e) {
            flash()->error('Error: ' . $e->getMessage());
            return;
        }
    }

    public function refreshCustomer()
    {
        $this->customers = User::where('role', 'customer')->get();
        flash()->info('Customer list refreshed!');
    }
    public function updatedCustomer() {
        // This method is triggered when the customer is updated
        $this->calculateTotals();
    }
    public function calculateTotals()
    {
        // Initialize totals
        $this->total = 0;
        $this->sub_total = 0;
        $this->vat = 0;

        foreach ($this->stockMedicines as $index => $medicine) {
            // Ensure numeric values
            $quantity = isset($medicine['quantity']) && is_numeric($medicine['quantity']) ? (float) $medicine['quantity'] : 0;
            $price = isset($medicine['price']) && is_numeric($medicine['price']) ? (float) $medicine['price'] : 0;
            $vat = isset($medicine['vat']) && is_numeric($medicine['vat']) ? (float) $medicine['vat'] : 0;

            // Calculate sub-total and VAT
            $subTotal = round($quantity * $price, 2);
            $vatAmount = round($subTotal * $vat / 100, 2);
            $medicineTotal = round($subTotal + $vatAmount, 2);

            // Store updated values
            $this->stockMedicines[$index]['sub_total'] = $subTotal;
            $this->stockMedicines[$index]['vat_amount'] = $vatAmount;
            $this->stockMedicines[$index]['total'] = $medicineTotal;

            // Add to overall totals
            $this->sub_total += $subTotal;
            $this->vat += $vatAmount;
            $this->total += $medicineTotal;
        }

        // Handle special discount safely
        $this->spl_discount = (float) ($this->spl_discount ?? 0);
        $this->spl_discount_amount = round($this->sub_total * $this->spl_discount / 100, 2);

        if($this->customer){
            // Fetch applicable discount
            $disValue = DiscountValue::where('start_amount', '<=', $this->sub_total)
                                    ->where('end_amount', '>=', $this->sub_total)
                                    ->where('discount_type', User::find($this->customer)->category)
                                    ->first();

            $this->discount = $disValue ? $disValue->discount : 0;
            $this->discount_amount = round($this->sub_total * $this->discount / 100, 2);
        }else{
            $this->discount = 0;
            $this->discount_amount = 0;
        }

        // Handle paid amount safely
        $this->paid_amount = (float) ($this->paid_amount ?? 0);

        // Calculate grand total and due amount, ensuring they are not negative
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
        // Get the medicine details from the database
        $medicine = Medicine::find($this->stockMedicines[$index]['medicine_id']);

        if (!$medicine) {
            return;
            flash()->error('Medicine not found!');
        }

        $newQuantity = $this->stockMedicines[$index]['quantity'] + $quantity;

        // Ensure the requested quantity does not exceed stock
        if ($newQuantity > $medicine->quantity) {
            $this->stockMedicines[$index]['quantity'] = $medicine->quantity;
            flash()->error('Not enough stock available!');
        } else {
            $this->stockMedicines[$index]['quantity'] = $newQuantity;
        }

        // Update the total price
        $this->stockMedicines[$index]['total'] = $this->stockMedicines[$index]['quantity'] * $this->stockMedicines[$index]['price'];

        // Recalculate totals
        $this->calculateTotals();
    }

    public function decreaseQuantity($index, $quantity = 1)
    {
        // Ensure stock is not reduced below 1
        $newQuantity = max(1, $this->stockMedicines[$index]['quantity'] - $quantity);

        if ($newQuantity < $this->stockMedicines[$index]['quantity']) {
            $this->stockMedicines[$index]['quantity'] = $newQuantity;
        } else {
            flash()->error('Quantity cannot be less than 1!');
        }

        // Update the total price
        $this->stockMedicines[$index]['total'] = $this->stockMedicines[$index]['quantity'] * $this->stockMedicines[$index]['price'];

        // Recalculate totals
        $this->calculateTotals();
    }


    public function updatedStockMedicines($value, $key)
    {
        [$index, $field] = explode('.', $key);

        if (!is_numeric($index)) {
            return;
        }

        if ($field === 'quantity') {
            $medicine = Medicine::find($this->stockMedicines[$index]['medicine_id']);

            // Ensure medicine exists
            if (!$medicine) {
                flash()->error('Invalid medicine selected!');
                return;
            }

            // Ensure quantity is not negative or zero
            if ($value < 1) {
                $this->stockMedicines[$index]['quantity'] = 1;
                flash()->error('Quantity cannot be less than 1!');
            }

            // Ensure stock is sufficient
            if ((float)$value > (float)$medicine->quantity) {
                $this->stockMedicines[$index]['quantity'] = $medicine->quantity;
                flash()->error('Not enough stock available!');
            }
        }

        if ($field === 'price' && $value < 0) {
            $this->stockMedicines[$index]['price'] = 0;
            flash()->error('Price cannot be negative!');
        }

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
                'customer_id' => $this->customer,
                'tse_id' => $userRoles->tse_id,
                'zse_id' => $userRoles->zse_id,
                'manager_id' => $userRoles->manager_id,
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

            TargetReport::where('user_id', $userRoles->tse_id)->where('target_month', date('F'))->where('target_year', date('Y'))->increment('sales_target_achieve', $this->sub_total);
            TargetReport::where('user_id', $userRoles->zse_id)->where('target_month', date('F'))->where('target_year', date('Y'))->increment('sales_target_achieve', $this->sub_total);
            TargetReport::where('user_id', $userRoles->manager_id)->where('target_month', date('F'))->where('target_year', date('Y'))->increment('sales_target_achieve', $this->sub_total);

            if($this->paid_amount > 0){
                PaymentHistory::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $this->paid_amount,
                    'date' => $this->invoice_date
                ]);
            }

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

                // $stockList = StockList::where('medicine_id', $medicine['medicine_id'])->where('quantity', '>=', $medicine['quantity'])->orderBy('expiry_date', 'desc')->first();
                // if ($stockList) {
                //     $stockList->quantity -= $medicine['quantity'];
                //     $stockList->save();
                // }
                $stockList = StockList::where('medicine_id', $medicine['medicine_id'])
                    ->where('quantity', '>', 0) // Only consider stocks with positive quantity
                    ->orderBy('expiry_date', 'asc')
                    ->get();
                $remainingQuantity = $medicine['quantity'];
                foreach ($stockList as $stock) {
                    if ($remainingQuantity <= 0) {
                        break;
                    }
                    $quantityToReduce = min($remainingQuantity, $stock->quantity);
                    $stock->quantity -= $quantityToReduce;
                    $stock->save();
                    $remainingQuantity -= $quantityToReduce;
                }
            }

            DB::commit();

            $this->reset();
            return redirect()->route('invoice.print', $invoice->invoice_no);
            flash()->success('Invoice created successfully!');

        } catch (\Exception $e) {
            flash()->error('Error: ' . $e->getMessage());
            return;
        }
    }
}

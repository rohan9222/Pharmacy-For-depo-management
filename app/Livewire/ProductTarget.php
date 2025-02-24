<?php

namespace App\Livewire;

use App\Models\{User, Medicine, TargetReport,Category,SiteSetting};

use Illuminate\Support\Facades\DB;

use Livewire\Component;

class ProductTarget extends Component
{
    public $search, $medicines, $users, $customer, $category, $category_lists;
    public $stockMedicines = []; 

    
    public $invoice_date, $invoice_number, $tse,$name,$email,$mobile,$address,$balance,$tse_team,$route;
    public $highlightedIndex = 0;
    public $total = 0;
    public $spl_discount = 0;
    public $spl_discount_amount = 0;
    public $discount = 0;
    public $discount_amount = 0;
    public $grand_total = 0;
    public $paid_amount = 0;
    public $due_amount = 0;

    public function mount()
    {
        // if (!auth()->user()->hasPermissionTo('product-target-manage')) {
        //     abort(403);
        // }
        if(!auth()->user()->hasRole('Super Admin') && !auth()->user()->hasRole('Depo Incharge') && !auth()->user()->hasRole('Manager') && !auth()->user()->hasRole('Zonal Sales Executive') ){
            abort(403);
        }
    }

    public function render()
    {
        if(auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Depo Incharge')){
            $this->users = User::where('role', 'Manager')->get();
        }elseif(auth()->user()->hasRole('Manager')){
            $this->users = User::where('role', 'Zonal Sales Executive')->where('manager_id', auth()->user()->id)->get();
        }elseif(auth()->user()->hasRole('Zonal Sales Executive')){
            $this->users = User::where('role', 'Territory Sales Executive')->where('zse_id', auth()->user()->id)->get();
        }
        $this->category_lists = Category::select('name')->get();
        
        if($this->category) {
            $this->medicines = Medicine::search($this->search)->where('category_name', $this->category)->get();
        } else {
            $this->medicines = Medicine::search($this->search)->get();
        }

        return view('livewire.product-target')->layout('layouts.app');
    }

    public function medicinesCategory($category) {
        $this->category = $category;
        $this->dispatch('slickCategory');
    }

    
    public function calculateTotals()
    {
        // Initialize totals
        $this->total = 0;

        foreach ($this->stockMedicines as $index => $medicine) {
            // Ensure numeric values
            $quantity = isset($medicine['quantity']) && is_numeric($medicine['quantity']) ? (float) $medicine['quantity'] : 0;
            $price = isset($medicine['price']) && is_numeric($medicine['price']) ? (float) $medicine['price'] : 0;

            // Calculate sub-total and VAT
            $subTotal = round($quantity * $price, 2);
            $medicineTotal = round($subTotal, 2);

            // Store updated values
            $this->stockMedicines[$index]['total'] = $medicineTotal;

            // Add to overall totals
            $this->total += $medicineTotal;
        }
    }

    public function updatedCustomer(){
        if(!$this->customer){
            flash()->warning('Please select a user first!');
            $this->stockMedicines = [];
            $this->total = 0;
        }else{
            $user = User::find($this->customer);
            $this->stockMedicines = json_decode($user->product_target_data, true) ?? [];
            $this->total = $user->product_target;
        }
    }

    public function addMedicine($index)
    {
        if(!$this->customer){
            flash()->warning('Please select a user first!');
        }
        
        $this->validate([
            'customer' => 'required|exists:users,id',
        ]);
        
        $medicine = Medicine::find($index);

        if ($medicine) {
            // Check if the medicine already exists in the stockMedicines array
            $existingIndex = collect($this->stockMedicines)->search(fn($item) => $item['medicine_id'] === $medicine->id);

            if ($existingIndex !== false) {
                // If the medicine exists, increase the quantity
                $this->stockMedicines[$existingIndex]['quantity'] += 1;
                $this->stockMedicines[$existingIndex]['total'] = round($this->stockMedicines[$existingIndex]['quantity'] * $this->stockMedicines[$existingIndex]['price'] ?? 0.0, 2);
            } else {
                // If the medicine does not exist, add it to the list
                $this->stockMedicines[] = [
                    'medicine_id' => $medicine->id,
                    'medicine_name' => $medicine->name ?? 'Unknown',
                    'category_name' => $medicine->category_name ?? 'Uncategorized',
                    'quantity' => 1, // Start with a quantity of 1
                    'price' => (float) $medicine->price ?? 0.0,
                    'total' => round(($medicine->price ?? 0.0), 2),
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

    
    public function targetSubmit()
    {
        $this->validate([
            'customer' => 'required|exists:users,id',
            'stockMedicines' => 'required|array|min:1',
            'stockMedicines.*.medicine_id' => 'required|exists:medicines,id',
            'stockMedicines.*.quantity' => 'required|numeric|min:1',
            'stockMedicines.*.price' => 'required|numeric|min:0',
            'stockMedicines.*.total' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // dd(json_encode($this->stockMedicines));
            $userRoles = User::where('id', $this->customer)->update([
                'product_target_data' => json_encode($this->stockMedicines),
                'product_target' => $this->total,
            ]);


            TargetReport::where('user_id', $this->customer)->where('target_month', date('F'))->where('target_year', date('Y'))->update([
                'product_target' => $this->total,
                'product_target_data' => json_encode($this->stockMedicines),
            ]);

            DB::commit();

            $this->reset();
            $this->stockMedicines = [];
            $this->total = 0;
            flash()->success('Product target updated successfully!');
        } catch (\Exception $e) {
            flash()->error('Error: ' . $e->getMessage());
            return;
        }
    }

}

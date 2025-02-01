<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\Invoice;
use App\Models\StockList;
use App\Models\SiteSetting;
use App\Models\SalesMedicine;
use App\Models\ReturnMedicine;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

use Livewire\Component;

class InvoiceHistory extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search, $invoice_data, $site_settings, $return_date, $return_quantity, $return_medicine;

    public function mount(){
        if(auth()->user()->hasRole('Super Admin')) {
            return true;
        }

        if (!auth()->user()->hasPermissionTo('view-invoice')) {
            abort(403, 'Unauthorized action.');
        }

        return true;
    }

    public function render()
    {
        $this->site_settings = SiteSetting::first();
        $invoices = Invoice::search($this->search)->paginate(15);
        return view('livewire.invoice-history',[
            'invoices' => $invoices
        ])->layout('layouts.app');
    }

    public function invoiceView($invoiceID = null)
    {
        if($invoiceID) {
            $this->invoice_data = Invoice::with('salesMedicines', 'customer')->find($invoiceID);
        }else {
            $this->invoice_data = null;
        }
    }

    public function returnMedicine($invoiceID = null){
        if($invoiceID) {
            $this->invoice_data = Invoice::with('salesMedicines')->find($invoiceID);
            $this->return_date = Carbon::now()->toDateString();
        }else {
            $this->invoice_data = null;
        }
    }

    public function returnSubmit(){
        $this->validate([
            'return_medicine' => 'required|numeric|exists:sales_medicines,id',
            'return_quantity' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) {
                    $medicine = \App\Models\SalesMedicine::find($this->return_medicine); // Fetch the medicine record
                    if ($medicine && $value > $medicine->quantity) {
                        $fail('The return quantity cannot be greater than the available quantity.');
                    }
                },
            ],
            'return_date' => 'required|date|before_or_equal:today',
        ]);

        DB::beginTransaction();
        try{
            $sales_medicine = SalesMedicine::find($this->return_medicine);
            $sales_medicine->quantity = $sales_medicine->quantity - $this->return_quantity;
            $sales_medicine->save();

            ReturnMedicine::create([
                'invoice_id' => $sales_medicine->invoice_id,
                'medicine_id' => $sales_medicine->medicine_id,
                'sales_medicine_id' => $sales_medicine->id,
                'quantity' => $this->return_quantity,
                'price' => $sales_medicine->price,
                'total' => $this->return_quantity * $sales_medicine->price,
                'return_date' => $this->return_date,
            ])->save();

            Medicine::where('id', $sales_medicine->medicine_id)->increment('quantity', $this->return_quantity);

            $stockList = StockList::where('medicine_id', $sales_medicine->medicine_id)
                ->where('initial_quantity', '>', 0)
                ->orderBy('expiry_date', 'asc')
                ->get();

            $remainingQuantity = $this->return_quantity;

            foreach ($stockList as $stock) {
                if ($remainingQuantity <= 0) {
                    break;
                }
                $quantityToIncrease = min($remainingQuantity, $stock->initial_quantity - $stock->quantity);

                $stock->quantity += $quantityToIncrease;
                $stock->save();
                $remainingQuantity -= $quantityToIncrease;
            }

            flash()->success('Return Medicine Successfully!');
            DB::commit();
        }catch(\Exception $e){
            flash()->error($e->getMessage());
        }
    }

}

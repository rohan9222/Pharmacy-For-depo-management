<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\StockInvoice;
use App\Models\StockList;
use App\Models\StockReturnList;
use App\Models\SiteSetting;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use Livewire\Component;

class StockInvoiceList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search, $stock_invoice_data,$return_invoice_data,$return_date,$return_medicine,$return_quantity;

    public function render()
    {
        $site_settings = SiteSetting::first();
        $stock_invoices = StockInvoice::latest()->paginate(15);
        return view('livewire.stock-invoice-list', [
            'stock_invoices' => $stock_invoices,
            'site_settings' => $site_settings
        ])->layout('layouts.app');
    }

    public function invoiceView($invoiceID = null)
    {
        if($invoiceID) {
            $this->stock_invoice_data = StockInvoice::with('stockLists', 'supplier')->find($invoiceID);
        }else {
            $this->stock_invoice_data = null;
        }
    }
    
    public function returnStockMedicine($invoiceID = null){
        if($invoiceID) {
            $this->return_invoice_data = StockInvoice::with('stockLists')->find($invoiceID);
            $this->return_date = Carbon::now()->toDateString();
        }else {
            $this->return_invoice_data = null;
        }
    }

    public function returnSubmit(){
        $this->validate([
            'return_medicine' => 'required|numeric|exists:stock_lists,id',
            'return_quantity' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) {
                    $medicine = \App\Models\StockList::find($this->return_medicine); // Fetch the medicine record
                    if ($medicine && $value > $medicine->quantity) {
                        $fail('The return quantity cannot be greater than the Stock Quantity.');
                    }
                },
            ],
            'return_date' => 'required|date|before_or_equal:today',
        ]);

        DB::beginTransaction();
        try{
            $stock_medicine = StockList::find($this->return_medicine);
            $stock_medicine->quantity = $stock_medicine->quantity - $this->return_quantity;
            $stock_medicine->save();

            StockReturnList::create([
                'medicine_id' => $stock_medicine->medicine_id,
                'stock_invoice_id' => $stock_medicine->stock_invoice_id,
                'stock_list_id' => $stock_medicine->id,
                'quantity' => $this->return_quantity,
                'buy_price' => $stock_medicine->buy_price,
                'total' => $this->return_quantity * $stock_medicine->buy_price,
                'return_date' => $this->return_date,
            ])->save();

            Medicine::find($stock_medicine->medicine_id)->decrement('quantity', $this->return_quantity);

            flash()->success('Return Medicine Successfully!');
            DB::commit();
            
            $this->return_medicine = '';
            $this->return_quantity = '';
        }catch(\Exception $e){
            flash()->error($e->getMessage());
        }
    }

}

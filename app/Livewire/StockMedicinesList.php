<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\StockInvoice;
use App\Models\StockList;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

use Livewire\Component;

class StockMedicinesList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search, $stock_invoice_data;

    public function render()
    {
        $stock_invoices = StockInvoice::paginate(15);;
        $stock_lists = StockList::search($this->search)->orderByDesc('expiry_date')->paginate(15);
        return view('livewire.stock-medicines-list', [
            'stock_lists' => $stock_lists,
            'stock_invoices' => $stock_invoices,
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
}

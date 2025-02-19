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
        $stock_lists = StockList::search($this->search)->latest()->orderBy('expiry_date')->paginate(15);
        return view('livewire.stock-medicines-list', [
            'stock_lists' => $stock_lists,
        ])->layout('layouts.app');
    }
}

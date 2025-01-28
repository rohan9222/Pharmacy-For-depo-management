<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\Invoice;
use App\Models\StockList;
use App\Models\SalesMedicine;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

use Livewire\Component;

class InvoiceHistory extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search, $invoice_data;

    public function render()
    {
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
}

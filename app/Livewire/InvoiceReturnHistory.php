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

class InvoiceReturnHistory extends Component
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
        $invoices = ReturnMedicine::search($this->search)->with('medicine', 'invoiceData')->paginate(15);
        $this->site_settings = SiteSetting::first();
        return view('livewire.invoice-return-history',[
            'invoices' => $invoices
        ])->layout('layouts.app');
    }

    public function invoiceView($invoiceID = null)
    {
        if($invoiceID) {
            $this->invoice_data = Invoice::with('salesMedicines','salesReturnMedicines', 'customer')->find($invoiceID);
        }else {
            $this->invoice_data = null;
        }
    }


}

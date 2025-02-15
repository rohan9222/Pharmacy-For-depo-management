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

use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

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
        $this->site_settings = SiteSetting::first();
        return view('livewire.invoice-return-history')->layout('layouts.app');
    }

    public function invoiceReturnList(Request $request)
    {
        if ($request->ajax()) {

            $data = ReturnMedicine::with(['invoiceData:id,invoice_no', 'medicine:id,name']);
            if ($request->manager_id != null) {
                $Minvoice = Invoice::where('manager_id', $request->manager_id)->pluck('id');
                $data = $data->whereIn('invoice_id', $Minvoice);
            }

            if ($request->sales_manager_id != null) {
                $SMinvoice = Invoice::where('sales_manager_id', $request->sales_manager_id)->pluck('id');
                $data = $data->whereIn('invoice_id', $SMinvoice);
            }

            if ($request->field_officer_id != null) {
                $Finvoice = Invoice::where('field_officer_id', $request->field_officer_id)->pluck('id');
                $data = $data->whereIn('invoice_id', $Finvoice);
            }

            if ($request->customer_id != null) {
                $Cinvoice = Invoice::where('customer_id', $request->customer_id)->pluck('id');
                $data = $data->whereIn('invoice_id', $Cinvoice);
            }

            if ($request->start_date && $request->end_date) {
                $data = $data->whereBetween('return_date', [Carbon::parse($request->start_date)->format('Y-m-d'), Carbon::parse($request->end_date)->format('Y-m-d')]);
            }
            return DataTables::of($data->get())
                ->addIndexColumn() // Auto Increment Column
                ->addColumn('invoice_no', function ($row) {
                    return $row->invoiceData->invoice_no;
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('invoice.return.print', $row->invoiceData->invoice_no) . '" target="_blank" title="View Invoice" class="btn btn-sm btn-warning"><i class="bi bi-eye"></i></a>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
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

<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;
class DueInvoiceList extends Component
{
    public function render()
    {
        return view('livewire.due-invoice-list')->layout('layouts.app');
    }

    public function invoiceDueList(Request $request)
    {
        if ($request->ajax()) {
            $data = Invoice::with(['customer:id,name,user_id,mobile', 'deliveredBy:id,name', 'salesReturnMedicines']);

            // Apply filtering conditions
            if ($request->manager_id != null) {
                $data = $data->where('manager_id', $request->manager_id);
            }

            if ($request->zse_id != null) {
                $data = $data->where('zse_id', $request->zse_id);
            }

            if ($request->tse_id != null) {
                $data = $data->where('tse_id', $request->tse_id);
            }

            if ($request->customer_id != null) {
                $data = $data->where('customer_id', $request->customer_id);
            }

            if ($request->start_date && $request->end_date) {
                $data = $data->whereBetween('invoice_date', [Carbon::parse($request->start_date)->format('Y-m-d'), Carbon::parse($request->end_date)->format('Y-m-d')]);
            }

            // Return data for DataTables
            return DataTables::of($data->get())
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '';
                })
                ->editColumn('deliveredBy.name', function ($row) {
                    return $row->deliveredBy ? $row->deliveredBy->name : 'N/A'; // Avoids null errors
                })
                ->addColumn('returnAmount', function ($row) {
                    return round($row->salesReturnMedicines->sum('total'),2);
                })
                ->editColumn('due', function ($row) {
                    $sumReturnTotal = $row->salesReturnMedicines->sum('total');
                    $afterReturnDue = $row->grand_total - $sumReturnTotal;
                    $discount_data = json_decode($row->discount_data);

                    if ($discount_data != null && $discount_data->start_amount <= $afterReturnDue && $afterReturnDue <= $discount_data->end_amount) {
                        $afterReturnDue = $afterReturnDue - $row->paid;
                    } elseif ($discount_data != null && $discount_data->start_amount > $afterReturnDue) {
                        $afterReturnDue += $row->dis_amount - $row->paid; 
                    }else{
                        $afterReturnDue = $afterReturnDue - $row->paid;
                    }
                    return $row->salesReturnMedicines->sum('total') > $row->due ? 0 : round($afterReturnDue,2);
                })
                ->rawColumns(['action']) // Ensure HTML buttons render correctly
                ->make(true);
        }
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\DiscountValue;
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
                $data = $data->whereBetween('invoice_date', [Carbon::parse($request->start_date)->format('Y-m-d 00:00:00'), Carbon::parse($request->end_date)->format('Y-m-d 23:59:59')]);
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
                    $afterReturnPrice = $row->sub_total - $row->salesReturnMedicines->sum('total_price');
                    $afterReturnVat = $row->vat - $row->salesReturnMedicines->sum('vat');
                    $sumReturnTotal = $row->salesReturnMedicines->sum('total');

                    $discount_data = json_decode($row->discount_data);
                    $newDiscount = DiscountValue::where('discount_type', 'General')
                        ->where('start_amount', '<=', $afterReturnPrice)
                        ->where('end_amount', '>=', $afterReturnPrice)
                        ->pluck('discount')
                        ->first();

                    if (!empty($discount_data) && $discount_data->start_amount <= $afterReturnPrice && $afterReturnPrice <= $discount_data->end_amount) {
                        $afterReturnDis = ($afterReturnPrice * $row->discount) / 100;
                        $afterReturnDue = ($afterReturnPrice - $afterReturnDis) + $afterReturnVat; 
                    } elseif ($newDiscount !== null) {
                        $afterReturnDue = ($afterReturnPrice + $afterReturnVat) - ($afterReturnPrice * $newDiscount / 100);
                    } else {
                        $afterReturnDue = $afterReturnPrice + $afterReturnVat;
                    }
            
                    // Ensure afterReturnDue is never negative
                    $totalDue = round(max($afterReturnDue - $row->paid, 0), 2);

                    return $totalDue;
                })
                ->rawColumns(['action']) // Ensure HTML buttons render correctly
                ->make(true);
        }
    }
}

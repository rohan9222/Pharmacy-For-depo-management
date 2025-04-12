<?php

namespace App\Livewire;


use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;

use Livewire\Component;

class CollectionReport extends Component
{
    public function render()
    {
        return view('livewire.collection-report')->layout('layouts.app');
    }


    public function invoiceCollectionList(Request $request)
    {
        if ($request->ajax()) {
            $query = Invoice::with(['customer:id,name,user_id,mobile', 'deliveredBy:id,name', 'paymentHistory', 'salesReturnMedicines'])->where('paid', '>', 0);

            // Apply filters for manager, Zonal Sales Executive, Territory Sales Executive, customer
            if (!empty($request->manager_id)) {
                $query->where('manager_id', $request->manager_id);
            }
            if (!empty($request->zse_id)) {
                $query->where('zse_id', $request->zse_id);
            }
            if (!empty($request->tse_id)) {
                $query->where('tse_id', $request->tse_id);
            }
            if (!empty($request->customer_id)) {
                $query->where('customer_id', $request->customer_id);
            }

            // Date filtering for PaymentHistory
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $startDate = Carbon::parse($request->start_date)->format('Y-m-d 00:00:00');
                $endDate = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');

                // Filter invoices that have payments in the given range
                $query->whereHas('paymentHistory', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                });
            }

            return DataTables::of($query->get())
                ->addIndexColumn()
                ->addColumn('payment_history', function ($row) {
                    if ($row->paymentHistory->isNotEmpty()) {
                        return $row->paymentHistory->map(function ($payment) {
                            return Carbon::parse($payment->date)->format('d-m-Y') . ': ' . number_format($payment->amount, 2) . ' TK';
                        })->implode('<br>'); // Converts the array to a proper HTML string
                    }
                    return 'No Payments';
                })
                ->editColumn('deliveredBy.name', function ($row) {
                    return $row->deliveredBy ? $row->deliveredBy->name : 'N/A';
                })
                
                ->addColumn('returnAmount', function ($row) {
                    return round($row->salesReturnMedicines->sum('total'),2);
                })
                // ->editColumn('due', function ($row) {
                //     return $row->salesReturnMedicines->sum('total') > $row->due ? 0 : round($row->due - $row->salesReturnMedicines->sum('total'),2);
                // })
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
                ->rawColumns(['payment_history'])
                ->make(true);
        }
    }

}

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
            $query = Invoice::with(['customer:id,name,user_id,mobile', 'deliveredBy:id,name', 'paymentHistory'])->where('paid', '>', 0);

            // Apply filters for manager, sales manager, field officer, customer
            if (!empty($request->manager_id)) {
                $query->where('manager_id', $request->manager_id);
            }
            if (!empty($request->sales_manager_id)) {
                $query->where('sales_manager_id', $request->sales_manager_id);
            }
            if (!empty($request->field_officer_id)) {
                $query->where('field_officer_id', $request->field_officer_id);
            }
            if (!empty($request->customer_id)) {
                $query->where('customer_id', $request->customer_id);
            }

            // Date filtering for PaymentHistory
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
                $endDate = Carbon::parse($request->end_date)->format('Y-m-d');

                // Filter invoices that have payments in the given range
                $query->whereHas('paymentHistory', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                });
            }

            return DataTables::of($query->get())
                ->addIndexColumn()
                // ->addColumn('payment_history', function ($row) {
                //     return $row->paymentHistory->map(function ($payment) {
                //         return [
                //             'amount' => $payment->amount,
                //             'date' => $payment->date,
                //         ];
                //     })->toArray();
                // })
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
                ->rawColumns(['payment_history'])
                ->make(true);
        }
    }

}

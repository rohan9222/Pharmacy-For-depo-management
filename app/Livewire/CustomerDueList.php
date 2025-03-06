<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\User;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;

class CustomerDueList extends Component
{
    public function render()
    {
        return view('livewire.customer-due-list')->layout('layouts.app');
    }

    
    public function customerDueList(Request $request)
    {
        if ($request->ajax()) {
            // Fetch customers with role 'Customer'
            $data = User::where('role', 'Customer');
    
            // Apply filtering conditions
            if ($request->manager_id) {
                $data = $data->where('manager_id', $request->manager_id);
            }
    
            if ($request->zse_id) {
                $data = $data->where('zse_id', $request->zse_id);
            }
    
            if ($request->tse_id) {
                $data = $data->where('tse_id', $request->tse_id);
            }
    
            if ($request->customer_id) {
                $data = $data->where('id', $request->customer_id);
            }
    
            // Fetch customers
            $customers = $data->get();
    
            // Get customer IDs
            $customerIds = $customers->pluck('id');
    
            // Fetch invoices for the selected customers
            $invoices = Invoice::whereIn('customer_id', $customerIds);
    
            // Apply date range filtering (if provided)
            if ($request->start_date && $request->end_date) {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                $invoices = $invoices->whereBetween('invoice_date', [$startDate, $endDate]);
            }
    
            // Fetch invoice data
            $invoiceData = $invoices->get();
    
            // Add invoice due amount for each customer
            $customers = $customers->map(function ($customer) use ($invoiceData) {
                $customer->invoice_no = $invoiceData->where('customer_id', $customer->id)->pluck('invoice_no')->toArray();;
                $customer->invoice_total = $invoiceData->where('customer_id', $customer->id)->sum('grand_total');
                $customer->invoice_paid = $invoiceData->where('customer_id', $customer->id)->sum('paid');
                $customer->invoice_due = $invoiceData->where('customer_id', $customer->id)->sum('due');
                return $customer;
            });
    
            // Return data for DataTables
            return DataTables::of($customers)
                ->addIndexColumn()
                ->addColumn('invoice_no', function ($row) {
                    return implode(', ', $row->invoice_no);
                })
                ->addColumn('invoice_total', function ($row) {
                    return number_format($row->invoice_total, 2);
                })
                ->addColumn('invoice_paid', function ($row) {
                    return number_format($row->invoice_paid, 2);
                })
                ->addColumn('invoice_due', function ($row) {
                    return number_format($row->invoice_due, 2);
                })
                ->addColumn('action', function ($row) {
                    return '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }    
    }
}

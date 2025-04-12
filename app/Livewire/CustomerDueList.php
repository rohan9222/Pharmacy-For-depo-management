<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\User;
use App\Models\DiscountValue;
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
            $invoices = Invoice::whereIn('customer_id', $customerIds)->with(['salesReturnMedicines']);
    
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
                $customer->invoice_return = $invoiceData->where('customer_id', $customer->id)->flatMap(function ($invoice) {
                    return $invoice->salesReturnMedicines;
                })->sum('total');
                $customer->invoice_paid = $invoiceData->where('customer_id', $customer->id)->sum('paid');
                // $customer->invoice_due = $invoiceData->where('customer_id', $customer->id)->sum('due') - $customer->invoice_return;
                $customer->invoice_due = max(
                    $invoiceData->where('customer_id', $customer->id)->sum(function ($invoice) {
                        $afterReturnPrice = $invoice->sub_total - $invoice->salesReturnMedicines->sum('total_price');
                        $afterReturnVat = $invoice->vat - $invoice->salesReturnMedicines->sum('vat');
                        $sumReturnTotal = $invoice->salesReturnMedicines->sum('total');

                        $discount_data = json_decode($invoice->discount_data);
                        $newDiscount = DiscountValue::where('discount_type', 'General')
                            ->where('start_amount', '<=', $afterReturnPrice)
                            ->where('end_amount', '>=', $afterReturnPrice)
                            ->pluck('discount')
                            ->first();

                        if (!empty($discount_data) && $discount_data->start_amount <= $afterReturnPrice && $afterReturnPrice <= $discount_data->end_amount) {
                            $afterReturnDis = ($afterReturnPrice * $invoice->discount) / 100;
                            $afterReturnDue = ($afterReturnPrice - $afterReturnDis) + $afterReturnVat; 
                        } elseif ($newDiscount !== null) {
                            $afterReturnDue = ($afterReturnPrice + $afterReturnVat) - ($afterReturnPrice * $newDiscount / 100);
                        } else {
                            $afterReturnDue = $afterReturnPrice + $afterReturnVat;
                        }
                
                        // Ensure afterReturnDue is never negative
                        $totalDue = round(max($afterReturnDue - $invoice->paid, 0), 2);
                
                        return $totalDue;
                    })
                    , 0
                );
                
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
                ->addColumn('invoice_return', function ($row) {
                    return number_format($row->invoice_return, 2);
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

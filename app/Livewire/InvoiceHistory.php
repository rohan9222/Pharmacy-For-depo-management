<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\Invoice;
use App\Models\TargetReport;
use App\Models\PaymentHistory;
use App\Models\StockList;
use App\Models\SiteSetting;
use App\Models\SalesMedicine;
use App\Models\ReturnMedicine;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;

use Livewire\Component;

class InvoiceHistory extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search, $invoice_data, $site_settings, $return_date, $return_quantity, $return_medicine, $invoice_discount, $spl_discount,$partialPayment,$amount,$selectedInvoice;

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
        return view('livewire.invoice-history')->layout('layouts.app');
    }

    public function invoiceList(Request $request)
    {
        if ($request->ajax()) {
            $data = Invoice::with(['customer:id,name', 'deliveredBy:id,name']);

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
                    $action = '';

                    // Check if the invoice has a due amount
                    if ($row->due > 0) {
                        $action .= '
                            <button wire:click="setInvoice('.$row->id.')"
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#duePaymentModal">
                                <i class="bi bi-credit-card"></i>
                            </button>
                        ';
                    }

                    // Check if the user has the 'invoice' permission
                    if (auth()->user()->can('invoice')) {
                        if ($row->delivery_status == 'pending') {
                            $action .= '
                                <button class="btn btn-sm btn-info" wire:click="invoiceEdit('.$row->id.')" data-bs-toggle="modal" data-bs-target="#invoiceEditModal"><i class="bi bi-pencil"></i></button>
                            ';
                        }
                        $action .= '
                            <a href="' . route('invoice.pdf', $row->invoice_no) . '" target="_blank" class="btn btn-sm btn-success me-1">
                                <i class="bi bi-eye"></i>
                            </a>
                        ';

                    }

                    // Check if the user has the 'return-medicine' permission
                    if (auth()->user()->can('return-medicine')) {
                        $action .= '
                            <button class="btn btn-sm btn-warning" wire:click="returnMedicine('.$row->id.')" @click="isTableData = false, isInvoiceData = false, isReturnData = true"><i class="bi bi-arrow-counterclockwise"></i></button>
                        ';
                    }

                    return $action;
                })
                ->editColumn('deliveredBy.name', function ($row) {
                    return $row->deliveredBy ? $row->deliveredBy->name : 'N/A'; // Avoids null errors
                })
                ->rawColumns(['action']) // Ensure HTML buttons render correctly
                ->make(true);
        }
    }

    public function invoiceEdit($invoiceID = null)
    {
        if($invoiceID) {
            $this->invoice_discount = Invoice::find($invoiceID);
            $this->spl_discount = $this->invoice_discount->spl_discount;
        }else {
            $this->invoice_discount = null;
            $this->spl_discount = '';
        }
        $this->dispatch('refreshTable');
    }

    public function invoiceUpdate($invoiceID = null)
    {
        if($invoiceID){
            $inv = Invoice::find($invoiceID);
            $inv->spl_dis_amount = $inv->sub_total * $this->spl_discount/100;
            $inv->spl_discount = $this->spl_discount;
            $inv->grand_total = $inv->sub_total + $inv->vat - ($inv->dis_amount + $inv->spl_dis_amount);
            $inv->due = $inv->grand_total - $inv->paid;
            $inv->save();

            $this->spl_discount = '';

            flash()->success('Invoice updated successfully');
        }else{
            flash()->error('Something is wrong');
        }
    }

    public function setInvoice($invoiceId)
    {
        $this->selectedInvoice = Invoice::find($invoiceId);
        $this->partialPayment = '';
        $this->amount = '';
    }

    public function payDue()
    {
        $this->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        if ($this->selectedInvoice) {
            $this->selectedInvoice->paid += $this->amount;
            $this->selectedInvoice->due -= $this->amount;
            $this->selectedInvoice->save();

            PaymentHistory::create([
                'invoice_id' => $this->selectedInvoice->id,
                'amount' => $this->amount,
                'date' => now()
            ]);

            $this->amount = '';

            flash()->success('Amount paid successfully.');
        }else{
            flash()->error('Something went wrong!');
        }
    }

    public function invoiceView($invoiceID = null)
    {
        if($invoiceID) {
            $this->invoice_data = Invoice::with('salesMedicines', 'customer')->find($invoiceID);
        }else {
            $this->invoice_data = null;
        }
    }

    public function returnMedicine($invoiceID = null){
        if($invoiceID) {
            $this->invoice_data = Invoice::with('salesMedicines')->find($invoiceID);
            $this->return_date = Carbon::now()->toDateString();
        }else {
            $this->invoice_data = null;
        }
    }

    public function returnSubmit(){
        $this->validate([
            'return_medicine' => 'required|numeric|exists:sales_medicines,id',
            'return_quantity' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) {
                    $medicine = \App\Models\SalesMedicine::find($this->return_medicine); // Fetch the medicine record
                    if ($medicine && $value > $medicine->quantity) {
                        $fail('The return quantity cannot be greater than the available quantity.');
                    }
                },
            ],
            'return_date' => 'required|date|before_or_equal:today',
        ]);

        DB::beginTransaction();
        try{
            $sales_medicine = SalesMedicine::find($this->return_medicine);
            $sales_medicine->quantity = $sales_medicine->quantity - $this->return_quantity;
            $sales_medicine->save();

            ReturnMedicine::create([
                'invoice_id' => $sales_medicine->invoice_id,
                'medicine_id' => $sales_medicine->medicine_id,
                'sales_medicine_id' => $sales_medicine->id,
                'quantity' => $this->return_quantity,
                'price' => $sales_medicine->price,
                'vat' => $sales_medicine->price * $sales_medicine->vat/100,
                'total' => ($this->return_quantity * $sales_medicine->price) + ($this->return_quantity * $sales_medicine->price * $sales_medicine->vat/100),
                'return_date' => $this->return_date,
            ])->save();

            Medicine::where('id', $sales_medicine->medicine_id)->increment('quantity', $this->return_quantity);

            $stockList = StockList::where('medicine_id', $sales_medicine->medicine_id)
                ->where('initial_quantity', '>', 0)
                ->orderBy('expiry_date', 'asc')
                ->get();

            $remainingQuantity = $this->return_quantity;

            foreach ($stockList as $stock) {
                if ($remainingQuantity <= 0) {
                    break;
                }
                $quantityToIncrease = min($remainingQuantity, $stock->initial_quantity - $stock->quantity);

                $stock->quantity += $quantityToIncrease;
                $stock->save();
                $remainingQuantity -= $quantityToIncrease;
            }

            flash()->success('Return Medicine Successfully!');
            DB::commit();
            $this->return_medicine = '';
            $this->return_quantity = '';
        }catch(\Exception $e){
            flash()->error($e->getMessage());
        }
    }

}

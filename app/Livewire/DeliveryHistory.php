<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Invoice;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

use Livewire\Component;

class DeliveryHistory extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $pending_search, $delivered_search, $invoices, $managers, $zses, $tses, $customers, $manager_id, $zse_id, $tse_id, $customer_id,$delivered_by;
    public $perPage = 15;
    public $selected_invoices = [];


    public function render()
    {
        $allInvoices = Invoice::search($this->delivered_search)
            ->where('delivery_status', 'delivered')
            ->with('deliveredBy:id,name')
            ->get()
            ->groupBy(fn($invoice) => $invoice->summary_id ?? 'Unknown');

        $pagedData = $allInvoices->slice((request('page', 1) - 1) * $this->perPage, $this->perPage);

        $this->updateInvoiceList();

        return view('livewire.delivery-history', [
            'delivered_invoices' => new \Illuminate\Pagination\LengthAwarePaginator(
                $pagedData->values(), $allInvoices->count(), $this->perPage
            ),
            'delivery_man_lists' => User::where('role', 'Delivery Man')->get(),
        ])->layout('layouts.app');
    }

    public function toggleSelectAll()
    {
        if (count($this->selected_invoices) < count($this->invoices)) {
            $this->selected_invoices = $this->invoices->pluck('id')->toArray();
        } else {
            $this->selected_invoices = [];
        }
    }

    public function updateInvoiceList($type = null) {
        // Use relationships properly
        // $this->managers = User::where('role', 'Manager')->get();
        // $zses = User::where('role', 'Zonal Sales Executive');
        $this->tses = User::where('role', 'Territory Sales Executive')->get();
        $customers = User::where('role', 'Customer');

        // $invoices = Invoice::where('delivery_status', 'pending');
        // if($this->manager_id != null){
        //     $invoices = $invoices->where('manager_id', $this->manager_id);
        //     $zses = $zses->where('manager_id', $this->manager_id);
        //     $tses = $tses->where('manager_id', $this->manager_id);
        //     $customers = $customers->where('manager_id', $this->manager_id);
        //     $this->tses = $tses->get() ?? null;
        // }else{
        //     $this->zse_id = null;
        //     $this->tse_id = null;
        //     $this->customer_id = null;
        //     $this->zses = [];
        //     $this->tses = [];
        //     $this->customers = [];
        // }

        // if($this->zse_id != null){
        //     $invoices = $invoices->where('zse_id', $this->zse_id);
        //     $tses = $tses->where('zse_id', $this->zse_id);
        //     $customers = $customers->where('zse_id', $this->zse_id);
        //     $this->tses = $tses->get() ?? null;
        // }else{
        //     $this->tse_id = null;
        //     $this->customer_id = null;
        //     $this->tses = [];
        //     $this->customers = [];
        // }

        if($this->tse_id != null){
            $this->invoices = Invoice::where('delivery_status', 'pending')->where('tse_id', $this->tse_id)->limit($this->perPage)->get() ?? null;
            $customers = $customers->where('tse_id', $this->tse_id);
            $this->customers = $customers->get() ?? null;
        }else{
            $this->invoices = null;
            $this->customer_id = null;
            $this->customers = [];
        }

        if($this->customer_id != null){
            $this->invoices = Invoice::where('delivery_status', 'pending')->where('customer_id', $this->customer_id)->limit($this->perPage)->get() ?? null;
        }

        // if($type == 'manager'){
        //     $this->zses = $zses->get() ?? null;
        // }else
        // if($type == 'zse'){
        //     $this->tses = $tses->get() ?? null;
        // }else
        if($type == 'tse'){
            $this->customers = $customers->get() ?? null;
        }
    }

    public function deliverInvoiceList() {
        $this->validate([
            'selected_invoices' => 'required|array|min:1',
            'selected_invoices.*' => 'exists:invoices,id',
            'delivered_by'      => 'required'
        ]);

        try{
            $lastSummaryId = Invoice::whereNotNull('summary_id')
                        ->orderBy('summary_id', 'desc')
                        ->pluck('summary_id')
                        ->first();

            $summary_id = $lastSummaryId ? $lastSummaryId + 1 : 10000;

            Invoice::whereIn('id', $this->selected_invoices)->update([
                'delivery_status' => 'delivered',
                'delivery_by' => $this->delivered_by,
                'delivery_date' => now(),
                'summary_id' => $summary_id
            ]);

            $this->updateInvoiceList();
            $this->selected_invoices = [];
            $this->delivered_by = null;
        }catch(\Exception $e){
            flash()->error('Something went wrong! ...'.$e->getMessage());
        }
    }
}

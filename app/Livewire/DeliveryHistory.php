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
    public $pending_search, $delivered_search, $invoices, $managers, $sales_managers, $field_officers, $customers, $manager_id, $sales_manager_id, $field_officer_id, $customer_id,$delivered_by;
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

    public function updateInvoiceList($type = null) {
        // Use relationships properly
        // $this->managers = User::where('role', 'Manager')->get();
        // $sales_managers = User::where('role', 'Sales Manager');
        $this->field_officers = User::where('role', 'Field Officer')->get();
        $customers = User::where('role', 'Customer');

        // $invoices = Invoice::where('delivery_status', 'pending');
        // if($this->manager_id != null){
        //     $invoices = $invoices->where('manager_id', $this->manager_id);
        //     $sales_managers = $sales_managers->where('manager_id', $this->manager_id);
        //     $field_officers = $field_officers->where('manager_id', $this->manager_id);
        //     $customers = $customers->where('manager_id', $this->manager_id);
        //     $this->field_officers = $field_officers->get() ?? null;
        // }else{
        //     $this->sales_manager_id = null;
        //     $this->field_officer_id = null;
        //     $this->customer_id = null;
        //     $this->sales_managers = [];
        //     $this->field_officers = [];
        //     $this->customers = [];
        // }

        // if($this->sales_manager_id != null){
        //     $invoices = $invoices->where('sales_manager_id', $this->sales_manager_id);
        //     $field_officers = $field_officers->where('sales_manager_id', $this->sales_manager_id);
        //     $customers = $customers->where('sales_manager_id', $this->sales_manager_id);
        //     $this->field_officers = $field_officers->get() ?? null;
        // }else{
        //     $this->field_officer_id = null;
        //     $this->customer_id = null;
        //     $this->field_officers = [];
        //     $this->customers = [];
        // }

        if($this->field_officer_id != null){
            $this->invoices = Invoice::where('delivery_status', 'pending')->where('field_officer_id', $this->field_officer_id)->limit($this->perPage)->get() ?? null;
            $customers = $customers->where('field_officer_id', $this->field_officer_id);
            $this->customers = $customers->get() ?? null;
        }else{
            $this->customer_id = null;
            $this->customers = [];
        }

        if($this->customer_id != null){
            $this->invoices = Invoice::where('delivery_status', 'pending')->where('customer_id', $this->customer_id)->limit($this->perPage)->get() ?? null;
        }

        // if($type == 'manager'){
        //     $this->sales_managers = $sales_managers->get() ?? null;
        // }else
        // if($type == 'sales_manager'){
        //     $this->field_officers = $field_officers->get() ?? null;
        // }else
        if($type == 'field_officer'){
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

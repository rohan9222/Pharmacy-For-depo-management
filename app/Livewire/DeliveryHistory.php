<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Invoice;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Carbon\Carbon;

use Livewire\Component;

class DeliveryHistory extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $pending_search, $delivered_search, $invoices, $managers, $zses, $tses, $customers, $manager_id, $zse_id, $tse_id, $customer_id, $delivered_by, $filter_delivered_by, $filter_delivered_date;
    public $perPage = 50;
    public $selected_invoices = [];

    public function mount() {
        if(!auth()->user()->hasPermissionTo('delivery-report') && !auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }
    }

    public function render()
    {
        $this->updateInvoiceList();

        return view('livewire.delivery-history', [
            'delivery_man_lists' => User::where('role', 'Delivery Man')->get(),
            'delivered_invoices' => $this->getDeliveredInvoices(),
        ])->layout('layouts.app');
    }
    
    public function getDeliveredInvoices()
    {
        // Step 1: Get unique summary_id values with pagination
        $paginatedSummaryIds = Invoice::search($this->delivered_search)
            ->where('delivery_status', 'delivered')
            ->when($this->filter_delivered_by, fn($q) => $q->where('delivery_by', $this->filter_delivered_by))
            ->when($this->filter_delivered_date, fn($q) => $q->whereDate('delivery_date', Carbon::parse($this->filter_delivered_date)))
            ->select('summary_id') // Select only summary_id for pagination
            ->groupBy('summary_id') // Grouping at the database level
            ->orderBy('summary_id', 'desc') // Maintain order
            ->paginate(10); // Paginate by summary_id
    
        // Step 2: Fetch full invoice data for the paginated summary IDs
        $invoices = Invoice::whereIn('summary_id', $paginatedSummaryIds->pluck('summary_id'))
            ->with('deliveredBy:id,name')
            ->orderBy('summary_id', 'desc')
            ->get()
            ->groupBy('summary_id'); // Group invoices by summary_id in PHP
    
        // Step 3: Return paginated response with grouped invoices
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $invoices, // Grouped data
            $paginatedSummaryIds->total(), // Total items
            $paginatedSummaryIds->perPage(), // Items per page
            $paginatedSummaryIds->currentPage(), // Current page
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );
    }
    
    

    public function toggleSelectAll()
    {
        if (count($this->selected_invoices) < count($this->invoices)) {
            $this->selected_invoices = $this->invoices->pluck('id')->toArray();
        } else {
            $this->selected_invoices = [];
        }
    }

    public function tseIdUpdate()
    {
        $this->updateInvoiceList('tse');
        $customers = User::where('role', 'Customer')->where('tse_id', $this->tse_id)->get();
        if($this->tse_id) {
            $this->customers = $customers;
        }else{
            $this->customers = [];
        }
        $this->customer_id = null;
    }

    public function customerIdUpdate()
    {
        $this->updateInvoiceList('customer');
    }

    public function updateInvoiceList($type = null) {
        // Use relationships properly
        // $this->managers = User::where('role', 'Manager')->get();
        // $zses = User::where('role', 'Zonal Sales Executive');
        $this->tses = User::where('role', 'Territory Sales Executive')->get();
        

        $invoices = Invoice::where('delivery_status', 'pending');
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
            $invoices = $invoices->where('tse_id', $this->tse_id) ?? null;
        }

        if($this->customer_id != null){
            $invoices = $invoices->where('customer_id', $this->customer_id) ?? null;
        }
        // if($type == 'manager'){
        //     $this->zses = $zses->get() ?? null;
        // }else
        // if($type == 'zse'){
        //     $this->tses = $tses->get() ?? null;
        // }else
        $this->invoices = $invoices->limit($this->perPage)->get() ?? null;
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

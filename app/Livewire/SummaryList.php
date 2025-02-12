<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\TargetReport;

use Livewire\Component;

class SummaryList extends Component
{
    public $search, $admin_targets,$managers, $manager_id, $sales_managers, $sales_manager_id, $field_officers, $field_officer_id, $customers, $customer_id, $invoices, $type;

    public function mount(){
        if(auth()->user()->hasRole('Super Admin')) {
            return true;
        }elseif(auth()->user()->hasRole('Manager')) {
            $this->type = 'manager';
        }elseif(auth()->user()->hasRole('Sales Manager')) {
            $this->type = 'sales_manager';
        }elseif(auth()->user()->hasRole('Field Officer')) {
            $this->type = 'field_officer';
        }
    }

    public function render()
    {
        $this->updateUserList();

        return view('livewire.summary-list')->layout('layouts.app');
    }

    public function updateUserList() {
        // Use relationships properly
        $managers = User::where('role', 'Manager');
        $sales_managers = User::where('role', 'Sales Manager');
        $field_officers = User::where('role', 'Field Officer');

        $admin_targets = TargetReport::query()->with('userData:id,name,role')->search($this->search);

        if(auth()->user()->role == 'Super Admin'){
            $this->managers = $managers->get();
            if($this->manager_id != null){
                $admin_targets = $admin_targets->where('manager', $this->manager_id);
                $this->sales_managers = $sales_managers->where('manager_id', $this->manager_id)->get();
            }else{
                $this->sales_manager_id = null;
                $this->sales_managers = [];
                $this->field_officer_id = null;
                $this->field_officers = [];
            }

            if($this->sales_manager_id != null){
                $admin_targets = $admin_targets->where('sales_manager', $this->sales_manager_id);
                $this->field_officers = $field_officers->where('sales_manager_id', $this->sales_manager_id)->get();
            }else{
                $this->field_officer_id = null;
                $this->field_officers = [];
            }

            if($this->field_officer_id != null){
                $admin_targets = $admin_targets->where('field_officer', $this->field_officer_id);
            }
        }elseif($this->type == 'manager'){
            $this->sales_managers = $sales_managers->where('manager_id', auth()->user()->id)->get();
            $this->admin_targets = $admin_targets->where('user_id', auth()->user()->id)->get();


            if($this->sales_manager_id != null){
                $admin_targets = $admin_targets->where('sales_manager', $this->sales_manager_id);
                $this->field_officers = $field_officers->where('sales_manager_id', $this->sales_manager_id)->get();
            }else{
                $this->field_officer_id = null;
                $this->field_officers = [];
            }

            if($this->field_officer_id != null){
                $admin_targets = $admin_targets->where('field_officer', $this->field_officer_id);
            }
        }elseif($this->type == 'sales_manager'){
            $this->field_officers = $field_officers->where('sales_manager_id', auth()->user()->id)->get();
            $this->admin_targets = $admin_targets->where('user_id', auth()->user()->id)->get();

            if($this->field_officer_id != null){
                $admin_targets = $admin_targets->where('field_officer', $this->field_officer_id);
            }
        }elseif($this->type == 'field_officer'){
            $this->admin_targets = $admin_targets->where('user_id', auth()->user()->id)->get();
        }else{
            $this->admin_targets = $admin_targets->get();
        }

        $this->admin_targets = $admin_targets->get();
    }

}

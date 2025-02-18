<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UserDataManage extends Component
{
    public $managers = [], $manager_id;
    public $sales_managers = [], $sales_manager_id;
    public $field_officers = [], $field_officer_id;
    public $customers = [], $customer_id;
    public $type;

    public function mount()
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            $this->type = 'super_admin';
        } elseif ($user->hasRole('Manager')) {
            $this->type = 'manager';
        } elseif ($user->hasRole('Sales Manager')) {
            $this->type = 'sales_manager';
        } elseif ($user->hasRole('Field Officer')) {
            $this->type = 'field_officer';
        }

        $this->updateUserList();
    }

    public function updateUserList()
    {
        $user = auth()->user();

        // Initialize Empty Arrays
        $this->sales_managers = [];
        $this->field_officers = [];
        $this->customers = [];

        if ($this->type === 'super_admin') {
            $this->managers = User::where('role', 'Manager')->get();
        }

        if ($this->type === 'super_admin' || $this->type === 'manager') {
            if ($this->manager_id) {
                $this->sales_managers = User::where('role', 'Sales Manager')
                    ->where('manager_id', $this->manager_id)
                    ->get();
            }
        } else {
            $this->sales_managers = User::where('role', 'Sales Manager')
                ->where('manager_id', $user->id)
                ->get();
        }

        if ($this->sales_manager_id) {
            $this->field_officers = User::where('role', 'Field Officer')
                ->where('sales_manager_id', $this->sales_manager_id)
                ->get();
        }

        if ($this->field_officer_id) {
            $this->customers = User::where('role', 'Customer')
                ->where('field_officer_id', $this->field_officer_id)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.user-data-manage');
    }
}

<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UserDataManage extends Component
{
    public $managers = [], $manager_id;
    public $zses = [], $zse_id;
    public $tses = [], $tse_id;
    public $customers = [], $customer_id;
    public $type;

    public function mount()
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            $this->type = 'super_admin';
        } elseif ($user->hasRole('Manager')) {
            $this->type = 'manager';
        } elseif ($user->hasRole('Zonal Sales Executive')) {
            $this->type = 'zse';
        } elseif ($user->hasRole('Territory Sales Executive')) {
            $this->type = 'tse';
        }

        $this->updateUserList();
    }

    public function updateUserList()
    {
        $user = auth()->user();

        // Initialize Empty Arrays
        $this->zses = [];
        $this->tses = [];
        $this->customers = [];

        if ($this->type === 'super_admin') {
            $this->managers = User::where('role', 'Manager')->get();
        }

        if ($this->type === 'super_admin' || $this->type === 'manager') {
            if ($this->manager_id) {
                $this->zses = User::where('role', 'Zonal Sales Executive')
                    ->where('manager_id', $this->manager_id)
                    ->get();
            }
        } else {
            $this->zses = User::where('role', 'Zonal Sales Executive')
                ->where('manager_id', $user->id)
                ->get();
        }

        if ($this->zse_id) {
            $this->tses = User::where('role', 'Territory Sales Executive')
                ->where('zse_id', $this->zse_id)
                ->get();
        }

        if ($this->tse_id) {
            $this->customers = User::where('role', 'Customer')
                ->where('tse_id', $this->tse_id)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.user-data-manage');
    }
}

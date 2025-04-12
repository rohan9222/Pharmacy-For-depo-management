<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\TargetReport;

use Livewire\Component;

class TargetHistory extends Component
{
    public $search, $admin_targets,$managers, $manager_id, $zses, $zse_id, $tses, $tse_id, $customers, $customer_id, $invoices, $type;

    public function mount(){
        if(auth()->user()->hasRole('Super Admin')) {
            return true;
        }elseif(auth()->user()->hasRole('Manager')) {
            $this->type = 'manager';
        }elseif(auth()->user()->hasRole('Zonal Sales Executive')) {
            $this->type = 'zse';
        }elseif(auth()->user()->hasRole('Territory Sales Executive')) {
            $this->type = 'tse';
        }
    }

    public function render()
    {
        $this->updateUserList();

        return view('livewire.target-history')->layout('layouts.app');
    }

    public function updateUserList() {
        // Use relationships properly
        $managers = User::where('role', 'Manager');
        $zses = User::where('role', 'Zonal Sales Executive');
        $tses = User::where('role', 'Territory Sales Executive');

        $admin_targets = TargetReport::query()->with('userData:id,name,role')->search($this->search);

        if(auth()->user()->role == 'Super Admin'){
            $this->managers = $managers->get();
            if($this->manager_id != null){
                $admin_targets = $admin_targets->where('manager', $this->manager_id);
                $this->zses = $zses->where('manager_id', $this->manager_id)->get();
            }else{
                $this->zse_id = null;
                $this->zses = [];
                $this->tse_id = null;
                $this->tses = [];
            }

            if($this->zse_id != null){
                $admin_targets = $admin_targets->where('zse', $this->zse_id);
                $this->tses = $tses->where('zse_id', $this->zse_id)->get();
            }else{
                $this->tse_id = null;
                $this->tses = [];
            }

            if($this->tse_id != null){
                $admin_targets = $admin_targets->where('tse', $this->tse_id);
            }
        }elseif($this->type == 'manager'){
            $this->zses = $zses->where('manager_id', auth()->user()->id)->get();
            $this->admin_targets = $admin_targets->where('user_id', auth()->user()->id)->get();


            if($this->zse_id != null){
                $admin_targets = $admin_targets->where('zse', $this->zse_id);
                $this->tses = $tses->where('zse_id', $this->zse_id)->get();
            }else{
                $this->tse_id = null;
                $this->tses = [];
            }

            if($this->tse_id != null){
                $admin_targets = $admin_targets->where('tse', $this->tse_id);
            }
        }elseif($this->type == 'zse'){
            $this->tses = $tses->where('zse_id', auth()->user()->id)->get();
            $this->admin_targets = $admin_targets->where('user_id', auth()->user()->id)->get();

            if($this->tse_id != null){
                $admin_targets = $admin_targets->where('tse', $this->tse_id);
            }
        }elseif($this->type == 'tse'){
            $this->admin_targets = $admin_targets->where('user_id', auth()->user()->id)->get();
        }else{
            $this->admin_targets = $admin_targets->get();
        }

        $this->admin_targets = $admin_targets->get();
    }

}

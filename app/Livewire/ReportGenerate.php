<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class ReportGenerate extends Component
{   
    public $managers = [], $manager_id;
    public $zses = [], $zse_id;
    public $tses = [], $tse_id;
    public $customers = [], $customer_id;
    public $type;

    public function mount()
    {
        if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view-report')) {
            return true;
        }else{
            abort(403, 'Unauthorized action.'); 
        }
    }
    
    public function render()
    {
        $this->managers = User::where('role', 'Manager')->get();
        return view('livewire.report-generate')->layout('layouts.app');
    }
}

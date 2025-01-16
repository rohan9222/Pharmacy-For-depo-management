<?php

namespace App\Livewire;

use App\Models\CustomerList;
use Livewire\Component;

class CustomersList extends Component
{

    public function render()
    {
        $customers = CustomerList::paginate(10);
        return view('livewire.customers-list', ['customers' => $customers])->layout('layouts.app');
    }
}

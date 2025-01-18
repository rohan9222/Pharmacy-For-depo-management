<?php

namespace App\Livewire;

use Livewire\Component;

class MedicinesList extends Component
{
    public function render()
    {
        return view('livewire.medicines-list')->layout('layouts.app');
    }
}

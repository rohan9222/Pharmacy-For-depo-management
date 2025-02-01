<?php

namespace App\Livewire;

use Livewire\Component;

class SummaryList extends Component
{
    public function render()
    {
        return view('livewire.summary-list')->layout('layouts.app');
    }
}

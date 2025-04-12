<?php

namespace App\Livewire;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

use App\Models\PackSize;

use Livewire\Component;

class PackSizeList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $packSizeId, $pack_name, $pack_size, $description, $status, $search;

    public function mount()
    {
        if(auth()->user()->hasRole('Super Admin')) {
            return true;
        }
        if (!auth()->user()->hasAnyPermission(['create-PackSize', 'edit-PackSize', 'delete-PackSize'])) {
            abort(403, 'Unauthorized action.');
        }
        return true;
    }

    public function render()
    {
        $packSizes = PackSize::search($this->search)->paginate(10);
        return view('livewire.pack-size-list', compact('packSizes'))->layout('layouts.app');
    }

    // Updated rules method
    public function role()
    {
        return [
            'pack_name' => 'required|string|max:255|unique:pack_sizes,pack_name,' . $this->packSizeId,
            'pack_size' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ];
    }

    public function submit()
    {
        $this->validate($this->role());

        try {
            PackSize::updateOrCreate(
                ['id' => $this->packSizeId],
                [
                    'pack_name' => $this->pack_name,
                    'pack_size' => $this->pack_size,
                    'description' => $this->description,
                    'status' => $this->status,
                ]
            );
            $this->reset();
            flash()->success('Packages added successfully!');
        } catch (\Exception $e) {
            flash()->error('Error: ' . $e->getMessage());
            return;
        }
    }

    public function edit($id)
    {
        $packSize = PackSize::find($id);
        if ($packSize) {
            $this->packSizeId = $id;
            $this->pack_name = $packSize->pack_name;
            $this->pack_size = $packSize->pack_size;
            $this->description = $packSize->description;
            $this->status = $packSize->status;
        }else {
            flash()->error('Something went wrong!');
        }
    }

    public function delete($id)
    {
        $packSize = PackSize::find($id);
        if ($packSize) {
            $packSize->forceDelete();
            flash()->success('Packages deleted successfully!');
        }else {
            flash()->error('Something went wrong!');
        }
    }
}

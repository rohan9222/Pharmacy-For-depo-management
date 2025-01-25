<?php

namespace App\Livewire;

use App\Models\Supplier;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

use Livewire\Component;

class SupplierList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $supplierId, $name, $email, $mobile, $address, $balance, $supplier_type, $search;

    public function mount()
    {
        if(auth()->user()->hasRole('Super Admin')) {
            return true;
        }
        if (!auth()->user()->hasAnyPermission(['create-supplier', 'edit-supplier', 'delete-supplier'])) {
            abort(403, 'Unauthorized action.');
        }
        return true;
    }
    public function render()
    {
        $suppliers = Supplier::search($this->search)->paginate(10);

        return view('livewire.supplier-list', ['suppliers' => $suppliers])->layout('layouts.app');
    }

    // Updated rules method
    public function role()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|numeric|digits:11',
            'address' => 'required|string|max:255',
            'balance' => 'nullable|numeric',
            'supplier_type' => 'required|string|max:255',
        ];
    }

    public function submit()
    {
        $this->validate($this->role());

        try {
            Supplier::updateOrCreate(
                ['id' => $this->supplierId],
                [
                    'name' => $this->name,
                    'email' => $this->email,
                    'mobile' => $this->mobile,
                    'address' => $this->address,
                    'balance' => $this->balance,
                    'supplier_type' => $this->supplier_type,
                ]
            );
            $this->reset();
            flash()->success('Supplier added successfully!');
        } catch (\Exception $e) {
            flash()->error('Error: ' . $e->getMessage());
            return;
        }
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $this->supplierId = $id;
            $this->name = $supplier->name;
            $this->email = $supplier->email;
            $this->mobile = $supplier->mobile;
            $this->address = $supplier->address;
            $this->balance = $supplier->balance;
            $this->supplier_type = $supplier->supplier_type;
        }else {
            flash()->error('Something went wrong!');
        }
    }

    public function delete($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->forceDelete();
            flash()->success('Supplier deleted successfully!');
        }else {
            flash()->error('Something went wrong!');
        }
    }
}

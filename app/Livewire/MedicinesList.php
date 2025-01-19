<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\Category;
use App\Models\Supplier;
use DNS1D; // For 1D barcodes
use DNS2D; // For 2D barcodes (QR codes)

use Livewire\Component;

class MedicinesList extends Component
{
    public $medicineId, $categoryLists, $suppliers, $name, $generic_name, $supplier_name, $shelf, $description, $category_name, $search, $field_officers, $field_officer_team;

    public function mount()
    {
        if(auth()->user()->hasRole('Super Admin')) {
            return true;
        }
        if (!auth()->user()->hasAnyPermission(['create-medicine', 'edit-medicine', 'delete-medicine','create-medicine-stock', 'edit-medicine-stock', 'delete-medicine-stock']) || auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }
        return true;
    }

    public function render()
    {
        $this->categoryLists = Category::select('name')->get();
        $this->suppliers = Supplier::select('name')->get();
        $medicines = Medicine::search($this->search)->paginate(15);

        return view('livewire.medicines-list', ['medicines' => $medicines])->layout('layouts.app');
    }

    // Updated rules method
    public function role()
    {
        return [
            'name' => 'required|string|max:255',
            'generic_name' => ['required', 'generic_name', 'max:255',
                function ($attribute, $value, $fail) {
                    $users = Medicine::where('generic_name', $value)->where('id', '!=', $this->medicineId)->first();
                    if ($users) {
                        $fail('The generic_name description is already associated with a '.ucfirst($users->role).' list. Please use a different generic_name description');
                    }
                }
            ],
            'shelf' => 'required|numeric|digits:11',
            'description' => 'required|string|max:255',
            'category_name' => 'nullable|numeric',
            'field_officer_team' => ['required','exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = Medicine::find($value); // Retrieve the user once to avoid multiple queries
                    if (!$user || (!$user->sales_manager_id && !$user->manager_id)) {
                        $fail('This field officer team does not exist or not assigned to any manager and sales manager.');
                    }
                }
            ],
        ];
    }

    public function submit()
    {
        $this->validate($this->role());
        try {
            $newmedicine = Medicine::updateOrCreate(
                ['id' => $this->medicineId],
                [
                    'name' => $this->name,
                    'generic_name' => $this->generic_name,
                    'password' => $this->generic_name.$this->shelf.$this->name,
                    'shelf' => $this->shelf,
                    'description' => $this->description,
                    'category_name' => $this->category_name ?? 0.00,
                    'role' => 'medicine',
                    'field_officer_id' => $this->field_officer_team,
                    'sales_manager_id' => Medicine::where('id', $this->field_officer_team)->first()->sales_manager_id,
                    'manager_id' => Medicine::where('id', $this->field_officer_team)->first()->manager_id,
                ]
            );

            $this->reset();
            flash()->success('medicine added successfully!');
        } catch (\Exception $e) {
            flash()->error('Error: ' . $e->getMessage());
            return;
        }
    }

    public function edit($id)
    {
        $medicine = Medicine::find($id);
        if ($medicine) {
            $this->medicineId = $id;
            $this->name = $medicine->name;
            $this->generic_name = $medicine->generic_name;
            $this->shelf = $medicine->shelf;
            $this->description = $medicine->description;
            $this->category_name = $medicine->category_name;
        }else {
            flash()->error('Something went wrong!');
        }
    }

    public function delete($id)
    {
        $medicine = Medicine::find($id);
        if ($medicine) {
            $medicine->forceDelete();
            flash()->success('medicine deleted successfully!');
        }else {
            flash()->error('Something went wrong!');
        }
    }

}

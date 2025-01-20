<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\Category;
use App\Models\Supplier;
use Livewire\WithFileUploads;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use DNS1D; // For 1D barcodes
use DNS2D; // For 2D barcodes (QR codes)

use Livewire\Component;

class MedicinesList extends Component
{
    use WithFileUploads;

    public $medicineId, $categoryLists, $suppliers, $barcode, $name, $generic_name, $supplier_name, $shelf, $description, $category_name, $search, $quantity, $supplier_price, $price, $vat, $image_url;
    public  $status = 1;
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
            'barcode' => 'nullable|unique:your_table_name,barcode',
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'supplier_name' => 'required|string|max:255',
            'shelf' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:255',
            'category_name' => 'required|string|max:255',
            'quantity' => 'required|numeric',
            'status' => 'required|boolean',
            'supplier_price' => 'required|numeric',
            'price' => 'required|numeric',
            'vat' => 'required|numeric',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }


    public function removePhoto(){
        $this->image_url = Null;
        $this->dispatch('showToast', 'Image Removed successfully!', 'warning');
    }

    public function submit()
    {
        $this->validate($this->role());

        // Start a database transaction
        DB::beginTransaction();

        try {
           // Generate a unique filename and define the path
            $filename = uniqid() . '.jpg';
            $path = 'img/customer-images/' . $filename;

            if ($this->image_url) {
                $image_file =$this->image_url->getRealPath();
                // create new manager instance with desired driver
                $manager = new ImageManager(new Driver());
                // read image from file system
                $image = $manager->read($image_file);
                // Image resize
                $image->resize(300, 300);
                // save modified image in new format
                $image->save(public_path("$path"));
            }
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
                    'image_url' => $this->image_url ? $path : null,
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

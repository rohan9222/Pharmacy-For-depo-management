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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Add this at the top of your class

use DNS1D; // For 1D barcodes
use DNS2D; // For 2D barcodes (QR codes)

use Livewire\Component;

class MedicinesList extends Component
{
    use WithFileUploads;

    public $medicineId, $categoryLists, $suppliers, $barcode, $name, $generic_name, $supplier_name, $shelf, $description, $category_name, $search, $supplier_price, $price,  $image_url;
    public $vat = 17.4;
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

        foreach ($medicines as $medicine) {
            $medicine->barcode_html = DNS1D::getBarcodeHTML($medicine->barcode, 'PHARMA'); // Store barcode as a new attribute
        }

        return view('livewire.medicines-list', ['medicines' => $medicines])->layout('layouts.app');
    }


    // Updated rules method
    public function role()
    {
        return [
            'barcode' => [
                'nullable',
                Rule::unique('medicines', 'barcode')->ignore($this->medicineId, 'id'),
            ],
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'supplier_name' => 'required|string|max:255',
            'shelf' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:255',
            'category_name' => 'required|string|max:255',
            // 'quantity' => 'required|numeric',
            'status' => 'required|boolean',
            'supplier_price' => 'required|numeric',
            'price' => 'required|numeric',
            'vat' => 'nullable|numeric',
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
            $path = 'img/medicine-images/' . $filename;

            if ($this->image_url) {
                $image_file =$this->image_url->getRealPath();
                // create new manager instance with desired driver
                $manager = new ImageManager(new Driver());
                // read image from file system
                $image = $manager->read($image_file);
                // Image resize
                $image->resize(600, 600);
                // save modified image in new format
                $image->save(public_path("$path"));
            }
            $newmedicine = Medicine::updateOrCreate(
                ['id' => $this->medicineId],
                [
                    'barcode' => $this->barcode,
                    'name' => $this->name,
                    'generic_name' => $this->generic_name,
                    'supplier' => $this->supplier_name,
                    'shelf' => $this->shelf,
                    'description' => $this->description,
                    'category_name' => $this->category_name,
                    'status' => $this->status,
                    'supplier_price' => $this->supplier_price,
                    'price' => $this->price,
                    'vat' => $this->vat,
                    'image_url' => $this->image_url ? $path : null,
                ]
            );

            $this->reset();
            DB::commit();

            if($this->medicineId){
                flash()->success('medicine updated successfully!');
            }else{
                flash()->success('medicine added successfully!');
            }
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
            $this->barcode = $medicine->barcode;
            $this->name = $medicine->name;
            $this->generic_name = $medicine->generic_name;
            $this->shelf = $medicine->shelf;
            $this->description = $medicine->description;
            $this->category_name = $medicine->category_name;
            $this->status = $medicine->status;
            $this->supplier_price = $medicine->supplier_price;
            $this->price = $medicine->price;
            $this->vat = $medicine->vat;
            $this->image_url = $medicine->image_url;
            $this->supplier_name = $medicine->supplier;

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

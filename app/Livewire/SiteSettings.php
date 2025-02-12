<?php

namespace App\Livewire;

use App\Models\SiteSetting;
use App\Models\DiscountValue;

use Livewire\WithFileUploads;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use Livewire\Component;

class SiteSettings extends Component
{
    use WithFileUploads;

    public $site_name, $site_title, $site_email, $site_phone, $site_address, $site_logo, $preview_site_logo, $site_favicon, $preview_site_favicon, $site_currency, $site_invoice_prefix, $medicine_expiry_days, $medicine_low_stock_quantity, $discountId, $start_amount, $end_amount, $discount, $discount_values;

    public function mount()
    {

        $site_settings = SiteSetting::first();
        $this->site_name = $site_settings->site_name;
        $this->site_title = $site_settings->site_title;
        $this->site_email = $site_settings->site_email;
        $this->site_phone = $site_settings->site_phone;
        $this->site_address = $site_settings->site_address;
        $this->preview_site_logo = $site_settings->site_logo;
        $this->preview_site_favicon = $site_settings->site_favicon;
        $this->site_currency = $site_settings->site_currency;
        $this->site_invoice_prefix = $site_settings->site_invoice_prefix;
        $this->medicine_expiry_days = $site_settings->medicine_expiry_days;
        $this->medicine_low_stock_quantity = $site_settings->medicine_low_stock_quantity;

        if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('edit-site-settings')) {
            return true;
        }
        abort(403, 'Unauthorized action.');
    }
    public function render()
    {
        $this->discount_values = DiscountValue::all();
        return view('livewire.site-settings')->layout('layouts.app');
    }

    public function removePhoto($img)
    {
        if ($img === 'logo') {
            $this->site_logo = null;
        } elseif ($img === 'favicon') {
            $this->site_favicon = null;
        } else {
            flash()->error('Invalid image type!');
        }
    }

    public function removePreviewPhoto($img)
    {
        if ($img === 'logo') {
            if (!empty($this->preview_site_logo)) {
                $filePath = 'img/' . $this->preview_site_logo;
                Storage::disk('public')->delete($filePath);
            }
            SiteSetting::where('id', 1)->update(['site_logo' => null]);
            $this->preview_site_logo = null;
            flash()->warning('Logo image removed successfully!');
        } elseif ($img === 'favicon') {
            if (!empty($this->preview_site_favicon)) {
                $filePath = 'img/' . $this->preview_site_favicon;
                Storage::disk('public')->delete($filePath);
            }
            SiteSetting::where('id', 1)->update(['site_favicon' => null]);
            $this->preview_site_favicon = null;
            flash()->warning('Favicon image removed successfully!');
        } else {
            flash()->error('Invalid image type!');
        }
    }

    public function updateSettings(){
        $this->validate([
            'site_name' => 'required',
            'site_title' => 'required',
            'site_email' => 'required|email',
            'site_phone' => 'required|digits:11',
            'site_address' => 'required',
            'site_currency' => 'required',
            'site_invoice_prefix' => 'required',
            'medicine_expiry_days' => 'required|numeric',
            'medicine_low_stock_quantity' => 'required|numeric',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'site_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $site_settings = SiteSetting::first();
        $site_settings->site_name = $this->site_name;
        $site_settings->site_title = $this->site_title;
        $site_settings->site_email = $this->site_email;
        $site_settings->site_phone = $this->site_phone;
        $site_settings->site_address = $this->site_address;
        $site_settings->site_currency = $this->site_currency;
        $site_settings->site_invoice_prefix = $this->site_invoice_prefix;
        $site_settings->medicine_expiry_days = $this->medicine_expiry_days;
        $site_settings->medicine_low_stock_quantity = $this->medicine_low_stock_quantity;


        if ($this->site_logo) {
            $filename = uniqid() . '.png';
            $path = 'img/' . $filename;

            $image_file =$this->site_logo->getRealPath();
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image_file);
            $image->resize(600, 600);
            $image->save(public_path("$path"));
            $site_settings->site_logo = $path;
        }

        if ($this->site_favicon) {
            $filename = uniqid() . '.png';
            $path = 'img/' . $filename;

            $image_file =$this->site_favicon->getRealPath();
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image_file);
            $image->resize(600, 600);
            $image->save(public_path("$path"));
            $site_settings->site_favicon = $path;
        }
        $site_settings->save();
        flash()->success('Site settings updated successfully!');
    }

    public function addDiscountValueIns()
    {
        // Validate the data before processing
        $this->validate([
            'start_amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if($this->discountId){
                        $latestDiscount = DB::table('discount_values')
                            ->where('id', $this->discountId)
                            ->first();

                        // If it's an edit scenario and we have a valid record
                        if ($latestDiscount) {
                            // Check if the start_amount is between the previous start and end amount
                            if ($value > $latestDiscount->end_amount || $value < $latestDiscount->start_amount) {
                                $fail('The ' . $attribute . ' must be between the previous start and end amount.');
                            }
                        }
                    }else{
                        // Get the latest end_amount for the same discount_type and Institution
                        $latestEndAmount = DB::table('discount_values')
                            ->where('discount_type', 'Institution')
                            ->orderByDesc('end_amount')
                            ->value('end_amount');
                        // If there's a latest end_amount, check if start_amount is greater
                        if ($latestEndAmount !== null && $value < $latestEndAmount) {
                            $fail('The ' . $attribute . ' must be greater than the previous last end amount.');
                        }
                    }

                }
            ],
            'end_amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    // Ensure end_amount is greater than the start_amount
                    if ($value <= $this->start_amount) {
                        $fail('The ' . $attribute . ' must be greater than the start amount.');
                    }
                }
            ],
            'discount' => 'required|numeric',
        ]);

        // Use updateOrCreate to either update an existing discount value or create a new one
        DiscountValue::updateOrCreate(
            [
                'id' => $this->discountId  // If the ID is null, it'll create a new entry
            ],
            [
                'start_amount' => $this->start_amount,
                'end_amount' => $this->end_amount,
                'discount' => $this->discount,
                'discount_type' => 'Institution'
            ]
        );

        // Reset input values after successful save
        $this->start_amount = $this->end_amount = $this->discount = $this->discountId = null;

        // Flash success message
        flash()->success('Discount value added successfully!');
    }


    public function addDiscountValueGen()
    {

        $this->validate([
            'start_amount' => [
                'required',
                'numeric',
                // Rule::unique('discount_values', 'start_amount')
                //     ->where('discount_type', 'General'),
                function ($attribute, $value, $fail) {
                    if($this->discountId){
                        $latestDiscount = DB::table('discount_values')
                            ->where('id', $this->discountId)
                            ->first();

                        // If it's an edit scenario and we have a valid record
                        if ($latestDiscount) {
                            // Check if the start_amount is between the previous start and end amount
                            if ($value > $latestDiscount->end_amount || $value < $latestDiscount->start_amount) {
                                $fail('The ' . $attribute . ' must be between the previous start and end amount.');
                            }
                        }
                    }else{
                        // Get the latest end_amount for the same discount_type and Institution
                        $latestEndAmount = DB::table('discount_values')
                            ->where('discount_type', 'General')
                            ->orderByDesc('end_amount')
                            ->value('end_amount');
                        // If there's a latest end_amount, check if start_amount is greater
                        if ($latestEndAmount !== null && $value < $latestEndAmount) {
                            $fail('The ' . $attribute . ' must be greater than the previous last end amount.');
                        }
                    }
                }
            ],
            'end_amount' => [
                'required',
                'numeric',
                // Rule::unique('discount_values', 'end_amount')
                //     ->where('discount_type', 'General'),
                function ($attribute, $value, $fail) {
                    // Ensure end_amount is greater than the start_amount
                    if ($value <= $this->start_amount) {
                        $fail('The ' . $attribute . ' must be greater than the start amount.');
                    }
                }
            ],
            'discount' => 'required|numeric',
        ]);
        DiscountValue::updateOrCreate(
            [
                'id' => $this->discountId
            ],
            [
                'start_amount' => $this->start_amount,
                'end_amount' => $this->end_amount,
                'discount' => $this->discount,
                'discount_type' => 'General'
            ]
        );
        $this->start_amount = $this->end_amount = $this->discount = $this->discountId = null;
        flash()->success('Discount value added successfully!');
    }

    public function editDiscountValue($id)
    {
        $discount_value = DiscountValue::find($id);
        $this->discountId = $discount_value->id;
        $this->start_amount = $discount_value->start_amount;
        $this->end_amount = $discount_value->end_amount;
        $this->discount = $discount_value->discount;
    }

    public function deleteDiscountValue($id){
        DiscountValue::find($id)->delete();
        flash()->success('Discount value deleted successfully!');
    }
}

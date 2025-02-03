<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Invoice;
use App\Models\PaymentHistory;
use App\Models\SiteSetting;
use Livewire\Component;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

use Illuminate\Validation\Rule;

class SupportersList extends Component
{
    use WithPagination, WithoutUrlPagination;
    
    public $type, $adminUserData, $site_settings, $invoices,$selectedInvoice,$customer,   $customerId, $name, $email, $mobile, $address, $balance, $search, $field_officers, $field_officer_team;

    public function mount($type)
    {
        $this->type = $type;
        // Validate the type type
        if (!in_array($type, ['manager', 'sales-manager','field-officer'])) {
            abort(403, 'Access Denied: Invalid Admin Type');
        }
        return true;
    }

    public function render()
    {
        $this->site_settings = SiteSetting::first();
        // Fetch admin_users based on search and role
        $admin_user = User::query()
            ->search($this->search);
            if($this->type == 'manager'){
                $admin_user = $admin_user->where('role', 'Manager');
            }elseif($this->type == 'sales-manager'){
                $admin_user = $admin_user->where('role', 'Sales Manager');
            }elseif($this->type == 'field-officer'){
                $admin_user = $admin_user->where('role', 'Field Officer');
            }
        $admin_users = $admin_user->paginate(10);

        return view('livewire.supporters-list', ['admin_users' => $admin_users])->layout('layouts.app');
    }


    public function view($id = null){
        $adminUserData = User::find($id);
        $invoices = Invoice::where('customer_id', $adminUserData->id)->get();
        $paymentHistory = PaymentHistory::whereIn('invoice_id', $invoices->pluck('id'))->get();
        $adminUserData->total_buy = $invoices->sum('grand_total');
        $adminUserData->total_due = $invoices->sum('due');
        $adminUserData->total_invoice = $invoices->count();
        $adminUserData->total_transaction = $paymentHistory->count();
        $adminUserData->total_paid = $paymentHistory->sum('amount');

        $this->adminUserData = $adminUserData;
        $this->invoices = $invoices;
    }


    // public function hydrate()
    // {
    //     if (!$this->customer) {
    //         $this->customer = request()->route('customer') ?? 'Customer'; // রুট থেকে বা ডিফল্ট সেট করুন
    //     }
    // }



    // Updated rules method
    // public function role()
    // {
    //     return [
    //         'name' => 'required|string|max:255',
    //         // 'email' => 'required|email|max:255|unique:users,email,'.$this->customerId,
    //         // 'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->customerId)],
    //         'email' => ['required', 'email', 'max:255',
    //             function ($attribute, $value, $fail) {
    //                 $users = User::where('email', $value)->where('id', '!=', $this->customerId)->first();
    //                 if ($users) {
    //                     $fail('The email address is already associated with a '.ucfirst($users->role).' role. Please use a different email address');
    //                 }
    //             }
    //         ],
    //         'mobile' => 'required|numeric|digits:11',
    //         'address' => 'required|string|max:255',
    //         'balance' => 'nullable|numeric',
    //         'field_officer_team' => 'required_if:customer,Customer',

    //     ];
    // }
    // public function messages()
    // {
    //     return [
    //         'field_officer_team.required_if' => 'The field officer is required.',
    //     ];
    // }

    // public function submit()
    // {
    //     $this->validate($this->role());

    //     try {
    //         User::updateOrCreate(
    //             ['id' => $this->customerId],
    //             [
    //                 'name' => $this->name,
    //                 'email' => $this->email,
    //                 'password' => Hash::make($this->email.$this->mobile.$this->name),
    //                 'mobile' => $this->mobile,
    //                 'address' => $this->address,
    //                 'balance' => $this->balance ?? 0.00,
    //                 'role' => $this->customer,
    //             ]
    //         );
    //         $this->reset();
    //         flash()->success('Customer added successfully!');
    //     } catch (\Exception $e) {
    //         flash()->error('Error: ' . $e->getMessage());
    //         return;
    //     }
    // }

    // public function edit($id)
    // {
    //     $customer = User::find($id);
    //     if ($customer) {
    //         $this->customerId = $id;
    //         $this->name = $customer->name;
    //         $this->email = $customer->email;
    //         $this->mobile = $customer->mobile;
    //         $this->address = $customer->address;
    //         $this->balance = $customer->balance;
    //         $this->supplier_type = $customer->supplier_type;
    //     }else {
    //         flash()->error('Something went wrong!');
    //     }
    // }
}

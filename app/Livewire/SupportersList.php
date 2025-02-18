<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TargetReport;
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

    public $type, $adminUserData, $site_settings, $invoices, $selectedInvoice, $zses, $tses, $customers, $zse_id, $tse_id, $customer_id, $target_edit, $sales_target, $start_date, $end_date,      $customerId, $name, $email, $mobile, $address, $balance, $search,  $tse_team;


    public function mount($type)
    {
        $this->type = $type;
        // Validate the type type
        if (!in_array($type, ['manager', 'zse','tse'])) {
            abort(403, 'Access Denied: Invalid Admin Type');
        }
        return true;
    }

    public function render()
    {
        $this->site_settings = SiteSetting::first();
        // Fetch admin_users based on search and role
        $admin_user = User::query()->search($this->search);
            if($this->type == 'manager'){
                $admin_user = $admin_user->where('role', 'Manager');
                if(auth()->user()->hasRole('Manager')) {
                    $admin_user = $admin_user->where('id', auth()->user()->id);
                }
            }elseif($this->type == 'zse'){
                $admin_user = $admin_user->where('role', 'Zonal Sales Executive');
                if(auth()->user()->hasRole('Manager')) {
                    $admin_user = $admin_user->where('manager_id', auth()->user()->id);
                }
                if(auth()->user()->hasRole('Zonal Sales Executive')) {
                    $admin_user = $admin_user->where('id', auth()->user()->id);
                }
            }elseif($this->type == 'tse'){
                $admin_user = $admin_user->where('role', 'Territory Sales Executive');
                if(auth()->user()->hasRole('Manager')) {
                    $admin_user = $admin_user->where('manager_id', auth()->user()->id);
                }
                if(auth()->user()->hasRole('Zonal Sales Executive')) {
                    $admin_user = $admin_user->where('zse_id', auth()->user()->id);
                }
                if(auth()->user()->hasRole('Territory Sales Executive')) {
                    $admin_user = $admin_user->where('id', auth()->user()->id);
                }
            }

        $admin_users = $admin_user->paginate(10);

        return view('livewire.supporters-list', ['admin_users' => $admin_users])->layout('layouts.app');
    }

    public function view($id = null) {
        $adminUserData = User::find($id);
        $invoices = Invoice::where($this->type . '_id', $adminUserData->id)->with(['salesReturnMedicines'])->get();

        $adminUserData->total_sales = $invoices->sum('grand_total');
        $adminUserData->total_invoice = $invoices->count();
        $adminUserData->total_paid = $invoices->sum('paid');
        $adminUserData->total_return = $invoices->flatMap->salesReturnMedicines->sum('total');
        $adminUserData->total_due = $invoices->sum('due');
        $this->adminUserData = $adminUserData;
        $this->updateInvoiceList($adminUserData->id);
    }

    public function edit($id = null) {
        $this->target_edit = User::find($id);
        $this->sales_target = $this->target_edit->sales_target;
    }

    public function targetUpdate($id) {
        $user = User::find($id);
        $user->sales_target = $this->sales_target;
        $user->save();
        TargetReport::where('user_id', $user->id)->where('target_month', date('F'))->where('target_year', date('Y'))->update(['sales_target' => $this->sales_target]);
        flash()->success('Sales Target Updated Successfully');
    }

    public function updateInvoiceList($id = null) {
        // Use relationships properly
        $zses = User::where('role', 'Zonal Sales Executive')->where($this->type . '_id', $id);
        $tses = User::where('role', 'Territory Sales Executive')->where($this->type . '_id', $id);
        $customers = User::where('role', 'Customer')->where($this->type . '_id', $id);

        $invoices = Invoice::where($this->type . '_id', $id);
        if($this->zse_id != null){
            $invoices = $invoices->where('zse_id', $this->zse_id);
            $tses = $tses->where('zse_id', $this->zse_id);
            $customers = $customers->where('zse_id', $this->zse_id);
            $this->tses = $tses->get() ?? null;
        }else{
            $this->tse_id = null;
            $this->customer_id = null;
            $this->tses = [];
            $this->customers = [];
        }

        if($this->tse_id != null){
            $invoices = $invoices->where('tse_id', $this->tse_id);
            $customers = $customers->where('tse_id', $this->tse_id);
            $this->customers = $customers->get() ?? null;
        }else{
            $this->customer_id = null;
            $this->customers = [];
        }

        if($this->customer_id != null){
            $invoices = $invoices->where('customer_id', $this->customer_id);
        }
        if($this->start_date){
            $invoices = $invoices->where('invoice_date', '<', $this->start_date);
        }
        if($this->end_date){
            $invoices = $invoices->where('invoice_date', '>', $this->end_date);
        }
        $this->invoices = $invoices->get() ?? null;
        if($this->type == 'manager'){
            $this->zses = $zses->get() ?? null;
        }elseif($this->type == 'zse'){
            $this->zse_id = $id;
            $this->tses = $tses->get() ?? null;
        }elseif($this->type == 'tse'){
            $this->zse_id = User::find($id)->zse_id;
            $this->tse_id = $id;
            $this->customers = $customers->get() ?? null;
        }
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
    //         'tse_team' => 'required_if:customer,Customer',

    //     ];
    // }
    // public function messages()
    // {
    //     return [
    //         'tse_team.required_if' => 'The Territory Sales Executive is required.',
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

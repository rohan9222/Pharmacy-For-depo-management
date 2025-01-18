<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class SupportersList extends Component
{
    use WithPagination;
    public $customer, $customerId, $name, $email, $mobile, $address, $balance, $search, $field_officers, $field_officer_team;

    public function mount($customer)
    {
        $this->customer = $customer;
        // Validate the customer type
        if (!in_array($customer, ['Customer', 'Delivery Man']) && auth()->user()->cannot('create-customer')) {
            abort(403, 'Access Denied: Invalid Customer Type');
        }

    }

    public function hydrate()
    {
        if (!$this->customer) {
            $this->customer = request()->route('customer') ?? 'Customer'; // রুট থেকে বা ডিফল্ট সেট করুন
        }
    }


    public function render()
    {
        $this->field_officers = User::select('id', 'name')->role('Field Officer')->get(); // Pre-fetch Field Officers
        // Fetch customers based on search and role
        $customers = User::query()
            ->search($this->search)
            ->where('role', $this->customer)
            ->paginate(10);

        return view('livewire.supporters-list', ['customers' => $customers])->layout('layouts.app');
    }

    // Updated rules method
    public function role()
    {
        return [
            'name' => 'required|string|max:255',
            // 'email' => 'required|email|max:255|unique:users,email,'.$this->customerId,
            // 'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->customerId)],
            'email' => ['required', 'email', 'max:255',
                function ($attribute, $value, $fail) {
                    $users = User::where('email', $value)->where('id', '!=', $this->customerId)->first();
                    if ($users) {
                        $fail('The email address is already associated with a '.ucfirst($users->role).' role. Please use a different email address');
                    }
                }
            ],
            'mobile' => 'required|numeric|digits:11',
            'address' => 'required|string|max:255',
            'balance' => 'nullable|numeric',
            'field_officer_team' => 'required_if:customer,Customer',

        ];
    }
    public function messages()
    {
        return [
            'field_officer_team.required_if' => 'The field officer is required.',
        ];
    }

    public function submit()
    {
        $this->validate($this->role());

        try {
            User::updateOrCreate(
                ['id' => $this->customerId],
                [
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->email.$this->mobile.$this->name),
                    'mobile' => $this->mobile,
                    'address' => $this->address,
                    'balance' => $this->balance ?? 0.00,
                    'role' => $this->customer,
                ]
            );
            $this->reset();
            flash()->success('Customer added successfully!');
        } catch (\Exception $e) {
            flash()->error('Error: ' . $e->getMessage());
            return;
        }
    }

    public function edit($id)
    {
        $customer = User::find($id);
        if ($customer) {
            $this->customerId = $id;
            $this->name = $customer->name;
            $this->email = $customer->email;
            $this->mobile = $customer->mobile;
            $this->address = $customer->address;
            $this->balance = $customer->balance;
            $this->supplier_type = $customer->supplier_type;
        }else {
            flash()->error('Something went wrong!');
        }
    }

    public function delete($id)
    {
        $customer = User::find($id);
        if ($customer) {
            $customer->forceDelete();
            flash()->success('Customer deleted successfully!');
        }else {
            flash()->error('Something went wrong!');
        }
    }

}

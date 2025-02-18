<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

use Livewire\Component;

class DeliveryManList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $customerId, $name, $email, $mobile, $address, $balance, $supplier_type, $search;

    public function mount()
    {
        if(auth()->user()->hasRole('Super Admin')) {
            return true;
        }
        if (!auth()->user()->hasAnyPermission(['create-delivery-man', 'edit-delivery-man', 'delete-delivery-man'])) {
            abort(403, 'Unauthorized action.');
        }
        return true;
    }

    public function render()
    {
        $customers = User::search($this->search)->where('role', 'Delivery Man')->paginate(10);

        return view('livewire.delivery-man-list', ['customers' => $customers])->layout('layouts.app');
    }

    // Updated rules method
    public function role()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255',
                function ($attribute, $value, $fail) {
                    $users = User::where('email', $value)->where('id', '!=', $this->customerId)->first();
                    if ($users) {
                        $fail('The email address is already associated with a '.ucfirst($users->role).' list. Please use a different email address');
                    }
                }
            ],
            'mobile' => 'required|numeric|digits:11',
            'address' => 'required|string|max:255',
            'balance' => 'nullable|numeric',
        ];
    }

    public function submit()
    {
        $this->validate($this->role());

        try {
            $latestInvoiceNo = User::orderByDesc('user_id')->value('user_id');
            $user_id = ($latestInvoiceNo) ? ((int) filter_var($latestInvoiceNo, FILTER_SANITIZE_NUMBER_INT) + 1) : 010500;
            User::updateOrCreate(
                ['id' => $this->customerId],
                [
                    'user_id' => $user_id,
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->email.$this->mobile.$this->name),
                    'mobile' => $this->mobile,
                    'address' => $this->address,
                    'balance' => $this->balance,
                    'role' => 'Delivery Man',
                ]
            );
            $this->reset();
            flash()->success('Delivery Man added successfully!');
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
        }else {
            flash()->error('Something went wrong!');
        }
    }

    public function delete($id)
    {
        $customer = User::find($id);
        if ($customer) {
            $customer->forceDelete();
            flash()->success('Delivery Man deleted successfully!');
        }else {
            flash()->error('Something went wrong!');
        }
    }
}

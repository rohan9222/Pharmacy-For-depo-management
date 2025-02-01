<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Invoice;
use App\Models\PaymentHistory;
use App\Models\SiteSetting;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

use Livewire\Component;

class CustomersList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $site_settings, $customerId, $name, $email, $mobile, $address, $balance, $supplier_type, $route, $category, $search, $field_officers, $field_officer_team, $customerData, $invoices, $invoiceDue;
    // public $invoices;
    public $selectedInvoice;
    public $amount;

    // protected $listeners = ['openModal' => 'setInvoice'];
    public function mount()
    {
        if (auth()->user()->hasRole('Super Admin')) {
            return true;
        }

        if (!auth()->user()->hasAnyPermission(['create-customer', 'edit-customer', 'delete-customer'])) {
            abort(403, 'Unauthorized action.');
        }
        return true;
    }

    public function render()
    {
        $field_officers = User::select('id', 'name')->role('Field Officer');
        if (auth()->user()->hasRole('Manager')) {
            $field_officers = $field_officers->where('manager_id', auth()->user()->id);
        } elseif (auth()->user()->hasRole('Sales Manager')) {
            $field_officers = $field_officers->where('sales_manager_id', auth()->user()->id);
        } elseif (auth()->user()->hasRole('Field Officer')) {
            $field_officers = $field_officers->where('id', auth()->user()->id);
        }
        $this->field_officers = $field_officers->get();
        $customers = User::search($this->search)->with('fieldOfficer')->where('role', 'Customer')->paginate(10);
        $this->site_settings = SiteSetting::first();

        return view('livewire.customers-list', ['customers' => $customers])->layout('layouts.app');
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
            'route' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'field_officer_team' => ['required','exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value); // Retrieve the user once to avoid multiple queries
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

            $latestInvoiceNo = User::orderByDesc('user_id')->value('user_id');
            $user_id = ($latestInvoiceNo) ? ((int) filter_var($latestInvoiceNo, FILTER_SANITIZE_NUMBER_INT) + 1) : 010500;
            $newCustomer = User::updateOrCreate(
                ['id' => $this->customerId],
                [
                    'user_id' => $user_id,
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->email.$this->mobile.$this->name),
                    'mobile' => $this->mobile,
                    'address' => $this->address,
                    'balance' => $this->balance ?? 0.00,
                    'role' => 'Customer',
                    'route' => $this->route,
                    'category' => $this->category,
                    'field_officer_id' => $this->field_officer_team,
                    'sales_manager_id' => User::where('id', $this->field_officer_team)->first()->sales_manager_id,
                    'manager_id' => User::where('id', $this->field_officer_team)->first()->manager_id,
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
            $this->route = $customer->route;
            $this->category = $customer->category;
            $this->field_officer_team = $customer->field_officer_id;
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

    public function view($id = null){
        $customerData = User::find($id);
        $invoices = Invoice::where('customer_id', $customerData->id)->get();
        $paymentHistory = PaymentHistory::whereIn('invoice_id', $invoices->pluck('id'))->get();
        $customerData->total_buy = $invoices->sum('grand_total');
        $customerData->total_due = $invoices->sum('due');
        $customerData->total_invoice = $invoices->count();
        $customerData->total_transaction = $paymentHistory->count();
        $customerData->total_paid = $paymentHistory->sum('amount');

        $this->customerData = $customerData;
        $this->invoices = $invoices;
    }

    public function setInvoice($invoiceId, $customerId)
    {
        $this->view($customerId);
        $this->selectedInvoice = Invoice::find($invoiceId);
        $this->amount = '';
    }

    public function payDue()
    {
        $this->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        if ($this->selectedInvoice) {
            $this->selectedInvoice->paid += $this->amount;
            $this->selectedInvoice->due -= $this->amount;
            $this->selectedInvoice->save();

            PaymentHistory::create([
                'invoice_id' => $this->selectedInvoice->id,
                'amount' => $this->amount,
                'date' => now()
            ]);

            $this->amount = '';

            flash()->success('Amount paid successfully.');
            // $this->dispatch('closeModal');

        }else {
            flash()->error('Something went wrong!');
        }
    }

}

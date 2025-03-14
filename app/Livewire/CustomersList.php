<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Invoice;
use App\Models\PaymentHistory;
use App\Models\SiteSetting;
use App\Models\DiscountValue;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

use Livewire\Component;

class CustomersList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $site_settings, $customerId, $name, $email, $mobile, $address, $balance, $supplier_type, $route, $category, $search, $tses, $tse_team, $customerData, $invoices, $invoiceDue, $selectedInvoice, $partialPayment, $amount;

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
        $tses = User::select('id', 'name')->role('Territory Sales Executive');
        if (auth()->user()->hasRole('Manager')) {
            $tses = $tses->where('manager_id', auth()->user()->id);
            $customers = User::search($this->search)->where('manager_id', auth()->user()->id)->with('fieldOfficer')->where('role', 'Customer')->paginate(10);
        } elseif (auth()->user()->hasRole('Zonal Sales Executive')) {
            $tses = $tses->where('zse_id', auth()->user()->id);
            $customers = User::search($this->search)->where('zse_id', auth()->user()->id)->with('fieldOfficer')->where('role', 'Customer')->paginate(10);
        } elseif (auth()->user()->hasRole('Territory Sales Executive')) {
            $tses = $tses->where('id', auth()->user()->id);
            $customers = User::search($this->search)->where('tse_id', auth()->user()->id)->with('fieldOfficer')->where('role', 'Customer')->paginate(10);
        }else {
            $customers = User::search($this->search)->with('fieldOfficer')->where('role', 'Customer')->paginate(10);
        }
        $this->tses = $tses->get();
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
            'tse_team' => ['required','exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value); // Retrieve the user once to avoid multiple queries
                    if (!$user || (!$user->zse_id && !$user->manager_id)) {
                        $fail('This Territory Sales Executive team does not exist or not assigned to any manager and Zonal Sales Executive.');
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
                    'tse_id' => $this->tse_team,
                    'zse_id' => User::where('id', $this->tse_team)->first()->zse_id,
                    'manager_id' => User::where('id', $this->tse_team)->first()->manager_id,
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
            $this->tse_team = $customer->tse_id;
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
        $invoices = Invoice::where('customer_id', $customerData->id)->with(['salesReturnMedicines'])->get();
        $paymentHistory = PaymentHistory::whereIn('invoice_id', $invoices->pluck('id'))->get();
        
        // Initialize totals
        $customerData->total_buy = $invoices->sum('grand_total');
        $customerData->total_return = 0;
        $customerData->total_due = 0;
    
        foreach ($invoices as $invoice) {
            $afterReturnPrice = $invoice->sub_total - $invoice->salesReturnMedicines->sum('total_price');
            $afterReturnVat = $invoice->vat - $invoice->salesReturnMedicines->sum('vat');
            $sumReturnTotal = $invoice->salesReturnMedicines->sum('total');

            $discount_data = json_decode($invoice->discount_data);
            $newDiscount = DiscountValue::where('discount_type', 'General')
                ->where('start_amount', '<=', $afterReturnPrice)
                ->where('end_amount', '>=', $afterReturnPrice)
                ->pluck('discount')
                ->first();

            if (!empty($discount_data) && $discount_data->start_amount <= $afterReturnPrice && $afterReturnPrice <= $discount_data->end_amount) {
                $afterReturnDis = ($afterReturnPrice * $invoice->discount) / 100;
                $afterReturnDue = ($afterReturnPrice - $afterReturnDis) + $afterReturnVat; 
            } elseif ($newDiscount !== null) {
                $afterReturnDue = ($afterReturnPrice + $afterReturnVat) - ($afterReturnPrice * $newDiscount / 100);
            } else {
                $afterReturnDue = $afterReturnPrice + $afterReturnVat;
            }
    
            // Ensure afterReturnDue is never negative
            $totalDue = round(max($afterReturnDue - $invoice->paid, 0), 2);

            $customerData->total_return += $sumReturnTotal;
            $customerData->total_due += $totalDue;
        }
    
        $customerData->total_invoice = $invoices->count();
        $customerData->total_transaction = $paymentHistory->count();
        $customerData->total_paid = $paymentHistory->sum('amount');
    
        $this->customerData = $customerData;
        $this->invoices = $invoices;
    }
    
    

    public function setInvoice($invoiceId)
    {
        $this->view($this->customerData->id);
        $this->selectedInvoice = Invoice::where('id', $invoiceId)->with('salesReturnMedicines')->first();
        $this->partialPayment = '';
        $this->amount = '';
    }

    public function partialPay($customerId)
    {
        $this->view($customerId);
        $this->partialPayment = $this->customerData->total_due;
        $this->selectedInvoice = null;
        $this->amount = '';
    }

    public function payDue()
    {
        $this->view($this->customerData->id);
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
        }elseif ($this->partialPayment){
            $inv = Invoice::where('customer_id', $this->customerData->id)->with('salesReturnMedicines')->where('due', '>', 0)->orderBy('id', 'desc')->get();
            $remainingPayment = $this->amount;
            foreach ($inv as $invoice) {
                if ($remainingPayment <= 0) {
                    break;
                }
                $afterReturnPrice = $invoice->sub_total - $invoice->salesReturnMedicines->sum('total_price');
                $afterReturnVat = $invoice->vat - $invoice->salesReturnMedicines->sum('vat');
                $sumReturnTotal = $invoice->salesReturnMedicines->sum('total');
    
                $discount_data = json_decode($invoice->discount_data);
                $newDiscount = DiscountValue::where('discount_type', 'General')
                    ->where('start_amount', '<=', $afterReturnPrice)
                    ->where('end_amount', '>=', $afterReturnPrice)
                    ->pluck('discount')
                    ->first();
    
                if (!empty($discount_data) && $discount_data->start_amount <= $afterReturnPrice && $afterReturnPrice <= $discount_data->end_amount) {
                    $afterReturnDis = ($afterReturnPrice * $invoice->discount) / 100;
                    $afterReturnDue = ($afterReturnPrice - $afterReturnDis) + $afterReturnVat; 
                } elseif ($newDiscount !== null) {
                    $afterReturnDue = ($afterReturnPrice + $afterReturnVat) - ($afterReturnPrice * $newDiscount / 100);
                } else {
                    $afterReturnDue = $afterReturnPrice + $afterReturnVat;
                }
    
                // Ensure afterReturnDue is never negative
                $afterReturnDue = round(max($afterReturnDue - $invoice->paid, 0), 2);
                
                $amountToPay = min($remainingPayment, $afterReturnDue); // Pay only what's needed to clear the due
                // Update invoice payment
                $invoice->paid += $amountToPay;
                $invoice->due -= $amountToPay;
                $invoice->save();
                // Store payment history
                PaymentHistory::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $amountToPay,
                    'date' => now()
                ]);
                // Reduce the remaining payment amount
                $remainingPayment -= $amountToPay;
            }
            // Reset input fields
            $this->amount = '';

            flash()->success('Amount paid successfully.');
        }else{
            flash()->error('Something went wrong!');
        }
        $this->view($this->customerData->id);
    }

}

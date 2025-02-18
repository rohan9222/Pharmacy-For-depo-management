<?php

namespace App\Models;

use App\Models\SalesMedicine;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'customer_id',
        'field_officer_id',
        'sales_manager_id',
        'manager_id',
        'sub_total',
        'vat',
        'discount',
        'dis_type',
        'dis_amount',
        'spl_discount',
        'spl_dis_type',
        'spl_dis_amount',
        'grand_total',
        'paid',
        'due',
        'delivery_status',
        'delivery_date',
        'delivery_by',
        'summary_id',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('invoice_no', 'like', '%' . $search . '%')
            ->orWhere('invoice_date', 'like', '%' . $search . '%')
            ->orWhere('customer_id', 'like', '%' . $search . '%')
            ->orWhere('field_officer_id', 'like', '%' . $search . '%')
            ->orWhere('sales_manager_id', 'like', '%' . $search . '%')
            ->orWhere('manager_id', 'like', '%' . $search . '%')
            ->orWhere('invoice_no', 'like', '%' . $search . '%')
            ->orWhere('delivery_date', 'like', '%' . $search . '%')
            ->orWhere('summary_id', 'like', '%' . $search . '%')
            ->orWhere('delivery_by', 'like', '%' . $search . '%');
    }

    public function salesMedicines()
    {
        return $this->hasMany(SalesMedicine::class);
    }

    public function salesReturnMedicines()
    {
        return $this->hasMany(ReturnMedicine::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function fieldOfficer()
    {
        return $this->belongsTo(User::class, 'field_officer_id');
    }

    public function salesManager()
    {
        return $this->belongsTo(User::class, 'sales_manager_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'delivery_by');
    }
    
    public function paymentHistory()
    {
        return $this->hasMany(PaymentHistory::class, 'invoice_id');
    }
}

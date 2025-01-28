<?php

namespace App\Models;

use App\Models\SalesMedicine;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'customer',
        'field_officer',
        'sales_manager',
        'manager',
        'sub_total',
        'vat',
        'spl_discount',
        'spl_dis_type',
        'spl_dis_amount',
        'grand_total',
        'paid',
        'due'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('invoice_no', 'like', '%' . $search . '%');
    }

    public function salesMedicines()
    {
        return $this->hasMany(SalesMedicine::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer');
    }

    public function fieldOfficer()
    {
        return $this->belongsTo(User::class, 'field_officer');
    }

    public function salesManager()
    {
        return $this->belongsTo(User::class, 'sales_manager');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}

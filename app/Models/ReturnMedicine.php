<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnMedicine extends Model
{
    protected $fillable = [
        'invoice_id',
        'medicine_id',
        'sales_medicine_id',
        'quantity',
        'price',
        'total',
    ];

    // Defining relationships
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

    public function invoiceData()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    // Search scope with improvements
    public function scopeSearch($query, $search)
    {
        return $query->where('invoice_id', 'like', '%' . $search . '%')
            ->orWhere('medicine_id', 'like', '%' . $search . '%')
            ->orWhere('sales_medicine_id', 'like', '%' . $search . '%')
            ->orWhere('quantity', '=', $search)  // Exact match for quantity
            ->orWhere('price', '=', $search)     // Exact match for price
            ->orWhere('total', '=', $search);    // Exact match for total
    }
}

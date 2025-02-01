<?php

namespace App\Models;

use App\Models\Medicine;

use Illuminate\Database\Eloquent\Model;

class StockList extends Model
{
    protected $fillable = [
        'medicine_id',
        'stock_invoice_id',
        'batch_number',
        'expiry_date',
        'initial_quantity',
        'quantity',
        'price',
        'buy_price',
        'vat',
        'total',
        'discount',
        'dis_type',
        'dis_amount',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('batch_number', 'like', '%' . $search . '%')
                ->orWhere('price', 'like', '%' . $search . '%');
        })->orWhereHas('medicine', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('generic_name', 'like', '%' . $search . '%')
                ->orWhere('category_name', 'like', '%' . $search . '%')
                ->orWhere('supplier', 'like', '%' . $search . '%')
                ->orWhere('barcode', 'like', '%' . $search . '%');
        });
    }

}

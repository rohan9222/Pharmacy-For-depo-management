<?php

namespace App\Models;

use App\Models\StockList;

use Illuminate\Database\Eloquent\Model;

class StockInvoice extends Model
{
    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'supplier_id',
        'sub_total',
        'discount',
        'dis_type',
        'dis_amount',
        'vat',
        'total',
        'paid',
        'due',
        'payment_method',
    ];

    public function stockLists()
    {
        return $this->hasMany(StockList::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockList extends Model
{
    protected $fillable = [
        'medicine_id',
        'stock_invoice_id',
        'batch_number',
        'expiry_date',
        'quantity',
        'price',
        'buy_price',
        'vat',
        'total',
        'discount',
        'dis_type',
        'dis_amount',
    ];
}

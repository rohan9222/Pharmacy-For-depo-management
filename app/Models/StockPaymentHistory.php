<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockPaymentHistory extends Model
{

    protected $fillable = [
        'stock_invoice_id',
        'payment_methods_id',
        'amount',
        'date',
    ];
}

<?php

namespace App\Models;

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
}

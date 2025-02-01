<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    protected $fillable = [
        'invoice_id',
        'payment_methods_id',
        'amount',
        'date',
    ];
}

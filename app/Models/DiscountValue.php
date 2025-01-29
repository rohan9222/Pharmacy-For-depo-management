<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountValue extends Model
{
    protected $fillable = [
        'start_amount',
        'end_amount',
        'discount',
    ];
}

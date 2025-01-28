<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountValue extends Model
{
    protected $fillable = [
        'amount',
        'discount',
    ];
}

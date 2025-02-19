<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpeningStock extends Model
{
    protected $fillable = [
        'medicine_id',
        'opening_stock',
        'opening_month',
        'opening_year',
    ];
}

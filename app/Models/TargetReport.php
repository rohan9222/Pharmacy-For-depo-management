<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetReport extends Model
{
    protected $fillable = [
        'user_id',
        'product_target',
        'target_month',
        'target_year'
    ];
}

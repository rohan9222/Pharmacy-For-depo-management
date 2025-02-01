<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesMedicine extends Model
{
    protected $fillable = [
        'invoice_id',
        'medicine_id',
        'initial_quantity',
        'quantity',
        'price',
        'vat',
        'total',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}

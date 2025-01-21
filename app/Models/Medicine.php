<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Medicine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'barcode',
        'name',
        'generic_name',
        'description',
        'shelf',
        'category_name',
        'supplier',
        'image_url',
        'stock',
        'price',
        'supplier_price',
        'vat',
        'status',
        'quantity',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->barcode)) {
                $model->barcode = static::generateUniqueBarcode();
            }
        });
    }

    // Method to generate a unique barcode
    public static function generateUniqueBarcode()
    {
        do {
            $barcode = Str::random(12); // Generates a random 12-character string
        } while (self::where('barcode', $barcode)->exists());

        return $barcode;
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('generic_name', 'like', '%' . $search . '%')
            ->orWhere('category_name', 'like', '%' . $search . '%')
            ->orWhere('supplier', 'like', '%' . $search . '%')
            ->orWhere('barcode', 'like', '%' . $search . '%');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use SoftDeletes;

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('generic_name', 'like', '%' . $search . '%')
            ->orWhere('category_name', 'like', '%' . $search . '%')
            ->orWhere('supplier', 'like', '%' . $search . '%')
            ->orWhere('bar_code', 'like', '%' . $search . '%');
    }
}

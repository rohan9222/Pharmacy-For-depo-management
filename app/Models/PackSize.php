<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackSize extends Model
{
    protected $fillable = ['pack_name', 'pack_size', 'description','status'];

    public function scopeSearch($query, $search)
    {
        return $query->where('pack_name', 'like', '%' . $search . '%');
    }
}

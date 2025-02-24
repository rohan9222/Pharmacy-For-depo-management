<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetReport extends Model
{
    protected $fillable = [
        'user_id',
        'manager',
        'zse',
        'tse',
        'product_target',
        'product_target_data',
        'product_target_achieve',
        'sales_target',
        'sales_target_achieve',
        'target_month',
        'target_year'
    ];

    public function userData(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function($query) use ($term){
            $query->where('target_month', 'like', $term)
                ->orWhere('target_year', 'like', $term);
        });
    }
}

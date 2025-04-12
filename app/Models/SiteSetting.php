<?php

namespace App\Models;
use Illuminate\Support\Facades\Cache;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_title',
        'site_email',
        'site_phone',
        'site_address',
        'site_logo',
        'site_favicon',
        'site_description',
        'site_keywords',
        'site_author',
        'site_status',
        'site_maintenance',
        'site_currency',
        'medicine_expiry_alert',
        'medicine_expiry_days',
        'medicine_low_stock_alert',
        'medicine_low_stock_quantity',
    ];

    public static function getSettings()
    {
        return Cache::rememberForever('site_settings', function () {
            return self::first();
        });
    }
}

<?php
use App\Models\User;
use App\Models\TargetReport;
use App\Models\OpeningStock;
use App\Models\Medicine;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Foundation\Inspiring;

use Carbon\Carbon;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    User::all()->whereIn('role', ['Manager', 'Zonal Sales Executive', 'Territory Sales Executive'])->each(function ($user) {
        TargetReport::create([
            'user_id' => $user->id,
            'manager_id' => $user->manager_id ?? null,
            'zse_id' => $user->zse_id ?? null,
            'tse_id' => $user->tse_id ?? null,
            'product_target' => $user->product_target ?? 0,
            'product_target_data' => $user->product_target_data ?? null,
            'sales_target' => $user->sales_target ?? 0,
            'target_month' => Carbon::now()->format('F'),
            'target_year' => Carbon::now()->format('Y')
        ]);
    });
})->monthly()->timezone('Asia/Dhaka');

Schedule::call(function () {
    Medicine::all()->each(function ($medicine) {
        OpeningStock::create([
            'medicine_id' => $medicine->id,
            'opening_stock' => $medicine->quantity,
            'stock_month' => Carbon::now()->format('F'),
            'stock_year' => Carbon::now()->format('Y')
        ]);
    });
})->monthly()->timezone('Asia/Dhaka');

Schedule::call(function () {
    \Log::info('Cron Job'.Carbon::now());
})->everyMinute()->timezone('Asia/Dhaka');

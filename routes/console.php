<?php

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
            'role' => $input['role'],
            'sales_target' => $user->sales_target ?? 0,
            'target_month' => Carbon::now()->format('F'),
            'target_year' => Carbon::now()->format('Y')
        ]);
    });
})->monthly()->timezone('Asia/Dhaka');

Schedule::call(function () {
    \Log::info('Cron Job'.Carbon::now());
})->everyMinute()->timezone('Asia/Dhaka');

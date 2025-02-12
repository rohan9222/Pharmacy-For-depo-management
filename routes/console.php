<?php

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    User::all()->whereIn('role', ['Manager', 'Sales Manager', 'Field Officer'])->each(function ($user) {
        TargetReport::create([
            'user_id' => $user->id,
            'manager_id' => $user->manager_id ?? null,
            'sales_manager_id' => $user->sales_manager_id ?? null,
            'field_officer_id' => $user->field_officer_id ?? null,
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

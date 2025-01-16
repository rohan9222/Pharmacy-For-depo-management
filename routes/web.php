<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController,UserProfileController};
use App\Http\Controllers\Admin\{RoleController,UserController};
Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
// profile
    Route::get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');
    Route::post('/user/profile/upload', [UserProfileController::class, 'uploadFile'])->name('user.profile.upload');
    Route::get('/user/profile/update', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/user/password/update', [UserProfileController::class, 'updatePassword'])->name('user.password.update');
// user and role management
    Route::resources([
        'dashboard' => DashboardController::class,
        'roles' => RoleController::class,
        'users' => UserController::class,
    ]);
});

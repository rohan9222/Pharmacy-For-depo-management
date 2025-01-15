<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\{UserProfileController};
use App\Http\Controllers\Admin\{RoleController,UserController};
Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
// profile
    // Route::get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');
    // Route::post('/user/profile/upload', [UserProfileController::class, 'uploadFile'])->name('user.profile.upload');
    // Route::get('/user/profile/update', [UserProfileController::class, 'update'])->name('user.profile.update');
    // Route::get('/user/password/update', [UserProfileController::class, 'updatePassword'])->name('user.password.update');
// user and role management
    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
    ]);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController,UserProfileController};
use App\Http\Controllers\Admin\{RoleController,UserController};
use App\Livewire\{CustomersList,DeliveryManList,SupplierList,MedicinesList,CategoryList};


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

// Supporters management
    Route::get('/customers', CustomersList::class)->name('customers');
    Route::get('/delivery-man', DeliveryManList::class)->name('delivery-man');
    // Route::get('/delivery-man/supporter', CustomersList::class)->name('supporter.customers');

// supplier management
    Route::get('/suppliers', SupplierList::class)->name('suppliers');

// medicine management
    Route::get('/medicines', MedicinesList::class)->name('medicines');

// category management
    Route::get('/categories', CategoryList::class)->name('categories');

// profile
    Route::get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');
    Route::post('/user/profile/upload', [UserProfileController::class, 'uploadFile'])->name('user.profile.upload');
    Route::get('/user/profile/update', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/user/password/update', [UserProfileController::class, 'updatePassword'])->name('user.password.update');
// user and role management
    Route::get('/users/sales-managers', [UserController::class, 'salesManagers'])->name('users.sales-managers');
    Route::resources([
        'dashboard' => DashboardController::class,
        'roles' => RoleController::class,
        'users' => UserController::class,
    ]);
});

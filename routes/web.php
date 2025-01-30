<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController,UserProfileController};
use App\Http\Controllers\Admin\{RoleController,UserController};
use App\Http\Controllers\makepdf\MakepdfController;
use App\Livewire\{CustomersList,DeliveryManList,SupplierList,MedicinesList,CategoryList, PackSizeList, StockMedicines,StockMedicinesList,SalesInvoice,InvoiceHistory,SiteSettings};


Route::get('/', function () {
    return redirect()->route('login');
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

// category management
    Route::get('/categories', CategoryList::class)->name('categories');
    Route::get('/pack-size', PackSizeList::class)->name('pack-size');

// medicine management
    Route::get('/medicines', MedicinesList::class)->name('medicines');
    Route::get('/stock-medicines', StockMedicines::class)->name('stock-medicines');
    Route::get('/stock-medicines-list', StockMedicinesList::class)->name('stock-medicines-list');

// pos
    Route::get('/pos', SalesInvoice::class)->name('pos');
    Route::get('/sales-medicines', InvoiceHistory::class)->name('sales-medicines');
    Route::get('/sales-medicines-list', InvoiceHistory::class)->name('sales-medicines-list');

// site settings
    Route::get('/site-settings', SiteSettings::class)->name('site-settings');

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

// invoice
    Route::get('/invoice/{invoice}', [MakepdfController::class,'invoicePDF'])->name('invoice');
});

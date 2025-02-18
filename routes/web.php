<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController,UserProfileController};
use App\Http\Controllers\Admin\{RoleController,UserController};
use App\Http\Controllers\makepdf\{MakeInvoiceController,MakeSummaryController,MakeReportController};
use App\Livewire\{SupportersList,CustomersList,DeliveryManList,SupplierList,MedicinesList,CategoryList, PackSizeList, StockMedicines,StockMedicinesList,SalesInvoice,InvoiceHistory,DeliveryHistory,InvoiceReturnHistory,SiteSettings,TargetHistory,DueInvoiceList,CollectionReport};


Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/php-artisan-optimize', function () {
    $commands = [
        'config:cache',
        'route:cache',
        'view:cache',
        'cache:clear',
        'event:cache',
        'compiled:clear',
        // Add more commands as needed
    ];

    $output = [];
    foreach ($commands as $command) {
        try {
            Artisan::call($command);
            $output[$command] = Artisan::output();
        } catch (\Exception $e) {
            $output[$command] = $e->getMessage();
        }
    }

    return response()->json($output);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
// user and role management
    Route::get('/users/zonal-sales-executives', [UserController::class, 'salesManagers'])->name('users.zonal-sales-executives');
    Route::resources([
        'dashboard' => DashboardController::class,
        'roles' => RoleController::class,
        'users' => UserController::class,
    ]);

// Supporters management
    Route::get('/customers', CustomersList::class)->name('customers');
    Route::get('/delivery-man', DeliveryManList::class)->name('delivery-man');
    Route::get('/admin-person/{type}', SupportersList::class)->name('supporter.list');

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
    Route::get('/sales-medicines-table', [InvoiceHistory::class, 'invoiceList'])->name('sales-medicines-table');
    Route::get('/sales-medicines-list', InvoiceHistory::class)->name('sales-medicines-list');
    Route::get('/sales-delivery-history', DeliveryHistory::class)->name('sales-delivery-history');
    Route::get('/sales-return-medicines-table', [InvoiceReturnHistory::class, 'invoiceReturnList'])->name('return-medicines-table');
    Route::get('/sales-return-medicines-list', InvoiceReturnHistory::class)->name('return-medicines-list');

// site settings
    Route::get('/site-settings', SiteSettings::class)->name('site-settings');

// profile
    Route::get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');
    Route::post('/user/profile/upload', [UserProfileController::class, 'uploadFile'])->name('user.profile.upload');
    Route::get('/user/profile/update', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/user/password/update', [UserProfileController::class, 'updatePassword'])->name('user.password.update');

// summary
    Route::get('/target-history', TargetHistory::class)->name('target-history');
    Route::get('/due-list-table', [DueInvoiceList::class, 'invoiceDueList'])->name('due-list-table');
    Route::get('/due-list', DueInvoiceList::class)->name('due-list');
    Route::get('/collection-list-table', [CollectionReport::class, 'invoiceCollectionList'])->name('collection-list-table');
    Route::get('/collection-list', CollectionReport::class)->name('collection-list');

// pdf generate
    Route::get('/invoice/print/{invoice}', [MakeInvoiceController::class,'invoicePrint'])->name('invoice.print');
    Route::get('/invoice/return/print/{invoice}', [MakeInvoiceController::class,'invoiceReturnPrint'])->name('invoice.return.print');
    Route::get('/invoice/{invoice}', [MakeInvoiceController::class,'invoicePDF'])->name('invoice.pdf');
    Route::get('/summary/{id}', [MakeSummaryController::class,'summaryPDF'])->name('summary.pdf');
    Route::get('/report/{report}', [MakeReportController::class,'reportPDF'])->name('report.pdf');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\{RoleController,UserController};
use App\Http\Controllers\makepdf\{MakeInvoiceController,MakeSummaryController,MakeReportController};
use App\Livewire\{SupportersList,CustomersList,DeliveryManList,SupplierList,MedicinesList,CategoryList, PackSizeList, StockMedicines,StockInvoiceList,StockMedicinesList,SalesInvoice,InvoiceHistory,DeliveryHistory,InvoiceReturnHistory,SiteSettings,TargetHistory,DueInvoiceList,CollectionReport,ReportGenerate,ProductTarget,CustomerDueList};


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
    Route::get('/product-target/manage', ProductTarget::class)->name('product-target-manage');

// supplier management
    Route::get('/suppliers', SupplierList::class)->name('suppliers');

// category management
    Route::get('/categories', CategoryList::class)->name('categories');
    Route::get('/pack-size', PackSizeList::class)->name('pack-size');

// medicine management
    Route::get('/medicines', MedicinesList::class)->name('medicines');
    Route::get('/stock-medicines', StockMedicines::class)->name('stock-medicines');
    Route::get('/stock-invoice-list', StockInvoiceList::class)->name('stock-invoice-list');
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

// summary
    Route::get('/target-history', TargetHistory::class)->name('target-history');
    Route::get('/due-list-table', [DueInvoiceList::class, 'invoiceDueList'])->name('due-list-table');
    Route::get('/due-list', DueInvoiceList::class)->name('due-list');
    Route::get('/customer-due-list-table', [CustomerDueList::class, 'customerDueList'])->name('customer-due-list-table');
    Route::get('/customer-due-list', CustomerDueList::class)->name('customer-due-list');
    Route::get('/collection-list-table', [CollectionReport::class, 'invoiceCollectionList'])->name('collection-list-table');
    Route::get('/collection-list', CollectionReport::class)->name('collection-list');

// pdf generate
    Route::get('/invoice/print/{invoice}', [MakeInvoiceController::class,'invoicePrint'])->name('invoice.print');
    Route::get('/invoice/return/print/{invoice}', [MakeInvoiceController::class,'invoiceReturnPrint'])->name('invoice.return.print');
    Route::get('/invoice/{invoice}', [MakeInvoiceController::class,'invoicePDF'])->name('invoice.pdf');
    Route::get('/summary/{id}', [MakeSummaryController::class,'summaryPDF'])->name('summary.pdf');

    // Route::get('/report', ReportGenerate::class)->name('report.index');
    
    Route::get('/report', [MakeReportController::class,'index'])->name('report.index');
    Route::get('/report/daily/sales-collection', function () {abort(404);});
    Route::post('/report/daily/sales-collection', [MakeReportController::class,'dailySalesCollection'])->name('report.daily.sales.collection');
    Route::get('/report/daily/product-sales-report', function () {abort(404);});
    Route::post('/report/daily/product-sales-report', [MakeReportController::class,'productSalesReport'])->name('report.daily.product.sales.report');
    Route::get('/report/stock/statement', function () {abort(404);});
    Route::post('/report/stock/statement', [MakeReportController::class,'stockStatement'])->name('report.stock.statement');
    Route::get('/medicine/print/qr-code', [MakeReportController::class,'printQrCode'])->name('medicine.print.qr-code');
});

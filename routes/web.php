<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\VisitController;
use App\Http\Controllers\Admin\ReceiptController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CustomerPhoneController;
use App\Http\Controllers\Admin\SalesStaffController;
use App\Http\Controllers\Admin\UploadUserController;
use App\Http\Controllers\Kasir\TransactionController;
use App\Http\Controllers\Kasir\ProductUploadController;
use App\Http\Controllers\CurrencyController;

// ── Root ─────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('owner') || $user->hasRole('admin'))
            return redirect()->route('dashboard.admin');
        if ($user->hasRole('kasir'))
            return redirect()->route('kasir.pos');
        if ($user->hasRole('security'))
            return redirect()->route('security.index');
    }
    return redirect()->route('login');
});

Route::get('/temp-reset-password', function() {
    \App\Models\UploadUser::query()->update([
        'password' => \Illuminate\Support\Facades\Hash::make('gempearlsupload2026')
    ]);
    return 'Done';
});
// ── Auth ─────────────────────────────────────
require __DIR__ . '/auth.php';

// ── Public ───────────────────────────────────
Route::get('/receipt/{token}', [TransactionController::class, 'publicReceipt'])->name('receipt.public');

// ── Upload (standalone, no auth) ─────────────
Route::get('/upload', [UploadController::class, 'showLogin'])->name('upload.login');
Route::post('/upload/login', [UploadController::class, 'login'])->name('upload.login.post');
Route::post('/upload/logout', [UploadController::class, 'logout'])->name('upload.logout');
Route::get('/upload/create', [UploadController::class, 'create'])->name('upload.create');
Route::post('/upload/create', [UploadController::class, 'store'])->name('upload.store');
Route::get('/upload/products', [\App\Http\Controllers\UploadController::class, 'products'])->name('upload.products');
Route::put('/upload/products/{product}', [\App\Http\Controllers\UploadController::class, 'update'])->name('upload.product.update');
Route::delete('/upload/products/{product}', [\App\Http\Controllers\UploadController::class, 'destroyProduct'])->name('upload.product.destroy');

Route::get('/komisi', [App\Http\Controllers\CommissionAccessController::class, 'showLogin'])->name('commission.login');
Route::post('/komisi/login', [App\Http\Controllers\CommissionAccessController::class, 'login'])->name('commission.login.post');
Route::post('/komisi/logout', [App\Http\Controllers\CommissionAccessController::class, 'logout'])->name('commission.logout');
Route::get('/komisi/data', [App\Http\Controllers\CommissionAccessController::class, 'index'])->name('commission.index');
Route::post('/komisi/{commission}/paid', [App\Http\Controllers\CommissionAccessController::class, 'markPaid'])->name('commission.paid');

// ─────────────────────────────────────────────
// AUTHENTICATED ROUTES
// ─────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // ── Dashboards ───────────────────────────
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin')->middleware('role:owner,admin');
    Route::get('/dashboard/kasir', function () {
        return redirect()->route('kasir.pos');
    })->name('dashboard.kasir')->middleware('role:kasir,owner,admin');

    Route::get('/dashboard/security', function () {
        return redirect()->route('security.index');
    })->name('dashboard.security')->middleware('role:security,owner,admin');

    // ── Admin ────────────────────────────────
    Route::prefix('admin')->name('admin.')->middleware('role:owner,admin')->group(function () {

        // Products
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/restock', [ProductController::class, 'restock'])->name('products.restock');
        Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');

        // Partners
        Route::resource('partners', PartnerController::class);
        Route::post('partners/{partner}/commission', [PartnerController::class, 'updateCommission'])->name('partners.commission');

        // Members
        Route::resource('members', MemberController::class);
        Route::post('members/{member}/adjust-points', [MemberController::class, 'adjustPoints'])->name('members.adjust-points');

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');

        // Visits
        Route::get('visits', [VisitController::class, 'index'])->name('visits.index');

        // Commissions
        Route::get('commissions', [CommissionController::class, 'index'])->name('commissions.index');
        Route::post('commissions', [CommissionController::class, 'store'])->name('commissions.store');
        Route::post('commissions/{commission}/paid', [CommissionController::class, 'markPaid'])->name('commissions.paid');
        Route::post('commissions/{commission}/update-rate', [CommissionController::class, 'updateRate'])->name('commissions.update-rate');
        Route::get('commissions/{commission}/pdf', [CommissionController::class, 'downloadPdf'])->name('commissions.pdf');
        Route::get('commissions/{commission}/view', [CommissionController::class, 'viewPdf'])->name('commissions.view');
        Route::post('commissions/{commission}/update-detail', [CommissionController::class, 'updateDetail'])->name('commissions.update-detail');
        Route::post('commissions/{commission}/attach-guide', [CommissionController::class, 'attachGuide'])->name('commissions.attach-guide');
        Route::delete('commissions/{commission}', [CommissionController::class, 'destroy'])->name('commissions.destroy');

        // Receipts
        Route::get('receipts', [ReceiptController::class, 'index'])->name('receipts.index');
        Route::post('receipts/{transaction}/print', [ReceiptController::class, 'markPrinted'])->name('receipts.print');
        Route::delete('receipts/{transaction}', [ReceiptController::class, 'destroy'])->name('receipts.destroy');

        // Tax
        Route::get('tax', [TaxController::class, 'index'])->name('tax.index');
        Route::post('tax', [TaxController::class, 'store'])->name('tax.store');
        Route::delete('tax/{tax}', [TaxController::class, 'destroy'])->name('tax.destroy');
        Route::get('tax/fetch-sales', [TaxController::class, 'fetchSales'])->name('tax.fetch-sales');

        // Transactions
        Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.delete');

        // Users
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
        Route::post('users/{user}/password', [UserController::class, 'updatePassword'])->name('users.password');

        // Customers
        Route::get('customers', [CustomerPhoneController::class, 'index'])->name('customers.index');
        Route::get('customers/export', [CustomerPhoneController::class, 'export'])->name('customers.export');

        // Sales Staff
        Route::get('sales-staff', [SalesStaffController::class, 'index'])->name('sales-staff.index');
        Route::post('sales-staff', [SalesStaffController::class, 'store'])->name('sales-staff.store');
        Route::put('sales-staff/{salesStaff}', [SalesStaffController::class, 'update'])->name('sales-staff.update');
        Route::delete('sales-staff/{salesStaff}', [SalesStaffController::class, 'destroy'])->name('sales-staff.destroy');

        // Upload Users
        Route::get('upload-users', [UploadUserController::class, 'index'])->name('upload-users.index');
        Route::post('upload-users', [UploadUserController::class, 'store'])->name('upload-users.store');
        Route::put('upload-users/{uploadUser}', [UploadUserController::class, 'update'])->name('upload-users.update');
        Route::delete('upload-users/{uploadUser}', [UploadUserController::class, 'destroy'])->name('upload-users.destroy');

        Route::get('commission-users', [\App\Http\Controllers\Admin\CommissionUserController::class, 'index'])->name('commission-users.index');
        Route::post('commission-users', [\App\Http\Controllers\Admin\CommissionUserController::class, 'store'])->name('commission-users.store');
        Route::put('commission-users/{commissionUser}', [\App\Http\Controllers\Admin\CommissionUserController::class, 'update'])->name('commission-users.update');
        Route::delete('commission-users/{commissionUser}', [\App\Http\Controllers\Admin\CommissionUserController::class, 'destroy'])->name('commission-users.destroy');

    });

    // ── Kasir ────────────────────────────────
    Route::prefix('kasir')->name('kasir.')->middleware('role:kasir,owner,admin')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('pos');
        Route::get('/search-product', [TransactionController::class, 'searchProduct'])->name('search.product');
        Route::get('/search-partner', [TransactionController::class, 'searchPartner'])->name('search.partner');
        Route::get('/search-member', [TransactionController::class, 'searchMember'])->name('search.member');
        Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout');
        Route::get('/receipt/{transaction}', [TransactionController::class, 'receipt'])->name('receipt');
        Route::post('/create-member', [TransactionController::class, 'storeMember']);
        Route::get('/receipt-data/{transaction}', [TransactionController::class, 'receiptData'])->name('receipt-data');
        Route::post('/print-raw', [TransactionController::class, 'printRaw'])->name('print-raw');
    });

    // ── Security ─────────────────────────────
    Route::prefix('security')->middleware('role:security,owner,admin')->group(function () {
        Route::get('/', [DashboardController::class, 'security'])->name('security.index');
        Route::post('visits/store', [DashboardController::class, 'storeVisit'])->name('security.visits.store');
        Route::post('partner/store', [DashboardController::class, 'storePartner'])->name('security.partner.store');
        Route::post('guides/store', [DashboardController::class, 'storeGuide'])->name('security.guides.store');
        Route::post('walkin/store', [DashboardController::class, 'storeWalkin'])->name('security.walkin.store');
        Route::get('search-guides', [DashboardController::class, 'searchGuides']);
        Route::post('visits/{visit}/status', [DashboardController::class, 'updateVisitStatus'])->name('security.visits.status');
        Route::put('visits/{visit}', [DashboardController::class, 'updateVisit'])->name('security.visits.update');
        Route::post('drivers/store', [DashboardController::class, 'storeDriver'])->name('security.drivers.store');
        Route::get('search-drivers', [DashboardController::class, 'searchDrivers']);
        Route::delete('visits/{visit}', [DashboardController::class, 'destroyVisit'])->name('security.visits.destroy');
    });
    Route::post('/komisi', [App\Http\Controllers\CommissionAccessController::class, 'store'])->name('commission.store');
    Route::delete('/komisi/{commission}', [App\Http\Controllers\CommissionAccessController::class, 'destroy'])->name('commission.destroy');
    Route::post('/komisi/{commission}/update-rate', [App\Http\Controllers\CommissionAccessController::class, 'updateRate'])->name('commission.update-rate');
    Route::post('/komisi/{commission}/update-detail', [App\Http\Controllers\CommissionAccessController::class, 'updateDetail'])->name('commission.update-detail');
    Route::get('/komisi/{commission}/pdf', [App\Http\Controllers\CommissionAccessController::class, 'downloadPdf'])->name('commission.pdf');
    Route::post('/komisi/{commission}/update-detail', [App\Http\Controllers\CommissionAccessController::class, 'updateDetail'])->name('commission.update-detail');

    // ── API ──────────────────────────────────
    Route::prefix('api')->group(function () {
        Route::get('currency-rates', [CurrencyController::class, 'getRates'])->name('currency.rates');
        Route::get('partner-visits', [TransactionController::class, 'partnerVisits']);
        Route::get('today-visits', [VisitController::class, 'todayVisits']);
    });



});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\VisitController;
use App\Http\Controllers\Admin\ReceiptController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Kasir\TransactionController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CustomerPhoneController;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('owner') || $user->hasRole('admin')) {
            return redirect()->route('dashboard.admin');
        }
        if ($user->hasRole('kasir')) {
            return redirect()->route('dashboard.kasir');
        }
        if ($user->hasRole('security')) {
            return redirect()->route('dashboard.security');
        }
    }
    return redirect()->route('login');
});

// Auth routes (login, logout, dll)
require __DIR__.'/auth.php';

// ─────────────────────────────────────────────
// AUTHENTICATED ROUTES
// ─────────────────────────────────────────────

// Public receipt
Route::get('/receipt/{token}', [TransactionController::class, 'publicReceipt'])->name('receipt.public');

Route::middleware(['auth'])->group(function () {

    // ── Dashboards ──────────────────────────
    Route::get('/dashboard/admin',    [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/kasir',    [TransactionController::class, 'index'])->name('dashboard.kasir');
    Route::get('/dashboard/security', [DashboardController::class, 'security'])->name('dashboard.security');

    // ── Admin ────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {

        // Products
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/restock',       [ProductController::class, 'restock'])->name('products.restock');
        Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');

        // Partners
        Route::resource('partners', PartnerController::class);
        Route::post('partners/{partner}/commission', [PartnerController::class, 'updateCommission'])->name('partners.commission');

        // Members
        Route::resource('members', MemberController::class);
        Route::post('members/{member}/adjust-points', [MemberController::class, 'adjustPoints'])->name('members.adjust-points');

        // Reports
        Route::get('reports',       [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');

        // Visits
        Route::get('visits', [VisitController::class, 'index'])->name('visits.index');

        // Commissions
        Route::get('commissions',                             [CommissionController::class, 'index'])->name('commissions.index');
        Route::post('commissions',                            [CommissionController::class, 'store'])->name('commissions.store');
        Route::post('commissions/{commission}/paid',          [CommissionController::class, 'markPaid'])->name('commissions.paid');
        Route::post('commissions/{commission}/update-rate',   [CommissionController::class, 'updateRate'])->name('commissions.update-rate');
        Route::get('commissions/{commission}/pdf',            [CommissionController::class, 'downloadPdf'])->name('commissions.pdf');
        Route::post('commissions/{commission}/update-detail', [CommissionController::class, 'updateDetail'])->name('commissions.update-detail');
        Route::post('commissions/{commission}/attach-guide',  [CommissionController::class, 'attachGuide'])->name('commissions.attach-guide');
        Route::delete('commissions/{commission}',             [CommissionController::class, 'destroy'])->name('commissions.destroy');

        // Receipts
        Route::get('receipts',                        [ReceiptController::class, 'index'])->name('receipts.index');
        Route::post('receipts/{transaction}/print',   [ReceiptController::class, 'markPrinted'])->name('receipts.print');
        Route::delete('receipts/{transaction}',       [ReceiptController::class, 'destroy'])->name('receipts.destroy');

        // Tax
        Route::get('tax',             [TaxController::class, 'index'])->name('tax.index');
        Route::post('tax',            [TaxController::class, 'store'])->name('tax.store');
        Route::delete('tax/{tax}',    [TaxController::class, 'destroy'])->name('tax.destroy');
        Route::get('tax/fetch-sales', [TaxController::class, 'fetchSales'])->name('tax.fetch-sales');

        // Transactions
        Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.delete');

        // Users
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
        Route::post('users/{user}/password', [UserController::class, 'updatePassword'])->name('users.password');

        Route::get('customers', [CustomerPhoneController::class, 'index'])->name('customers.index');
        Route::get('customers/export', [CustomerPhoneController::class, 'export'])->name('customers.export');

    });

    // ── Kasir ────────────────────────────────
    Route::prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/',               [TransactionController::class, 'index'])->name('pos');
        Route::get('/search-product', [TransactionController::class, 'searchProduct'])->name('search.product');
        Route::get('/search-partner', [TransactionController::class, 'searchPartner'])->name('search.partner');
        Route::get('/search-member',  [TransactionController::class, 'searchMember'])->name('search.member');
        Route::post('/checkout',      [TransactionController::class, 'checkout'])->name('checkout');
        Route::get('/receipt/{transaction}', [TransactionController::class, 'receipt'])->name('receipt');
        Route::post('/create-member', [TransactionController::class, 'storeMember']);
    });

    // ── Security ─────────────────────────────
    Route::prefix('security')->group(function () {
        Route::post('visits/store',  [DashboardController::class, 'storeVisit'])->name('security.visits.store');
        Route::post('partner/store', [DashboardController::class, 'storePartner'])->name('security.partner.store');
        Route::post('guides/store',  [DashboardController::class, 'storeGuide'])->name('security.guides.store');
        Route::post('walkin/store',  [DashboardController::class, 'storeWalkin'])->name('security.walkin.store');
        Route::get('search-guides',  [DashboardController::class, 'searchGuides']);
        Route::post('visits/{visit}/status', [DashboardController::class, 'updateVisitStatus'])->name('security.visits.status');
    });

    // ── API ──────────────────────────────────
    Route::prefix('api')->group(function () {
        Route::get('currency-rates', [CurrencyController::class, 'getRates'])->name('currency.rates');
        Route::get('partner-visits', [TransactionController::class, 'partnerVisits']);
        Route::get('today-visits',   [VisitController::class, 'todayVisits']);
    });

});

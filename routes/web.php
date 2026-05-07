<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Kasir\TransactionController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\CommissionController;

// Temporary: akses langsung tanpa login
Route::get('/', function () {
    return redirect('/dashboard/admin');
});

Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
Route::get('/dashboard/kasir', [TransactionController::class, 'index'])->name('dashboard.kasir');
Route::get('/dashboard/security', [DashboardController::class, 'security'])->name('dashboard.security');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/restock', [ProductController::class, 'restock'])->name('products.restock');
    Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::resource('partners', PartnerController::class);
    Route::post('partners/{partner}/commission', [PartnerController::class, 'updateCommission'])->name('partners.commission');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy'])
        ->name('transactions.delete');
    Route::resource('members', MemberController::class);
    Route::post('members/{member}/adjust-points', [MemberController::class, 'adjustPoints'])->name('members.adjust-points');
    Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');
    Route::post('/commissions/{commission}/paid', [CommissionController::class, 'markPaid'])
    ->name('commissions.paid');
    Route::post('/commissions/{commission}/update-rate',
    [CommissionController::class, 'updateRate'])
    ->name('commissions.update-rate');
    Route::get('/commissions/{commission}/pdf',
    [CommissionController::class, 'downloadPdf'])
    ->name('commissions.pdf');
});
Route::get('/api/currency-rates', [CurrencyController::class, 'getRates'])->name('currency.rates');

Route::prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('pos');
    Route::get('/search-product', [TransactionController::class, 'searchProduct'])->name('search.product');
    Route::get('/search-partner', [TransactionController::class, 'searchPartner'])->name('search.partner');
    Route::get('/search-member', [TransactionController::class, 'searchMember'])->name('search.member');
    Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout');
    Route::get('/receipt/{transaction}', [TransactionController::class, 'receipt'])->name('receipt');
    Route::post('/create-member', [TransactionController::class, 'storeMember']);
});




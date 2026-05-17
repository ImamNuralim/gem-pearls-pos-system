<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class CustomerPhoneController extends Controller
{
    public function index()
    {
        $customers = Transaction::whereNotNull('customer_phone')
            ->where('customer_phone', '!=', '')
            ->select('customer_phone', 'customer_name', 'customer_type')
            ->selectRaw('COUNT(*) as total_transaksi')
            ->selectRaw('SUM(total) as total_belanja')
            ->selectRaw('MAX(created_at) as last_transaction')
            ->groupBy('customer_phone', 'customer_name', 'customer_type')
            ->orderByDesc('last_transaction')
            ->get();

        return view('admin.customers.index', compact('customers'));
    }
    public function export()
{
    return \Maatwebsite\Excel\Facades\Excel::download(
        new \App\Exports\CustomerPhoneExport,
        'data-customer-' . now()->format('d-m-Y') . '.xlsx'
    );
}
}

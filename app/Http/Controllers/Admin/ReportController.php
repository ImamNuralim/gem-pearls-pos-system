<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.reports.sales');
    }

    public function sales(Request $request)
    {
        $startDate = $request->start_date
    ? Carbon::parse($request->start_date)->startOfDay()
    : now()->startOfMonth();

$endDate = $request->end_date
    ? Carbon::parse($request->end_date)->endOfDay()
    : now();

        $transactions = Transaction::with(['items.product', 'user', 'partner', 'member'])
    ->whereNull('deleted_at') // pastikan tidak ambil yang sudah dihapus
    ->whereBetween('created_at', [$startDate, $endDate])
    ->where('status', 'completed')
    ->latest()
    ->get();

        $totalRevenue = $transactions->sum('total');
        $totalTransactions = $transactions->count();

        return view('admin.reports.sales', compact(
            'transactions',
            'totalRevenue',
            'totalTransactions',
            'startDate',
            'endDate'
        ));
    }
}

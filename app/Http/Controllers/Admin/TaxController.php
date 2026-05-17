<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxReport;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->year ?? now()->year;

        $reports = TaxReport::where('year', $year)
            ->orderBy('month')
            ->get();

        $years = TaxReport::selectRaw('DISTINCT year')
            ->orderByDesc('year')
            ->pluck('year');

        if (!$years->contains(now()->year)) {
            $years->prepend(now()->year);
        }

        return view('admin.tax.index', compact('reports', 'year', 'years'));
    }

    public function fetchSales(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);

        $total = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [
                \Carbon\Carbon::parse($request->start_date)->startOfDay(),
                \Carbon\Carbon::parse($request->end_date)->endOfDay(),
            ])
            ->sum('total');

        return response()->json(['total_sales' => $total]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'period_start'    => 'required|date',
            'period_end'      => 'required|date',
            'total_sales'     => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'notes'           => 'nullable|string',
        ]);

        $totalSales      = $request->total_sales;
        $commissionRate  = $request->commission_rate;
        $commissionAmount = $totalSales * ($commissionRate / 100);
        $salesFinal      = $totalSales - $commissionAmount;
        $taxAmount       = $salesFinal * 0.005; // 0.5%

        $start = \Carbon\Carbon::parse($request->period_start);

        TaxReport::create([
            'year'              => $start->year,
            'month'             => $start->month,
            'period_start'      => $request->period_start,
            'period_end'        => $request->period_end,
            'total_sales'       => $totalSales,
            'commission_rate'   => $commissionRate,
            'commission_amount' => $commissionAmount,
            'sales_final'       => $salesFinal,
            'tax_amount'        => $taxAmount,
            'notes'             => $request->notes,
        ]);

        return back()->with('success', 'Data pajak berhasil disimpan!');
    }

    public function destroy(TaxReport $tax)
    {
        $tax->delete();
        return back()->with('success', 'Data pajak berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CommissionController extends Controller
{
    public function index()
    {
        $commissions = Commission::with('partner')
            ->latest('commission_date')
            ->latest()
            ->get();

        return view('admin.commissions.index', compact('commissions'));
    }
    public function markPaid(Commission $commission)
    {
        $commission->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Komisi berhasil dibayar');
    }
    public function updateRate(Request $request, Commission $commission)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $rate = $request->commission_rate;

        $commissionAmount =
            $commission->total_sales * ($rate / 100);

        $commission->update([
            'commission_rate' => $rate,
            'commission_amount' => $commissionAmount,
        ]);

        return back()->with('success', 'Persentase komisi berhasil diupdate');
    }
    public function downloadPdf(Commission $commission)
{
    $pdf = Pdf::loadView('admin.commissions.pdf', [
        'commission' => $commission
    ])->setPaper([0, 0, 595, 500], 'portrait');

    return $pdf->download(
        'komisi-' .
        $commission->partner->name .
        '-' .
        $commission->commission_date->format('d-m-Y') .
        '.pdf'
    );
}
}

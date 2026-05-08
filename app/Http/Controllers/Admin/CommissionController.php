<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Guide;

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
    public function updateDetail(Request $request, Commission $commission)
    {
        $request->validate([
            'sticker_number' => 'nullable|string|max:255',
            'group_description' => 'nullable|string',
            'visit_date' => 'nullable|date',
            'pickup_deadline' => 'nullable|date',
            'vehicle_notes' => 'nullable|string',
        ]);

        $commission->update([
            'sticker_number' => $request->sticker_number,
            'group_description' => $request->group_description,
            'visit_date' => $request->visit_date,
            'pickup_deadline' => $request->pickup_deadline,
            'vehicle_notes' => $request->vehicle_notes,
        ]);

        return back()->with('success', 'Detail komisi berhasil diupdate');
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

    public function attachGuide(Request $request, Commission $commission)
    {
        $request->validate([
            'guide_id' => 'required|exists:guides,id',
        ]);

        // attach kalau belum ada
        if (!$commission->guides->contains($request->guide_id)) {

            $commission->guides()->attach($request->guide_id);

            // tambah total visits guide
            $guide = Guide::find($request->guide_id);

            $guide->increment('total_visits');
        }

        return back()->with('success', 'Guide berhasil ditambahkan');
    }
    public function updateRate(Request $request, Commission $commission)
{
    $request->validate([

        'commission_rate' => 'required|numeric|min:0|max:100',

    ]);

    $rate = $request->commission_rate;

    $amount = ($commission->total_sales * $rate) / 100;

    $commission->update([

        'commission_rate' => $rate,

        'commission_amount' => $amount,

    ]);

    return back()->with(
        'success',
        'Persentase komisi berhasil diupdate'
    );
}


}

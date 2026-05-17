<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\PartnerVisit;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Guide;

class CommissionController extends Controller
{
    public function index()
    {
        $commissions = Commission::with(['partner', 'visit'])
            ->latest('commission_date')
            ->latest()
            ->get();

        // Visit completed yang belum punya komisi
        $availableVisits = PartnerVisit::with(['partner', 'vehicles', 'guides'])
    ->where('visit_type', 'partner')
    ->where('status', 'completed')
    ->whereNotIn('id', Commission::whereNotNull('partner_visit_id')->pluck('partner_visit_id'))
    ->whereDoesntHave('transactions', function($q) {
        $q->whereNull('partner_visit_id');
    })
    ->get();

        return view('admin.commissions.index', compact('commissions', 'availableVisits'));
    }

    public function store(Request $request)

{

    $request->validate([
        'partner_visit_id' => 'required|exists:partner_visits,id',
        'commission_rate'  => 'required|numeric|min:0|max:100',
    ]);

    $visit = PartnerVisit::with(['partner', 'guides'])->findOrFail($request->partner_visit_id);

    $rate   = (float) $request->commission_rate;
    $amount = $visit->total_sales * ($rate / 100);

    $commission = Commission::create([
        'partner_id'        => $visit->partner_id,
        'partner_visit_id'  => $visit->id,
        'sticker_number'    => $visit->sticker_number,
        'group_description' => $visit->group_description,
        'visit_date'        => $visit->visit_date,
        'pickup_deadline'   => $visit->pickup_deadline,
        'vehicle_notes'     => $visit->vehicle_notes,
        'commission_date'   => now()->toDateString(),
        'total_sales'       => $visit->total_sales,
        'commission_rate'   => $rate,
        'commission_amount' => $amount,
        'status'            => 'unpaid',
    ]);

    $commission->refresh();

// Update commission rate di partner
$visit->partner->update(['commission_rate' => $rate]);

if ($visit->guides->isNotEmpty()) {
    $commission->guides()->attach($visit->guides->pluck('id'));
}

return back()->with('success', 'Komisi berhasil dibuat!');
}

    public function markPaid(Commission $commission)
    {
        $commission->update(['status' => 'paid', 'paid_at' => now()]);
        return back()->with('success', 'Komisi berhasil dibayar');
    }

    public function destroy(Commission $commission)
    {
        $commission->delete();
        return back()->with('success', 'Komisi berhasil dihapus');
    }

    public function updateRate(Request $request, Commission $commission)
    {
        $request->validate(['commission_rate' => 'required|numeric|min:0|max:100']);
        $rate   = $request->commission_rate;
        $amount = ($commission->total_sales * $rate) / 100;
        $commission->update(['commission_rate' => $rate, 'commission_amount' => $amount]);
        return back()->with('success', 'Persentase komisi berhasil diupdate');
    }

    public function updateDetail(Request $request, Commission $commission)
    {
        $request->validate([
            'sticker_number'    => 'nullable|string|max:255',
            'group_description' => 'nullable|string',
            'visit_date'        => 'nullable|date',
            'pickup_deadline'   => 'nullable|date',
            'vehicle_notes'     => 'nullable|string',
        ]);

        $commission->update($request->only([
            'sticker_number', 'group_description',
            'visit_date', 'pickup_deadline', 'vehicle_notes',
        ]));

        return back()->with('success', 'Detail komisi berhasil diupdate');
    }

    public function downloadPdf(Commission $commission)
    {
        $pdf = Pdf::loadView('admin.commissions.pdf', compact('commission'))
            ->setPaper([0, 0, 595, 500], 'portrait');

        return $pdf->download('komisi-' . $commission->partner->name . '-' . $commission->commission_date->format('d-m-Y') . '.pdf');
    }

    public function attachGuide(Request $request, Commission $commission)
    {
        $request->validate(['guide_id' => 'required|exists:guides,id']);

        if (!$commission->guides->contains($request->guide_id)) {
            $commission->guides()->attach($request->guide_id);
            Guide::find($request->guide_id)->increment('total_visits');
        }

        return back()->with('success', 'Guide berhasil ditambahkan');
    }
}

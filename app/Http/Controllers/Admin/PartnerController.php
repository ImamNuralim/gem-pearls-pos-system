<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Commission;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Partner::withCount('transactions')
            ->with('commissions');

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        if ($request->status === 'aktif') {
            $query->where('is_active', true);
        } elseif ($request->status === 'nonaktif') {
            $query->where('is_active', false);
        }

        $partners = $query->latest()->paginate(15);

        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:travel_agent,freelance_guide',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        // Generate kode unik
        $prefix = $request->type === 'travel_agent' ? 'TA' : 'FG';
        $lastPartner = Partner::where('type', $request->type)->orderBy('id', 'desc')->first();
        $nextId = $lastPartner ? ($lastPartner->id + 1) : 1;
        $code = $prefix . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $partner = Partner::create([
            'name' => $request->name,
            'code' => $code,
            'type' => $request->type,
            'phone' => $request->phone,
            'email' => $request->email,
            'commission_rate' => $request->commission_rate,
            'notes' => $request->notes,
            'is_active' => true,
        ]);

        return redirect()->route('admin.partners.index')
            ->with('success', "Mitra {$partner->name} berhasil ditambahkan! Kode: {$code}");
    }

   public function show(Partner $partner)
{
    $partner->load('commissions');

    $totalCommission  = $partner->commissions->sum('commission_amount');
    $unpaidCommission = $partner->commissions->where('status', 'unpaid')->sum('commission_amount');
    $paidCommission   = $partner->commissions->where('status', 'paid')->sum('commission_amount');

    $visits = \App\Models\PartnerVisit::with(['commissions'])
        ->where('partner_id', $partner->id)
        ->latest()
        ->paginate(10);

    return view('admin.partners.show', compact(
        'partner', 'visits',
        'totalCommission', 'unpaidCommission', 'paidCommission'
    ));
}

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $partner->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'commission_rate' => $request->commission_rate,
            'notes' => $request->notes,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.partners.index')
            ->with('success', "Mitra {$partner->name} berhasil diupdate!");
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('admin.partners.index')
            ->with('success', "Mitra {$partner->name} berhasil dihapus!");
    }

    // Update status komisi
    public function updateCommission(Request $request, Partner $partner)
    {
        $request->validate([
            'commission_ids' => 'required|array',
            'status' => 'required|in:paid,unpaid',
        ]);

        Commission::whereIn('id', $request->commission_ids)
            ->where('partner_id', $partner->id)
            ->update([
                'status' => $request->status,
                'paid_at' => $request->status === 'paid' ? now() : null,
            ]);

        return redirect()->back()
            ->with('success', 'Status komisi berhasil diupdate!');
    }
}

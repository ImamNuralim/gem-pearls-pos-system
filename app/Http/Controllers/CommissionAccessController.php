<?php

namespace App\Http\Controllers;

use App\Models\CommissionUser;
use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionAccessController extends Controller
{
    public function showLogin()
    {
        if (session('commission_user_id')) {
            return redirect()->route('commission.index');
        }
        return view('commission.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = CommissionUser::where('email', $request->email)
            ->where('is_active', true)
            ->first();

        if (!$user || !$user->checkPassword($request->password)) {
            return back()->withErrors(['email' => 'Email atau password salah!']);
        }

        session(['commission_user_id' => $user->id, 'commission_user_name' => $user->name]);
        return redirect()->route('commission.index');
    }

    public function logout()
    {
        session()->forget(['commission_user_id', 'commission_user_name']);
        return redirect()->route('commission.login');
    }

    public function index(Request $request)
{
    if (!session('commission_user_id')) return redirect()->route('commission.login');

    $search = $request->search;

    $commissions = Commission::with(['partner', 'visit', 'guides'])
        ->when($search, function($q) use ($search) {
            $q->whereHas('partner', fn($q) => $q->where('name', 'like', "%{$search}%"))
              ->orWhere('sticker_number', 'like', "%{$search}%");
        })
        ->latest('commission_date')->latest()->get();

    $availableVisits = \App\Models\PartnerVisit::with(['partner', 'vehicles', 'guides'])
        ->where('visit_type', 'partner')
        ->where('status', 'completed')
        ->whereNotIn('id', Commission::whereNotNull('partner_visit_id')->pluck('partner_visit_id'))
        ->get();

    return view('commission.index', compact('commissions', 'availableVisits', 'search'));
}

    public function markPaid(Request $request, Commission $commission)
    {
        if (!session('commission_user_id')) {
            return redirect()->route('commission.login');
        }

        $request->validate([
            'taken_by' => 'required|string|max:255',
        ]);

        $commission->update([
            'status'   => 'paid',
            'paid_at'  => now(),
            'taken_by' => $request->taken_by,
            'taken_at' => now(),
        ]);

        return back()->with('success', 'Komisi berhasil ditandai lunas!');
    }
    public function store(Request $request)
{
    if (!session('commission_user_id')) return redirect()->route('commission.login');

    $request->validate([
        'partner_visit_id' => 'required|exists:partner_visits,id',
        'commission_rate'  => 'required|numeric|min:0|max:100',
    ]);

    $visit  = \App\Models\PartnerVisit::with(['partner', 'guides'])->findOrFail($request->partner_visit_id);
    $rate   = (float) $request->commission_rate;
    $amount = $visit->total_sales * ($rate / 100);

    $commission = \App\Models\Commission::create([
        'partner_id'        => $visit->partner_id,
        'partner_visit_id'  => $visit->id,
        'sticker_number'    => $visit->sticker_number,
        'group_description' => $visit->group_description,
        'visit_date'        => $visit->visit_date,
        'pickup_deadline'   => $request->pickup_deadline,
        'commission_date'   => now()->toDateString(),
        'total_sales'       => $visit->total_sales,
        'commission_rate'   => $rate,
        'commission_amount' => $amount,
        'status'            => 'unpaid',
    ]);

    if ($visit->guides->isNotEmpty()) {
        $commission->guides()->attach($visit->guides->pluck('id'));
    }

    return back()->with('success', 'Komisi berhasil dibuat!');
}

public function destroy(Commission $commission)
{
    if (!session('commission_user_id')) return redirect()->route('commission.login');
    $commission->delete();
    return back()->with('success', 'Komisi berhasil dihapus!');
}

public function updateRate(Request $request, Commission $commission)
{
    if (!session('commission_user_id')) return redirect()->route('commission.login');
    $request->validate(['commission_rate' => 'required|numeric|min:0|max:100']);
    $rate   = $request->commission_rate;
    $amount = ($commission->total_sales * $rate) / 100;
    $commission->update(['commission_rate' => $rate, 'commission_amount' => $amount]);
    return back()->with('success', 'Persentase komisi berhasil diupdate!');
}
public function updateDetail(Request $request, Commission $commission)
{
    if (!session('commission_user_id')) return redirect()->route('commission.login');

    $request->validate(['commission_rate' => 'required|numeric|min:0|max:100']);

    $rate   = $request->commission_rate;
    $amount = ($commission->total_sales * $rate) / 100;

    $commission->update([
        'commission_rate'   => $rate,
        'commission_amount' => $amount,
        'sticker_number'    => $request->sticker_number,
        'group_description' => $request->group_description,
        'pickup_deadline'   => $request->pickup_deadline,
    ]);

    return back()->with('success', 'Komisi berhasil diupdate!');
}

public function downloadPdf(Commission $commission)
{
    if (!session('commission_user_id')) return redirect()->route('commission.login');

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.commissions.pdf', compact('commission'))
        ->setPaper([0, 0, 595, 500], 'portrait');

    return $pdf->download('komisi-' . $commission->partner->name . '-' . $commission->commission_date->format('d-m-Y') . '.pdf');
}
}

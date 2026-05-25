<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commission;

class DashboardController extends Controller
{
    public function index()
    {
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

        abort(403, 'Unauthorized');
    }

   public function admin(Request $request)
{
    $todaySales = \App\Models\Transaction::whereDate('created_at', today())
        ->where('status', 'completed')
        ->sum('total');

    $todayTransactions = \App\Models\Transaction::whereDate('created_at', today())
        ->where('status', 'completed')
        ->count();

    $todayVisits = \App\Models\PartnerVisit::whereDate('visit_date', today())->count();

    $totalMembers = \App\Models\Member::where('is_active', true)->count();

    $totalPartners = \App\Models\Partner::where('is_active', true)->count();

    $recentTransactions = \App\Models\Transaction::with(['user', 'partner'])
        ->where('status', 'completed')
        ->latest()
        ->take(7)
        ->get();

    $topProducts = \App\Models\TransactionItem::select('product_name', 'sku')
        ->selectRaw('SUM(quantity) as total_qty')
        ->selectRaw('SUM(subtotal) as total_revenue')
        ->groupBy('product_name', 'sku')
        ->orderByDesc('total_qty')
        ->take(5)
        ->get();

    $days = $request->days ?? 7;
    $startDate = now()->subDays($days - 1)->startOfDay();

    $salesChart = \App\Models\Transaction::where('status', 'completed')
        ->where('created_at', '>=', $startDate)
        ->get()
        ->groupBy(fn($t) => $t->created_at->format('d/m'))
        ->map(fn($group) => $group->sum('total'));

    $chartLabels = collect();
    $chartData   = collect();
    for ($i = $days - 1; $i >= 0; $i--) {
        $label = now()->subDays($i)->format('d/m');
        $chartLabels->push($label);
        $chartData->push($salesChart[$label] ?? 0);
    }

    return view('dashboard.admin', compact(
        'todaySales', 'todayTransactions', 'todayVisits',
        'totalMembers', 'totalPartners',
        'recentTransactions', 'chartLabels', 'chartData',
        'topProducts', 'days'
    ));
}
    public function kasir()
    {
        return view('dashboard.kasir');
    }

    public function security()
    {
        $partners = \App\Models\Partner::where('is_active', true)
            ->orderBy('name')
            ->get();

        $visits = \App\Models\PartnerVisit::with([
            'partner',
            'vehicles'
        ])
            ->latest()
            ->get();

        return view('dashboard.security', compact(
            'partners',
            'visits'
        ));
    }
    public function storeVisit(Request $request)
{
    $request->validate([
        'visit_date'          => 'required|date',
        'sticker_number'      => 'nullable|string|max:255',
        'group_description'   => 'nullable|string',
        'visitor_nationality' => 'nullable|string',
        'tour_leader_name'    => 'nullable|string',
        'tour_leader_phone'   => 'nullable|string',
        'visit_type_label'    => 'nullable|string',
        'partner_id'          => 'nullable|exists:partners,id',
    ]);

    $lastVisit = \App\Models\PartnerVisit::latest('id')->first();
    $number    = $lastVisit ? ((int) str_replace('VIS-', '', $lastVisit->visit_code)) + 1 : 1;
    $visitCode = 'VIS-' . str_pad($number, 4, '0', STR_PAD_LEFT);

    // Tentukan visit_type berdasarkan visit_type_label
    $visitTypeLabel = $request->visit_type_label ?? 'no_guide';
    $visitType = $visitTypeLabel === 'no_guide' ? 'walk_in' : 'partner';

    $visit = \App\Models\PartnerVisit::create([
        'partner_id'          => $request->partner_id,
        'visit_code'          => $visitCode,
        'visit_type'          => $visitType,
        'visit_type_label'    => $visitTypeLabel,
        'sticker_number'      => $request->sticker_number,
        'group_description'   => $request->group_description,
        'visit_date'          => $request->visit_date,
        'visitor_nationality' => $request->visitor_nationality,
        'tour_leader_name'    => $request->tour_leader_name,
        'tour_leader_phone'   => $request->tour_leader_phone,
        'status'              => 'pending',
    ]);

    // Save vehicles
    if ($request->plate_numbers) {
        foreach ($request->plate_numbers as $index => $plate) {
            if ($plate) {
                \App\Models\CommissionVehicle::create([
                    'partner_visit_id' => $visit->id,
                    'plate_number'     => $plate,
                    'vehicle_type'     => $request->vehicle_types[$index] ?? null,
                ]);
            }
        }
    }

    // Attach guides
    if ($request->guide_ids) {
        $visit->guides()->attach($request->guide_ids);
        \App\Models\Guide::whereIn('id', $request->guide_ids)->increment('total_visits');
    }

    // Attach drivers
    if ($request->driver_ids) {
        $visit->drivers()->attach($request->driver_ids);
    }

    return back()->with('success', 'Kunjungan berhasil disimpan!');
}


    public function storePartner(Request $request)
{
    $request->validate([
        'name'    => 'required|string|max:255',
        'phone'   => 'nullable|string|max:30',
        'address' => 'nullable|string',
        'type'    => 'required|in:travel_agent,freelance_guide',
    ]);

    $lastPartner = \App\Models\Partner::latest('id')->first();
    $number      = $lastPartner ? ((int) str_replace('PRT-', '', $lastPartner->code)) + 1 : 1;
    $code        = 'PRT-' . str_pad($number, 3, '0', STR_PAD_LEFT);

    $partner = \App\Models\Partner::create([
        'code'      => $code,
        'name'      => $request->name,
        'phone'     => $request->phone,
        'address'   => $request->address,
        'type'      => $request->type,
        'is_active' => true,
    ]);

    if ($request->wantsJson()) {
        return response()->json(['success' => true, 'partner' => $partner]);
    }
    return back()->with('success', 'Partner berhasil ditambahkan');
}

    public function searchGuides(Request $request)
    {
        $guides = \App\Models\Guide::where('is_active', true)
            ->where(function ($q) use ($request) {

                $q->where('name', 'like', "%{$request->q}%")
                    ->orWhere('guide_code', 'like', "%{$request->q}%");

            })
            ->orderBy('name')
            ->get([
                'id',
                'guide_code',
                'name',
                'phone'
            ]);

        return response()->json($guides);
    }
    public function storeGuide(Request $request)
{
    $request->validate([
        'name'    => 'required|string|max:255',
        'phone'   => 'nullable|string|max:30',
        'address' => 'nullable|string',
    ]);

    $guide = \App\Models\Guide::create([
        'name'         => $request->name,
        'phone'        => $request->phone,
        'address'      => $request->address,
        'total_visits' => 0,
        'is_active'    => true,
    ]);

    if ($request->wantsJson()) {
        return response()->json(['success' => true, 'guide' => $guide]);
    }
    return back()->with('success', 'Guide berhasil ditambahkan');
}
    public function storeWalkin(Request $request)
{
    $request->validate([
        'visit_date' => 'required|date',
        'vehicle_notes' => 'required|string',
        'vehicle_description' => 'required|string',
        'group_description' => 'nullable|string',
    ]);

    \App\Models\PartnerVisit::create([
        'partner_id' => null,
        'visit_code' => \App\Models\PartnerVisit::generateVisitCode(),
        'visit_type' => 'walk_in',
        'visit_date' => $request->visit_date,
        'vehicle_notes' => $request->vehicle_notes,
        'vehicle_description' => $request->vehicle_description,
        'group_description' => $request->group_description,
        'status' => 'pending',
    ]);

    return redirect()->back()->with('success', 'Walk-in berhasil dicatat!');
}
public function storeDriver(Request $request)
{
    $request->validate(['name' => 'required|string']);

    $driver = \App\Models\Driver::create([
        'name'  => $request->name,
        'phone' => $request->phone,
    ]);

    if ($request->wantsJson()) {
        return response()->json(['success' => true, 'driver' => $driver]);
    }
    return back()->with('success', 'Driver berhasil ditambahkan!');
}

public function searchDrivers(Request $request)
{
    $drivers = \App\Models\Driver::where('name', 'like', "%{$request->q}%")
        ->orWhere('driver_code', 'like', "%{$request->q}%")
        ->where('is_active', true)
        ->limit(10)
        ->get();
    return response()->json($drivers);
}
public function updateVisitStatus(Request $request, \App\Models\PartnerVisit $visit)
{
    $request->validate([
        'status' => 'required|in:pending,completed',
    ]);

    $visit->update(['status' => $request->status]);

    return redirect()->back()->with('success', 'Status diperbarui!');
}
public function updateVisit(Request $request, \App\Models\PartnerVisit $visit)
{
    $visit->update([
        'sticker_number'      => $request->sticker_number,
        'group_description'   => $request->group_description,
        'visitor_nationality' => $request->visitor_nationality,
        'status'              => $request->status,
    ]);

    // Update kendaraan — hapus lama, insert baru
    $visit->vehicles()->delete();
    if ($request->plate_numbers) {
        foreach ($request->plate_numbers as $index => $plate) {
            if ($plate) {
                \App\Models\CommissionVehicle::create([
                    'partner_visit_id' => $visit->id,
                    'plate_number'     => $plate,
                    'vehicle_type'     => $request->vehicle_types[$index] ?? null,
                ]);
            }
        }
    }

    return back()->with('success', 'Data kunjungan berhasil diupdate!');
}

public function destroyVisit(\App\Models\PartnerVisit $visit)
{
    $visit->vehicles()->delete();
    $visit->guides()->detach();
    $visit->drivers()->detach();
    $visit->delete();
    return back()->with('success', 'Kunjungan berhasil dihapus!');
}
}

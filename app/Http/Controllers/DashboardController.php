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

    public function admin()
    {
        return view('dashboard.admin');
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

            'partner_id' => 'required|exists:partners,id',

            'sticker_number' => 'nullable|string|max:255',

            'group_description' => 'nullable|string',

            'visit_date' => 'required|date',

            'pickup_deadline' => 'nullable|date',

            'vehicle_notes' => 'nullable|string',

        ]);

        $lastVisit = \App\Models\PartnerVisit::latest('id')->first();

$number = $lastVisit
    ? ((int) str_replace('VIS-', '', $lastVisit->visit_code)) + 1
    : 1;

$visitCode = 'VIS-' . str_pad($number, 4, '0', STR_PAD_LEFT);

$visit = \App\Models\PartnerVisit::create([

    'partner_id' => $request->partner_id,

    'visit_code' => $visitCode,

    'sticker_number' => $request->sticker_number,

    'group_description' => $request->group_description,

    'visit_date' => $request->visit_date,

    'pickup_deadline' => $request->pickup_deadline,

    'vehicle_notes' => $request->vehicle_notes,

    'status' => 'pending',

]);

// Save vehicles
if ($request->plate_numbers) {

    foreach ($request->plate_numbers as $index => $plate) {

        if ($plate) {

            \App\Models\CommissionVehicle::create([

                'partner_visit_id' => $visit->id,

                'plate_number' => $plate,

                'vehicle_type' =>
                    $request->vehicle_types[$index] ?? null,

            ]);

        }

    }

}

        return back()->with(
            'success',
            'Kunjungan berhasil disimpan'
        );
    }

    public function storePartner(Request $request)
    {
        $request->validate([

            'name' => 'required|string|max:255',

            'phone' => 'nullable|string|max:30',

            'address' => 'nullable|string',

            'type' => 'required|in:travel_agent,freelance',

        ]);

        $lastPartner = \App\Models\Partner::latest('id')->first();

        $number = $lastPartner
            ? ((int) str_replace('PRT-', '', $lastPartner->code)) + 1
            : 1;

        $code = 'PRT-' . str_pad($number, 3, '0', STR_PAD_LEFT);

        \App\Models\Partner::create([

            'code' => $code,

            'name' => $request->name,

            'phone' => $request->phone,

            'address' => $request->address,

            'type' => $request->type,

            'is_active' => true,

        ]);

        return back()->with(
            'success',
            'Partner berhasil ditambahkan'
        );
    }
}

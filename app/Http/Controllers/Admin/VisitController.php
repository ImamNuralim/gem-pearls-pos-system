<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerVisit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index(Request $request)
{
    $search = $request->search;

    $visits = PartnerVisit::with(['partner', 'vehicles', 'guides'])
        ->when($search, function($q) use ($search) {
            $q->where('visit_code', 'like', "%{$search}%")
              ->orWhereHas('partner', fn($q) => $q->where('name', 'like', "%{$search}%"))
              ->orWhereHas('guides', fn($q) => $q->where('name', 'like', "%{$search}%"));
        })
        ->latest()
        ->get();

    $partnerVisits = $visits->where('visit_type', 'partner');
    $walkinVisits  = $visits->where('visit_type', 'walk_in');

    return view('admin.visits.index', compact('visits', 'partnerVisits', 'walkinVisits', 'search'));
}

    public function todayVisits(Request $request)
{
    $type = $request->type;

    $visits = PartnerVisit::with(['partner', 'guides', 'vehicles'])
    ->whereDate('visit_date', today())
    ->whereIn('status', ['pending', 'shopping'])
    ->when($type === 'walk_in', fn($q) => $q->where('visit_type', 'walk_in'))
    ->when($type !== 'walk_in', function($q) use ($type) {
        $q->where('visit_type', 'partner')
          ->when($type, fn($q) => $q->whereHas('partner', fn($q) => $q->where('type', $type)));
    })
    ->get();

    return response()->json($visits);
}
}

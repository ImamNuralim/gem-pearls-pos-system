<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PartnerVisit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function todayVisits(Request $request)
    {
        $type = $request->type; // travel_agent atau freelance_guide

        $visits = PartnerVisit::with(['partner', 'guides', 'vehicles'])
            ->whereDate('visit_date', today())
            ->where('visit_type', 'partner')
            ->when($type, function ($q) use ($type) {
                $q->whereHas('partner', fn($q) => $q->where('type', $type));
            })
            ->get();

        return response()->json($visits);
    }
}

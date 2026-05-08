<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerVisit;

class VisitController extends Controller
{
    public function index()
    {
        $visits = PartnerVisit::with([
                'partner',
                'vehicles'
            ])
            ->latest()
            ->get();

        return view('admin.visits.index', compact(
            'visits'
        ));
    }
}

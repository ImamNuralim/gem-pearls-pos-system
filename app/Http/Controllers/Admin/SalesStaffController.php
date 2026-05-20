<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesStaff;
use Illuminate\Http\Request;

class SalesStaffController extends Controller
{
    public function index(Request $request)
{
    $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
    $endDate   = $request->end_date ?? now()->format('Y-m-d');

    $staffs = SalesStaff::withCount(['transactions' => fn($q) => $q->where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])])
        ->withSum(['transactions' => fn($q) => $q->where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])], 'total')
        ->orderByDesc('transactions_sum_total')
        ->get();

    return view('admin.sales_staff.index', compact('staffs', 'startDate', 'endDate'));
}

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        SalesStaff::create(['name' => $request->name, 'team' => 'mutiara']);
        return back()->with('success', 'Sales berhasil ditambahkan!');
    }

    public function update(Request $request, SalesStaff $salesStaff)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $salesStaff->update(['name' => $request->name]);
        return back()->with('success', 'Sales berhasil diupdate!');
    }

    public function destroy(SalesStaff $salesStaff)
    {
        $salesStaff->delete();
        return back()->with('success', 'Sales berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberPoint;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->status === 'aktif') {
            $query->where('is_active', true);
        } elseif ($request->status === 'nonaktif') {
            $query->where('is_active', false);
        }

        $members = $query->latest()->paginate(15);

        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        return view('admin.members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:members,phone',
            'email' => 'nullable|email|max:255',
        ]);

        $member = Member::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'points_balance' => 0,
            'registered_at' => today(),
            'is_active' => true,
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', "Member {$member->name} berhasil didaftarkan!");
    }

    public function show(Member $member)
    {
        $pointLogs = $member->pointLogs()
            ->with('transaction')
            ->latest()
            ->paginate(10);

        $totalEarned = $member->pointLogs()->where('type', 'earn')->sum('points');
        $totalRedeemed = $member->pointLogs()->where('type', 'redeem')->sum('points');
        $totalTransactions = $member->transactions()->count();
        $totalSpent = $member->transactions()->sum('total');

        return view('admin.members.show', compact(
            'member', 'pointLogs',
            'totalEarned', 'totalRedeemed',
            'totalTransactions', 'totalSpent'
        ));
    }

    public function edit(Member $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:members,phone,' . $member->id,
            'email' => 'nullable|email|max:255',
        ]);

        $member->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', "Data member {$member->name} berhasil diupdate!");
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('admin.members.index')
            ->with('success', "Member {$member->name} berhasil dihapus!");
    }

    // Adjust poin manual (oleh admin/owner)
    public function adjustPoints(Request $request, Member $member)
    {
        $request->validate([
            'type' => 'required|in:earn,redeem',
            'points' => 'required|integer|min:1',
            'note' => 'required|string',
        ]);

        if ($request->type === 'redeem' && $request->points > $member->points_balance) {
            return redirect()->back()->with('error', 'Poin tidak mencukupi!');
        }

        if ($request->type === 'earn') {
            $member->increment('points_balance', $request->points);
        } else {
            $member->decrement('points_balance', $request->points);
        }

        MemberPoint::create([
            'member_id' => $member->id,
            'transaction_id' => null,
            'type' => $request->type,
            'points' => $request->points,
            'note' => '[Manual] ' . $request->note,
        ]);

        return redirect()->back()
            ->with('success', 'Poin berhasil disesuaikan!');
    }
}

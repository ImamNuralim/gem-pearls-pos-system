<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionUser;
use Illuminate\Http\Request;

class CommissionUserController extends Controller
{
    public function index()
    {
        $commissionUsers = CommissionUser::latest()->get();
        return view('admin.commission-users.index', compact('commissionUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:commission_users,email',
            'password' => 'required|min:6',
        ]);
        CommissionUser::create($request->only('name', 'email', 'password'));
        return back()->with('success', 'Akun komisi berhasil dibuat!');
    }

    public function update(Request $request, CommissionUser $commissionUser)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:commission_users,email,' . $commissionUser->id,
        ]);
        $data = ['name' => $request->name, 'email' => $request->email];
        if ($request->password) $data['password'] = $request->password;
        $commissionUser->update($data);
        return back()->with('success', 'Akun komisi berhasil diupdate!');
    }

    public function destroy(CommissionUser $commissionUser)
    {
        $commissionUser->delete();
        return back()->with('success', 'Akun komisi berhasil dihapus!');
    }
}

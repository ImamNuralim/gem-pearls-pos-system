<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('dashboard.security');
    }
}

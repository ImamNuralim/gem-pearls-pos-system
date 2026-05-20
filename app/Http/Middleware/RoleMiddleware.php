<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        foreach ($roles as $role) {
            if (auth()->user()->hasRole($role)) {
                return $next($request);
            }
        }

        // Redirect berdasarkan role
        $user = auth()->user();
        if ($user->hasRole('kasir')) return redirect()->route('dashboard.kasir');
        if ($user->hasRole('security')) return redirect()->route('dashboard.security');
        return redirect()->route('dashboard.admin');
    }
}

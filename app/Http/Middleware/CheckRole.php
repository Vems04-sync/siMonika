<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $pengguna = Auth::user();
        
        if ($role === 'super_admin' && $pengguna->role !== 'super_admin') {
            abort(403, 'Akses tidak diizinkan');
        }

        if ($role === 'admin' && $pengguna->role !== 'admin') {
            abort(403, 'Akses tidak diizinkan');
        }

        return $next($request);
    }
}

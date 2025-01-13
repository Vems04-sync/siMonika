<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            DB::table('penggunas')
                ->where('id_user', Auth::user()->id_user)
                ->update(['last_activity' => now()]);
        }
        
        return $next($request);
    }
}

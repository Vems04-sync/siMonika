<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Pengguna;
use App\Models\Aplikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        $data = [
            'total_admin' => Pengguna::where('role', 'admin')->count(),
            'total_aplikasi' => Aplikasi::count(),
            'aplikasi_aktif' => Aplikasi::where('status_pemakaian', 'Aktif')->count(),
            'aplikasi_tidak_aktif' => Aplikasi::where('status_pemakaian', '!=', 'Aktif')->count(),
            'log_aktivitas' => LogAktivitas::with('user')
                                         ->orderBy('created_at', 'desc')
                                         ->paginate(10),
            'admin_aktif' => DB::table('penggunas')
                              ->where('role', 'admin')
                              ->whereNotNull('last_activity')
                              ->where('last_activity', '>=', now()->subMinutes(5))
                              ->get()
        ];

        return view('super-admin.dashboard', $data);
    }
}

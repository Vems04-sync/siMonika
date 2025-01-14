<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Pengguna;
use App\Models\Aplikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        ];

        // Dapatkan admin yang aktif
        $activeAdminIds = DB::table('log_aktivitas as login')
            ->select('login.user_id')
            ->where('login.aktivitas', 'Login')
            ->where('login.created_at', '>=', Carbon::now()->subMinutes(30))
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('log_aktivitas as logout')
                    ->whereColumn('logout.user_id', 'login.user_id')
                    ->where('logout.aktivitas', 'Logout')
                    ->whereRaw('logout.created_at > login.created_at');
            })
            ->groupBy('login.user_id')
            ->pluck('user_id');

        // Ambil data lengkap admin yang aktif
        $data['admin_aktif'] = Pengguna::whereIn('id_user', $activeAdminIds)
            ->where('role', 'admin')
            ->get()
            ->map(function ($admin) {
                // Ambil waktu login terakhir
                $lastLogin = LogAktivitas::where('user_id', $admin->id_user)
                    ->where('aktivitas', 'Login')
                    ->latest()
                    ->first();

                $admin->last_login = $lastLogin ? $lastLogin->created_at : null;
                return $admin;
            });

        return view('super-admin.dashboard', $data);
    }
}

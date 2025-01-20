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
        // Data untuk statistik
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
        $activeAdmins = Pengguna::where('role', 'admin')
            ->get()
            ->map(function ($admin) {
                // Cek login/logout terakhir
                $lastLogin = LogAktivitas::where('user_id', $admin->id_user)
                    ->where('aktivitas', 'Login')
                    ->latest()
                    ->first();

                $lastLogout = LogAktivitas::where('user_id', $admin->id_user)
                    ->where('aktivitas', 'Logout')
                    ->latest()
                    ->first();

                // Ambil aktivitas terakhir untuk ditampilkan
                $lastActivity = LogAktivitas::where('user_id', $admin->id_user)
                    ->latest()
                    ->first();

                // Tentukan status
                $isOnline = false;
                if ($lastLogin) {
                    if (!$lastLogout || $lastLogin->created_at > $lastLogout->created_at) {
                        $isOnline = true;
                    }
                }

                return [
                    'nama' => $admin->nama,
                    'email' => $admin->email,
                    'last_activity' => $lastActivity ? $lastActivity->created_at : null,
                    'last_action' => $lastActivity ? $lastActivity->aktivitas : 'Tidak ada aktivitas',
                    'status' => $isOnline ? 'Online' : 'Offline',
                    'sort_order' => $isOnline ? 0 : 1
                ];
            })
            ->sortBy('sort_order')
            ->values();

        $data['admin_aktif'] = $activeAdmins;

        return view('super-admin.dashboard', $data);
    }
}

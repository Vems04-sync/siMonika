<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $jumlahAplikasiAktif = Aplikasi::where('status_pemakaian', 'Aktif')->count();
        $jumlahAplikasiTidakDigunakan = Aplikasi::where('status_pemakaian', '!=', 'Aktif')->count();
        $aplikasis = Aplikasi::all();
        
        // Ambil waktu update terakhir
        $lastUpdate = $this->getLastUpdateTime();

        return view('index', compact(
            'user',
            'jumlahAplikasiAktif',
            'jumlahAplikasiTidakDigunakan',
            'aplikasis',
            'lastUpdate'
        ));
    }

    private function getLastUpdateTime()
    {
        $lastUpdate = Aplikasi::select('updated_at')
            ->orderBy('updated_at', 'desc')
            ->first();

        if ($lastUpdate) {
            return Carbon::parse($lastUpdate->updated_at)->timezone('Asia/Jakarta');
        }

        return Carbon::now()->timezone('Asia/Jakarta');
    }

    public function getLastUpdate()
    {
        $lastUpdate = $this->getLastUpdateTime();
        
        return response()->json([
            'lastUpdate' => $lastUpdate->format('Y-m-d H:i:s'),
            'formatted' => $lastUpdate->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm [WIB]')
        ]);
    }
}

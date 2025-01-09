<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $jumlahAplikasiAktif = Aplikasi::where('status_pemakaian', 'Aktif')->count();
        $jumlahAplikasiTidakDigunakan = Aplikasi::where('status_pemakaian', '!=', 'Aktif')->count();
        $aplikasis = Aplikasi::paginate(request('per_page', 10));

        return view('index', compact(
            'user',
            'jumlahAplikasiAktif',
            'jumlahAplikasiTidakDigunakan',
            'aplikasis'
        ));
    }
}

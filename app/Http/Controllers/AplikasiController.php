<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AplikasiController extends \Illuminate\Routing\Controller
{
    public function __construct()
    {
        // Terapkan middleware auth untuk semua method
        $this->middleware('auth');
    }

    /** 
     * Display a listing of the resource.
     */
    public function index()
    {
        // Jika ini untuk dashboard
        if (request()->route()->getName() === 'dashboard') {
            $jumlahAplikasiAktif = Aplikasi::where('status_pemakaian', 'Aktif')->count();
            $jumlahAplikasiTidakDigunakan = Aplikasi::where('status_pemakaian', '!=', 'Aktif')->count();
            $aplikasis = Aplikasi::all();
            
            return view('index', compact('jumlahAplikasiAktif', 'jumlahAplikasiTidakDigunakan', 'aplikasis'));
        }

        // Jika ini untuk aplikasi
        $aplikasis = Aplikasi::all();
        return view('aplikasi.index', compact('aplikasis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('aplikasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'opd' => 'required|max:100',
        ]);

        Aplikasi::create($request->all());
        return redirect()->route('aplikasi.index')->with('success', 'Aplikasi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aplikasi $aplikasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aplikasi $aplikasi)
    {
        return view('aplikasi.edit', compact('aplikasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aplikasi $aplikasi)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'opd' => 'required|max:100',
        ]);

        $aplikasi->update($request->all());
        return redirect()->route('aplikasi.index')->with('success', 'Aplikasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aplikasi $aplikasi)
    {
        $aplikasi->delete();
        return redirect()->route('aplikasi.index')->with('success', 'Aplikasi berhasil dihapus.');
    }

    public function getChartData()
    {
        // Data untuk status chart
        $statusData = Aplikasi::select('status_pemakaian', DB::raw('count(*) as total'))
            ->groupBy('status_pemakaian')
            ->get();
        
        // Data untuk jenis aplikasi
        $jenisData = Aplikasi::select('jenis', DB::raw('count(*) as total'))
            ->groupBy('jenis')
            ->get();
        
        // Data untuk basis platform
        $basisData = Aplikasi::select('basis_aplikasi', DB::raw('count(*) as total'))
            ->groupBy('basis_aplikasi')
            ->get();
        
        // Data untuk pengembang
        $pengembangData = Aplikasi::select('pengembang', DB::raw('count(*) as total'))
            ->groupBy('pengembang')
            ->get();

        return response()->json([
            'statusData' => $statusData,
            'jenisData' => $jenisData,
            'basisData' => $basisData,
            'pengembangData' => $pengembangData
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\AplikasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller;



class AplikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->get('per_page', 5); 
        $aplikasis = Aplikasi::paginate($perPage);

        if (request()->route()->getName() === 'dashboard') {
            $jumlahAplikasiAktif = Aplikasi::where('status_pemakaian', 'Aktif')->count();
            $jumlahAplikasiTidakDigunakan = Aplikasi::where('status_pemakaian', '!=', 'Aktif')->count();

            return view('index', compact('jumlahAplikasiAktif', 'jumlahAplikasiTidakDigunakan', 'aplikasis'));
        }

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
        $validatedData = $request->validate([
            'nama' => 'required',
            'opd' => 'required',
            'uraian' => 'nullable',
            'tahun_pembuatan' => 'nullable|date',
            'jenis' => 'required',
            'basis_aplikasi' => 'required',
            'bahasa_framework' => 'required',
            'database' => 'required',
            'pengembang' => 'required',
            'lokasi_server' => 'required',
            'status_pemakaian' => 'required'
        ]);

        Aplikasi::create($validatedData);

        return redirect()->route('aplikasi.index')
            ->with('success', 'Aplikasi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aplikasi $aplikasi)
    {
        return view('aplikasi.show', compact('aplikasi'));
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

    /**
     * Export data to an Excel file.
     */
    public function export()
    {
        try {
            return Excel::download(new AplikasiExport, 'aplikasi.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan saat melakukan ekspor: ' . $e->getMessage());
        }
    }

    /**
     * Get chart data.
     */
    public function getChartData()
    {
        $statusData = Aplikasi::select('status_pemakaian', DB::raw('count(*) as total'))
            ->groupBy('status_pemakaian')
            ->get();

        $jenisData = Aplikasi::select('jenis', DB::raw('count(*) as total'))
            ->groupBy('jenis')
            ->get();

        $basisData = Aplikasi::select('basis_aplikasi', DB::raw('count(*) as total'))
            ->groupBy('basis_aplikasi')
            ->get();

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

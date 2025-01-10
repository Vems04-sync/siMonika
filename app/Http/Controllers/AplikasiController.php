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
        return response()->json($aplikasi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aplikasi $aplikasi)
    {
        try {
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

            $aplikasi->update($validatedData);
            return redirect()->route('aplikasi.index')
                ->with('success', 'Aplikasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('aplikasi.index')
                ->with('error', 'Gagal memperbarui aplikasi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Aplikasi $aplikasi)
    // {
    //     try {
    //         $aplikasi->delete();
    //         return redirect()->route('aplikasi.index')
    //             ->with('success', 'Aplikasi berhasil dihapus.');
    //     } catch (\Exception $e) {
    //         return redirect()->route('aplikasi.index')
    //             ->with('error', 'Gagal menghapus aplikasi: ' . $e->getMessage());
    //     }
    // }

    /**
     * Remove the specified resource from storage by nama.
     */
    public function destroyByNama($nama)
    {
        try {
            $aplikasi = Aplikasi::where('nama', $nama)->firstOrFail();
            $aplikasi->delete();
            return redirect()->route('aplikasi.index')
                ->with('success', 'Aplikasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('aplikasi.index')
                ->with('error', 'Gagal menghapus aplikasi: ' . $e->getMessage());
        }
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

    /**
     * Show the form for editing the specified resource by nama.
     */
    public function editByNama($nama)
    {
        try {
            $aplikasi = Aplikasi::where('nama', $nama)->firstOrFail();
            return response()->json($aplikasi);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Aplikasi tidak ditemukan'], 404);
        }
    }

    /**
     * Update the specified resource in storage by nama.
     */
    public function updateByNama(Request $request, $nama)
    {
        try {
            $aplikasi = Aplikasi::where('nama', $nama)->firstOrFail();

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

            $aplikasi->update($validatedData);
            return redirect()->route('aplikasi.index')
                ->with('success', 'Aplikasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('aplikasi.index')
                ->with('error', 'Gagal memperbarui aplikasi: ' . $e->getMessage());
        }
    }
}

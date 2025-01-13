<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\AplikasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\AtributTambahan;



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
            Log::info('Starting export process');
            
            // Cek apakah ada data
            $count = Aplikasi::count();
            Log::info("Found {$count} records to export");
            
            $export = new AplikasiExport();
            Log::info('AplikasiExport instance created');
            
            return Excel::download($export, 'aplikasi.xlsx');
        } catch (\Exception $e) {
            Log::error('Export error details: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan saat mengexport data: ' . $e->getMessage());
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
            // Dapatkan semua kolom dari tabel
            $columns = Schema::getColumnListing('aplikasis');

            // Ambil data berdasarkan kolom yang ada
            $aplikasi = Aplikasi::where('nama', $nama)
                ->select($columns)
                ->firstOrFail();

            // Siapkan data untuk response
            $detailData = [];
            foreach ($columns as $column) {
                $detailData[$column] = $aplikasi->$column;
            }

            return response()->json($detailData);
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

    /**
     * Get detail aplikasi by nama.
     */
    public function getDetail($nama)
    {
        try {
            // Dapatkan semua kolom dari tabel
            $columns = Schema::getColumnListing('aplikasis');

            // Ambil data berdasarkan kolom yang ada
            $aplikasi = Aplikasi::where('nama', $nama)->firstOrFail();

            // Siapkan data untuk response, exclude kolom yang tidak perlu ditampilkan
            $detailData = [];
            foreach ($columns as $column) {
                // Skip kolom yang tidak perlu ditampilkan
                if (!in_array($column, ['id', 'created_at', 'updated_at'])) {
                    $detailData[$column] = $aplikasi->$column;
                }
            }

            return response()->json($detailData);
        } catch (\Exception $e) {
            info('Error di detail: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }

    /**
     * Get detail aplikasi by nama.
     */
    public function detail($nama)
    {
        try {
            $aplikasi = Aplikasi::where('nama', $nama)->firstOrFail();
            // Debug
            info('ID Aplikasi: ' . $aplikasi->id_aplikasi);
            
            $atribut = AtributTambahan::where('id_aplikasi', $aplikasi->id_aplikasi)->get();
            // Debug
            info('Atribut: ' . $atribut);

            return response()->json([
                'aplikasi' => $aplikasi,
                'atribut' => $atribut
            ]);
        } catch (\Exception $e) {
            info('Error di detail: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }
}

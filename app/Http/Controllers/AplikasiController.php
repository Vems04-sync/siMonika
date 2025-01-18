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
use App\Traits\CatatAktivitas;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AplikasiController extends Controller
{
    use CatatAktivitas;

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
        $atributs = AtributTambahan::all();
        return view('aplikasi.index', compact('aplikasis', 'atributs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $atributs = AtributTambahan::all();
        return view('aplikasi.create', compact('atributs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|unique:aplikasis,nama',
                'opd' => 'required',
                'uraian' => 'nullable',
                'tahun_pembuatan' => 'required|date',
                'jenis' => 'required',
                'basis_aplikasi' => 'required|in:Website,Desktop,Mobile',
                'bahasa_framework' => 'required',
                'database' => 'required',
                'pengembang' => 'required',
                'lokasi_server' => 'required',
                'status_pemakaian' => 'required|in:Aktif,Tidak Aktif'
            ]);

            $aplikasi = Aplikasi::create($validated);

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Tambah Aplikasi',
                'tipe_aktivitas' => 'create',
                'modul' => 'Aplikasi',
                'detail' => "Menambahkan aplikasi '{$aplikasi->nama}'"
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Aplikasi berhasil ditambahkan'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan aplikasi: ' . $e->getMessage()
            ], 500);
        }
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
    public function edit($nama)
    {
        try {
            $aplikasi = Aplikasi::where('nama', $nama)->firstOrFail();
            return response()->json($aplikasi);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data aplikasi'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nama)
    {
        DB::beginTransaction();
        try {
            $aplikasi = Aplikasi::where('nama', $nama)->firstOrFail();

            // Validasi
            $validated = $request->validate([
                'nama' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('aplikasis')->ignore($aplikasi->id_aplikasi, 'id_aplikasi')
                ],
                'opd' => 'required|string|max:255',
                'uraian' => 'nullable|string',
                'tahun_pembuatan' => 'required|date',
                'jenis' => 'required|string|max:255',
                'basis_aplikasi' => 'required|in:Website,Desktop,Mobile',
                'bahasa_framework' => 'required|string|max:255',
                'database' => 'required|string|max:255',
                'pengembang' => 'required|string|max:255',
                'lokasi_server' => 'required|string|max:255',
                'status_pemakaian' => 'required|in:Aktif,Tidak Aktif'
            ]);

            // Update aplikasi
            $aplikasi->update($validated);

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Update Aplikasi',
                'tipe_aktivitas' => 'update',
                'modul' => 'Aplikasi',
                'detail' => "Mengupdate aplikasi '{$aplikasi->nama}'"
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Aplikasi berhasil diperbarui'
            ]);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui aplikasi: ' . $e->getMessage()
            ], 500);
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
            $namaAplikasi = $aplikasi->nama;

            $aplikasi->delete();

            // Catat aktivitas penghapusan
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Hapus Aplikasi',
                'tipe_aktivitas' => 'delete',
                'modul' => 'Aplikasi',
                'detail' => "Menghapus aplikasi '{$namaAplikasi}'"
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Aplikasi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus aplikasi: ' . $e->getMessage()
            ], 500);
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
            $aplikasi = Aplikasi::where('nama', $nama)->firstOrFail();

            // Debug untuk melihat data
            Log::info('Aplikasi data:', ['aplikasi' => $aplikasi->toArray()]);

            $atributs = AtributTambahan::whereHas('aplikasis', function ($query) use ($aplikasi) {
                $query->where('aplikasis.id_aplikasi', $aplikasi->id_aplikasi);
            })->get();

            $existingAtributs = $aplikasi->atributTambahans()
                ->get()
                ->pluck('pivot.nilai_atribut', 'id_atribut')
                ->toArray();

            // Debug untuk melihat atribut
            Log::info('Existing atributs:', $existingAtributs);

            return view('aplikasi.edit', compact('aplikasi', 'atributs', 'existingAtributs'));
        } catch (\Exception $e) {
            Log::error('Error in editByNama: ' . $e->getMessage());
            return redirect()->route('aplikasi.index')
                ->with('error', 'Gagal memuat data aplikasi');
        }
    }

    /**
     * Update the specified resource in storage by nama.
     */
    public function updateByNama(Request $request, $nama)
    {
        try {
            $aplikasi = Aplikasi::where('nama', $nama)->firstOrFail();
            $oldStatus = $aplikasi->status_pemakaian;

            $aplikasi->update($request->all());

            LogAktivitas::create([
                'user_id' => Auth::user()->id_user,
                'aktivitas' => 'Update Aplikasi',
                'tipe_aktivitas' => 'update',
                'modul' => 'aplikasi',
                'detail' => "Mengubah status aplikasi {$nama} dari {$oldStatus} menjadi {$request->status_pemakaian}"
            ]);

            return redirect()->route('aplikasi.index')
                ->with('success', 'Aplikasi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
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
            // Ambil data aplikasi
            $aplikasi = Aplikasi::where('nama', $nama)->firstOrFail();

            // Ambil atribut tambahan dengan nilai dari tabel pivot
            $atributTambahan = DB::table('aplikasi_atribut')
                ->join('atribut_tambahans', 'aplikasi_atribut.id_atribut', '=', 'atribut_tambahans.id_atribut')
                ->where('aplikasi_atribut.id_aplikasi', $aplikasi->id_aplikasi)
                ->select(
                    'atribut_tambahans.nama_atribut',
                    'atribut_tambahans.tipe_data',
                    'aplikasi_atribut.nilai_atribut'
                )
                ->get();

            return response()->json([
                'success' => true,
                'aplikasi' => $aplikasi,
                'atribut_tambahan' => $atributTambahan,
                'debug' => [
                    'id_aplikasi' => $aplikasi->id_aplikasi,
                    'count_atribut' => $atributTambahan->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error di detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Data tidak ditemukan',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    // Fungsi helper untuk label field yang lebih mudah dibaca
    private function getFieldLabel($field)
    {
        $labels = [
            'nama' => 'Nama',
            'opd' => 'OPD',
            'uraian' => 'Uraian',
            'tahun_pembuatan' => 'Tahun Pembuatan',
            'jenis' => 'Jenis',
            'basis_aplikasi' => 'Basis Aplikasi',
            'bahasa_framework' => 'Bahasa/Framework',
            'database' => 'Database',
            'pengembang' => 'Pengembang',
            'lokasi_server' => 'Lokasi Server',
            'status_pemakaian' => 'Status Pemakaian'
        ];

        return $labels[$field] ?? $field;
    }
}

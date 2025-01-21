<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use App\Models\AtributTambahan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AtributTambahanController extends Controller
{
    public function index()
    {
        $atributs = AtributTambahan::with('aplikasis')->get();
        $aplikasis = Aplikasi::all();
        return view('atribut.index', compact('atributs', 'aplikasis'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'nama_atribut' => 'required|string|max:100|unique:atribut_tambahans',
                'tipe_data' => 'required|in:varchar,number,date,text',
                'nilai_default' => 'nullable|string'
            ]);

            $atribut = AtributTambahan::create([
                'nama_atribut' => $validated['nama_atribut'],
                'tipe_data' => $validated['tipe_data']
            ]);

            $aplikasis = Aplikasi::all();
            foreach ($aplikasis as $aplikasi) {
                $aplikasi->atributTambahans()->attach($atribut->id_atribut, [
                    'nilai_atribut' => $validated['nilai_default'] ?? null
                ]);
            }

            // Perbaikan format log
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Tambah Atribut',
                'tipe_aktivitas' => 'create',
                'modul' => 'Atribut',
                'detail' => sprintf(
                    "Admin %s menambahkan atribut baru '%s' ke %d aplikasi",
                    Auth::user()->nama,
                    $validated['nama_atribut'],
                    count($aplikasis)
                )
            ]);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $aplikasi = Aplikasi::findOrFail($id);
            $atributUpdates = $request->input('nilai_atribut', []);
            $successCount = 0;
            $errors = [];

            foreach ($atributUpdates as $atributId => $nilai) {
                try {
                    $atribut = AtributTambahan::findOrFail($atributId);

                    // Validasi berdasarkan tipe data
                    $rules = $this->getValidationRules($atribut->tipe_data);
                    $validator = Validator::make(
                        ['nilai_atribut' => $nilai],
                        ['nilai_atribut' => $rules]
                    );

                    if ($validator->fails()) {
                        $errors[] = "Nilai untuk {$atribut->nama_atribut} tidak sesuai dengan tipe data " .
                            $this->getTypeLabel($atribut->tipe_data);
                        continue;
                    }

                    $oldValue = $aplikasi->getNilaiAtribut($atributId);

                    $aplikasi->atributTambahans()->updateExistingPivot($atributId, [
                        'nilai_atribut' => $nilai
                    ]);

                    // Log aktivitas
                    LogAktivitas::create([
                        'user_id' => Auth::id(),
                        'aktivitas' => 'Update Nilai Atribut',
                        'tipe_aktivitas' => 'update',
                        'modul' => 'Atribut',
                        'detail' => "Mengubah nilai atribut '{$atribut->nama_atribut}' pada aplikasi '{$aplikasi->nama}' dari '{$oldValue}' menjadi '{$nilai}'"
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Gagal memperbarui atribut: " . $e->getMessage();
                }
            }

            if (count($errors) > 0) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => implode("\n", $errors)
                ], 422);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil memperbarui ' . $successCount . ' nilai atribut'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui nilai atribut: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $atribut = AtributTambahan::with('aplikasis')->findOrFail($id);
            $namaAtribut = $atribut->nama_atribut;
            $jumlahAplikasi = $atribut->aplikasis->count();

            // Perbaikan format log
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Hapus Atribut',
                'tipe_aktivitas' => 'delete',
                'modul' => 'Atribut',
                'detail' => sprintf(
                    "Admin %s menghapus atribut '%s' dari %d aplikasi",
                    Auth::user()->nama,
                    $namaAtribut,
                    $jumlahAplikasi
                )
            ]);

            $atribut->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Atribut berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus atribut');
        }
    }

    public function detail($id)
    {
        try {
            $atribut = AtributTambahan::with(['aplikasis' => function ($query) {
                $query->orderBy('nama');
            }])->findOrFail($id);

            return response()->json([
                'success' => true,
                'aplikasis' => $atribut->aplikasis
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail atribut'
            ], 500);
        }
    }

    public function updateNilai(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $atribut = AtributTambahan::findOrFail($id);
            $aplikasi = Aplikasi::findOrFail($request->id_aplikasi);

            // Validasi berdasarkan tipe data
            $rules = $this->getValidationRules($atribut->tipe_data);
            $validator = Validator::make(
                ['nilai_atribut' => $request->nilai_atribut],
                ['nilai_atribut' => $rules]
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nilai tidak sesuai dengan tipe data ' . $this->getTypeLabel($atribut->tipe_data)
                ], 422);
            }

            $oldValue = $aplikasi->getNilaiAtribut($id);

            $aplikasi->atributTambahans()->updateExistingPivot($id, [
                'nilai_atribut' => $request->nilai_atribut
            ]);

            // Log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Update Nilai Atribut',
                'tipe_aktivitas' => 'update',
                'modul' => 'Atribut',
                'detail' => "Mengubah nilai atribut '{$atribut->nama_atribut}' pada aplikasi '{$aplikasi->nama}' dari '{$oldValue}' menjadi '{$request->nilai_atribut}'"
            ]);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate nilai atribut'
            ], 500);
        }
    }

    private function getValidationRules($tipeData)
    {
        switch ($tipeData) {
            case 'number':
                return 'nullable|numeric';
            case 'date':
                return 'nullable|date';
            case 'varchar':
                return 'nullable|string|max:255';
            case 'text':
                return 'nullable|string';
            default:
                return 'nullable|string';
        }
    }

    private function getTypeLabel($tipeData)
    {
        switch ($tipeData) {
            case 'number':
                return 'Angka';
            case 'date':
                return 'Tanggal';
            case 'varchar':
                return 'Teks (max 255 karakter)';
            case 'text':
                return 'Teks Panjang';
            default:
                return 'Teks';
        }
    }
}

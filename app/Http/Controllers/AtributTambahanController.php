<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use App\Models\AtributTambahan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

            $request->validate([
                'id_aplikasi' => 'required|exists:aplikasis,id_aplikasi',
                'nilai_atribut' => 'nullable|string'
            ]);

            $aplikasi = Aplikasi::findOrFail($request->id_aplikasi);
            $atribut = AtributTambahan::findOrFail($id);

            $oldValue = $aplikasi->getNilaiAtribut($id);

            $aplikasi->atributTambahans()->updateExistingPivot($id, [
                'nilai_atribut' => $request->nilai_atribut
            ]);

            // Catat di log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Update Atribut',
                'tipe_aktivitas' => 'update',
                'modul' => 'Atribut',
                'detail' => "Mengubah nilai atribut '{$atribut->nama_atribut}' pada aplikasi '{$aplikasi->nama}' dari '{$oldValue}' menjadi '{$request->nilai_atribut}'"
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Nilai atribut berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memperbarui nilai atribut');
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
}

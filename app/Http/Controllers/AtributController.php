<?php

namespace App\Http\Controllers;

use App\Models\AtributTambahan;
use App\Models\Aplikasi;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AtributController extends Controller
{
    public function index()
    {
        $atributs = AtributTambahan::with('aplikasi')->get();
        $aplikasis = Aplikasi::all();
        return view('atribut.index', compact('atributs', 'aplikasis'));
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        try {
            // Log request data
            \Log::info('Request data:', $request->all());

            $validated = $request->validate([
                'id_aplikasi' => 'required|exists:aplikasis,id_aplikasi',
                'nama_atribut' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('atribut_tambahans')
                        ->where(function ($query) use ($request) {
                            return $query->where('id_aplikasi', $request->id_aplikasi)
                                ->where('nama_atribut', $request->nama_atribut);
                        })
                ],
                'nilai_atribut' => 'nullable|string'
            ]);

            // Cek aplikasi
            $aplikasi = Aplikasi::findOrFail($validated['id_aplikasi']);
            
            // Buat atribut
            $atribut = AtributTambahan::create([
                'id_aplikasi' => $validated['id_aplikasi'],
                'nama_atribut' => $validated['nama_atribut'],
                'nilai_atribut' => $validated['nilai_atribut'] ?? null
            ]);

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Tambah Atribut',
                'tipe_aktivitas' => 'create',
                'modul' => 'Atribut',
                'detail' => "Menambahkan atribut '{$atribut->nama_atribut}' pada aplikasi {$aplikasi->nama}"
            ]);

            \DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Atribut berhasil ditambahkan',
                    'data' => $atribut
                ]);
            }

            return redirect()->route('atribut.index')
                ->with('success', 'Atribut berhasil ditambahkan');

        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Error di AtributController@store: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan atribut: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->route('atribut.index')
                ->with('error', 'Gagal menambahkan atribut: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $atribut = AtributTambahan::findOrFail($id);
        return response()->json($atribut);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'id_aplikasi' => 'required|exists:aplikasis,id_aplikasi',
                'nama_atribut' => 'required|string|max:100',
                'nilai_atribut' => 'nullable|string'
            ]);

            $atribut = AtributTambahan::findOrFail($id);
            $oldNama = $atribut->nama_atribut;
            $oldAplikasi = $atribut->aplikasi->nama;

            $atribut->update($request->all());

            // Catat aktivitas
            $aplikasi = Aplikasi::find($request->id_aplikasi);
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Update Atribut',
                'tipe_aktivitas' => 'update',
                'modul' => 'Atribut',
                'detail' => "Mengubah atribut '{$oldNama}' pada aplikasi {$oldAplikasi}"
            ]);

            return redirect()->route('atribut.index')
                ->with('success', 'Atribut berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->route('atribut.index')
                ->with('error', 'Gagal mengupdate atribut: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $atribut = AtributTambahan::findOrFail($id);
            $namaAtribut = $atribut->nama_atribut;
            $namaAplikasi = $atribut->aplikasi->nama;

            $atribut->delete();

            // Catat aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Hapus Atribut',
                'tipe_aktivitas' => 'delete',
                'modul' => 'Atribut',
                'detail' => "Menghapus atribut '{$namaAtribut}' dari aplikasi {$namaAplikasi}"
            ]);

            return redirect()->route('atribut.index')
                ->with('success', 'Atribut berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('atribut.index')
                ->with('error', 'Gagal menghapus atribut: ' . $e->getMessage());
        }
    }
}

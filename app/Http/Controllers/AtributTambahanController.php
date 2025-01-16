<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use App\Models\AtributTambahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $request->validate([
            'nama_atribut' => 'required|string|max:100|unique:atribut_tambahans',
            'tipe_data' => 'required|in:varchar,number,date,text',
            'nilai_default' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            
            Log::info('Request data:', $request->all());
            
            $atribut = AtributTambahan::create([
                'nama_atribut' => $request->nama_atribut,
                'tipe_data' => $request->tipe_data
            ]);
            
            Log::info('Created atribut:', $atribut->toArray());
            
            // Terapkan ke semua aplikasi
            $aplikasis = Aplikasi::all();
            foreach ($aplikasis as $aplikasi) {
                $aplikasi->atributTambahans()->attach($atribut->id_atribut, [
                    'nilai_atribut' => $request->nilai_default
                ]);
            }
            
            DB::commit();
            return redirect()->back()->with('success', 'Atribut berhasil ditambahkan ke semua aplikasi');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menambahkan atribut: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_aplikasi' => 'required|exists:aplikasis,id_aplikasi',
            'nilai_atribut' => 'nullable|string'
        ]);

        try {
            $aplikasi = Aplikasi::findOrFail($request->id_aplikasi);
            $aplikasi->atributTambahans()->updateExistingPivot($id, [
                'nilai_atribut' => $request->nilai_atribut
            ]);

            return redirect()->back()->with('success', 'Nilai atribut berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui nilai atribut');
        }
    }

    public function destroy($id)
    {
        try {
            $atribut = AtributTambahan::findOrFail($id);
            $atribut->delete(); // Akan menghapus juga data di tabel pivot karena cascade

            return redirect()->back()->with('success', 'Atribut berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus atribut');
        }
    }
}

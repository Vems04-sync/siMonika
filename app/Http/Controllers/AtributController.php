<?php

namespace App\Http\Controllers;

use App\Models\AtributTambahan;
use App\Models\Aplikasi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        try {
            // Cek duplikasi secara manual
            $exists = AtributTambahan::where('id_aplikasi', $request->id_aplikasi)
                ->where('nama_atribut', $request->nama_atribut)
                ->exists();
            
            if ($exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Atribut ini sudah ada untuk aplikasi yang dipilih'
                ], 422);
            }

            $request->validate([
                'id_aplikasi' => 'required|exists:aplikasis,id_aplikasi',
                'nama_atribut' => 'required|string|max:100',
                'nilai_atribut' => 'nullable|string'
            ]);

            AtributTambahan::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Atribut berhasil ditambahkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan atribut: ' . $e->getMessage()
            ], 500);
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
            // Cek duplikasi secara manual, kecuali untuk record yang sedang diedit
            $exists = AtributTambahan::where('id_aplikasi', $request->id_aplikasi)
                ->where('nama_atribut', $request->nama_atribut)
                ->where('id_atribut', '!=', $id)
                ->exists();
            
            if ($exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Atribut ini sudah ada untuk aplikasi yang dipilih'
                ], 422);
            }

            $request->validate([
                'id_aplikasi' => 'required|exists:aplikasis,id_aplikasi',
                'nama_atribut' => 'required|string|max:100',
                'nilai_atribut' => 'nullable|string'
            ]);

            $atribut = AtributTambahan::findOrFail($id);
            $atribut->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Atribut berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui atribut: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $atribut = AtributTambahan::findOrFail($id);
            $atribut->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Atribut berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus atribut: ' . $e->getMessage()
            ], 500);
        }
    }
} 
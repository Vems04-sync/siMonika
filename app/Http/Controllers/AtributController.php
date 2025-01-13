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
            $request->validate([
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
            ], [
                'nama_atribut.unique' => 'Atribut ini sudah ada untuk aplikasi yang dipilih'
            ]);

            AtributTambahan::create($request->all());

            return redirect()->route('atribut.index')
                ->with('success', 'Atribut berhasil ditambahkan');
        } catch (\Exception $e) {
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
        $request->validate([
            'id_aplikasi' => 'required|exists:aplikasis,id_aplikasi',
            'nama_atribut' => 'required|string|max:100',
            'nilai_atribut' => 'nullable|string'
        ]);

        $atribut = AtributTambahan::findOrFail($id);
        $atribut->update($request->all());

        return redirect()->route('atribut.index')
            ->with('success', 'Atribut berhasil diupdate');
    }

    public function destroy($id)
    {
        $atribut = AtributTambahan::findOrFail($id);
        $atribut->delete();

        return redirect()->route('atribut.index')
            ->with('success', 'Atribut berhasil dihapus');
    }
} 
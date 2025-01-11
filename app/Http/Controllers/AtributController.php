<?php

namespace App\Http\Controllers;

use App\Models\AtributTambahan;
use App\Models\Aplikasi;
use Illuminate\Http\Request;

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
        $request->validate([
            'id_aplikasi' => 'required|exists:aplikasis,id_aplikasi',
            'nama_atribut' => 'required|string|max:100',
            'nilai_atribut' => 'nullable|string'
        ]);

        AtributTambahan::create($request->all());

        return redirect()->route('atribut.index')
            ->with('success', 'Atribut berhasil ditambahkan');
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
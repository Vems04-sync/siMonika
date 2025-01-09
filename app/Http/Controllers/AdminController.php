<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Pengguna::where('role', 'admin')->get();
        return view('admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:penggunas',
            'password' => 'required|min:6'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'admin';

        Pengguna::create($validated);
        return redirect()->route('admin.index')->with('success', 'Admin berhasil ditambahkan');
    }
}

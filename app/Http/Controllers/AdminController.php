<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewAdminCredentials;
use Illuminate\Support\Str;

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
        ]);

        // Generate random password
        $plainPassword = Str::random(8);
        $validated['password'] = Hash::make($plainPassword);
        $validated['role'] = 'admin';

        $admin = Pengguna::create($validated);

        // Kirim email
        Mail::to($validated['email'])->send(new NewAdminCredentials(
            $validated['nama'],
            $validated['email'],
            $plainPassword
        ));

        return redirect()
            ->route('admin.index')
            ->with('success', 'Admin berhasil ditambahkan dan kredensial telah dikirim ke email.');
    }

    public function edit($id)
    {
        $admin = Pengguna::findOrFail($id);
        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Pengguna::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:penggunas,email,' . $id . ',id_user',
            'password' => 'nullable|min:6'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);
        return redirect()->route('admin.index')->with('success', 'Admin berhasil diupdate');
    }

    public function destroy($id)
    {
        $admin = Pengguna::findOrFail($id);
        $admin->delete();
        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus');
    }
}

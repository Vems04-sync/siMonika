<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengguna;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = Pengguna::find(Auth::id());

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'password' => 'nullable|min:8|confirmed'
        ]);

        $user->nama = $validated['nama'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diupdate');
    }
}

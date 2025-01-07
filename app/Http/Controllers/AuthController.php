<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengguna;

class AuthController extends \Illuminate\Routing\Controller
{
    public function __construct()
    {
        // Terapkan middleware guest untuk method tertentu
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:penggunas',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }
} 
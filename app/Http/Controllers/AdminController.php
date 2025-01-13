<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewAdminCredentials;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Mail\EmailUpdateNotification;

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
        try {
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

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil ditambahkan'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $admin = Pengguna::findOrFail($id);
            return response()->json($admin);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Admin tidak ditemukan'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $admin = Pengguna::findOrFail($id);
            $oldEmail = $admin->email;

            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'email' => 'required|email|unique:penggunas,email,' . $id . ',id_user',
                'password' => 'nullable|min:6'
            ]);

            // Jika email berubah, kirim notifikasi ke email baru
            if ($validated['email'] !== $oldEmail) {
                // Kirim email notifikasi ke alamat baru
                Mail::to($validated['email'])->send(new EmailUpdateNotification(
                    $validated['nama'],
                    $validated['email'],
                    $oldEmail
                ));
            }

            // Jika password diisi, update password
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                // Jika password tidak diisi, hapus dari array validated
                unset($validated['password']);
            }

            $admin->update($validated);

            return response()->json([
                'success' => true,
                'message' => $validated['email'] !== $oldEmail ?
                    'Admin berhasil diupdate dan notifikasi telah dikirim ke email baru' :
                    'Admin berhasil diupdate'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $admin = Pengguna::findOrFail($id);
        $admin->delete();
        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus');
    }
}

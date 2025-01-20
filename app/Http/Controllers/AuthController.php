<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengguna;
use Illuminate\Validation\Rules\Password;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            'email' => ['required', 'email', 'exists:penggunas'],
            'password' => ['required', Password::min(8)],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // Catat aktivitas login
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Login',
                'tipe_aktivitas' => 'login',
                'modul' => 'Auth',
                'detail' => 'User login ke sistem'
            ]);

            // Update last_activity
            Auth::user()->update([
                'last_activity' => now()
            ]);

            // Ubah redirect untuk super admin ke dashboard super admin
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('super-admin.dashboard');
            }
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        // Catat aktivitas logout
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Logout',
            'tipe_aktivitas' => 'logout',
            'modul' => 'Auth',
            'detail' => 'User logout dari sistem'
        ]);

        // Set last_activity ke null saat logout
        Auth::user()->update([
            'last_activity' => null
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Berhasil logout dari sistem');
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

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:penggunas,email',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar dalam sistem'
        ]);

        $token = Str::random(64);

        // Simpan token ke database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        // Kirim email
        Mail::send('emails.reset-password', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password - SiMonika');
        });

        return back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox email Anda.');
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:penggunas',
            'password' => 'required|confirmed|min:8',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar',
            'password.required' => 'Password baru wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter'
        ]);

        // Cek token valid
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetToken) {
            return back()->withErrors(['email' => 'Token reset password tidak valid.']);
        }

        // Cek token tidak expired (60 menit)
        if (Carbon::parse($resetToken->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Token reset password sudah kadaluarsa.']);
        }

        // Update password
        $user = Pengguna::where('email', $request->email)->first();
        $user->update([
            'password' => bcrypt($request->password)
        ]);

        // Hapus token yang sudah digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Catat di log aktivitas
        LogAktivitas::create([
            'user_id' => $user->id_user,
            'aktivitas' => 'Reset Password',
            'tipe_aktivitas' => 'update',
            'modul' => 'auth',
            'detail' => 'User melakukan reset password'
        ]);

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}

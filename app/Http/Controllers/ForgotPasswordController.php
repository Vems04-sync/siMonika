<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\OtpMail;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email tidak terdaftar'
            ], 404);
        }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Simpan OTP ke session
        session([
            'reset_email' => $request->email,
            'reset_otp' => $otp,
            'reset_otp_expires' => now()->addMinutes(5)
        ]);

        // Kirim email OTP
        Mail::to($user->email)->send(new OtpMail($user->nama, $otp));

        return response()->json(['message' => 'OTP berhasil dikirim']);
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        if (!session('reset_otp') || 
            !session('reset_otp_expires') || 
            now()->isAfter(session('reset_otp_expires'))) {
            return response()->json([
                'message' => 'OTP telah kadaluarsa'
            ], 400);
        }

        if ($request->otp !== session('reset_otp')) {
            return response()->json([
                'message' => 'Kode OTP tidak valid'
            ], 400);
        }

        return response()->json(['message' => 'OTP valid']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        if (!session('reset_email')) {
            return response()->json([
                'message' => 'Sesi telah berakhir'
            ], 400);
        }

        $user = User::where('email', session('reset_email'))->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session
        session()->forget(['reset_email', 'reset_otp', 'reset_otp_expires']);

        return response()->json(['message' => 'Password berhasil direset']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Tampilkan Halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended('/')->with('success', 'Berhasil login!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Tampilkan Halaman Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses Register dengan Transaction & Error Handling
    public function register(Request $request)
    {
        // 1. Validasi Input (Sistem akan berhenti di sini jika password < 8)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. Mulai Database Transaction
        DB::beginTransaction();

        try {
            // A. Masukkan ke database
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // B. Kirim Email Verifikasi (Magic Link)
            event(new Registered($user));

            // C. Jika A dan B sukses, patenkan data di database
            DB::commit();

            // Langsung login dan arahkan ke halaman tunggu verifikasi
            Auth::login($user);
            return redirect()->route('verification.notice');

        } catch (\Exception $e) {
            // Jika terjadi error (misal gagal kirim email), HAPUS kembali data yang mau masuk!
            DB::rollBack();
            
            // Catat pesan error aslinya di file log laravel (storage/logs/laravel.log) agar mudah dilacak
            Log::error('Register Error: ' . $e->getMessage());

            // Kembalikan user ke halaman register dengan pesan error
            return back()->withErrors([
                'email' => 'Terjadi kesalahan sistem saat mengirim email. Pastikan koneksi internet stabil dan coba lagi.',
            ])->withInput();
        }
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah logout.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function showLogin() {
        return view('auth.login');
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {

            $request->session()->regenerate();

            // Jika role adalah admin, arahkan ke dashboard admin
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Jika user biasa, arahkan ke dashboard user
            return redirect('/dashboard');
        }

        return back()->with('error', 'Email atau password salah');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Dialihkan ke form login agar user melakukan login manual setelah mendaftar
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar!']);
        }

        // Simpan di session agar edit-password tahu user mana yang direset
        session(['reset_user_id' => $user->id]);

        return redirect()->route('profil.edit-password');
    }

    // --- FUNGSI UPDATE PASSWORD ---
    public function updatePassword(Request $request) {
        $request->validate([
            'password' => 'required|confirmed|min:8',
            'current_password' => Auth::check() ? 'required' : 'nullable',
        ]);

        // 1. Logika untuk User yang BELUM LOGIN (Lupa Password)
        if (!Auth::check()) {
            // Ambil ID dari session jika ada, atau dari input email
            $userId = session('reset_user_id');
            if (!$userId) {
                return back()->withErrors(['email' => 'Silakan masukkan email Anda kembali.']);
            }
            $user = User::find($userId);
        } 
        // 2. Logika untuk User yang SUDAH LOGIN (Ganti Password Profil)
        else {
            $user = Auth::user();
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama Anda salah!']);
            }
        }

        // Update ke password baru
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus session reset jika ada
        session()->forget('reset_user_id');

        return Auth::check() 
            ? back()->with('success', 'Password berhasil diubah!')
            : redirect('/login')->with('success', 'Password berhasil direset! Silakan login.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
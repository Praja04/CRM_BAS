<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Cek apakah pengguna sudah login
        if (Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah login.',
            ]);
        }

        // Proses login
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'redirect' => url('layout'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Login gagal. Periksa username atau password Anda.',
        ], 401);
    }



    public function logout(Request $request)
    {
        // Hapus session yang ada
        Auth::logout();

        // Hapus token CSRF jika menggunakan token untuk API
        $request->session()->invalidate();

        // Menghancurkan semua session yang tersimpan
        $request->session()->regenerateToken();

        // Menghapus semua cookies yang terkait dengan aplikasi
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

}

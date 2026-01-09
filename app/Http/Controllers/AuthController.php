<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) { // jika sudah login, redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }

        return redirect('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    // Tampilkan form register
    public function register()
    {
        $level = \App\Models\LevelModel::all(); // Ambil data level untuk dropdown
        return view('auth.register', ['level' => $level]);
    }

    // Proses simpan user baru
    public function postregister(Request $request)
    {
        $request->validate([
            'username' => 'required|min:4|unique:m_user,username',
            'nama' => 'required|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer'
        ]);

        \App\Models\UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Register Berhasil',
            'redirect' => url('login')
        ]);
    }
    
}
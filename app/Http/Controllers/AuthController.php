<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
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
            
            // Redirect sementara ke halaman home/dashboard setelah login
            if (Auth::attempt($credentials)) {

                $request->session()->regenerate();

                $user = Auth::user();

                switch ($user->role) {

                    case 'PemilikRumah':
                        return redirect()->route('pemilik.index');

                    case 'PetugasLab':
                        return redirect()->route('petugas_lab.index');

                    case 'Kontraktor':
                    case 'TeknisiLapangan':
                    case 'PetugasLapangan':
                    default:
                        return redirect()->route('dashboard');
                }
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
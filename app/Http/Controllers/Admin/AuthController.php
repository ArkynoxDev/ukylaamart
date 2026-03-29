<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        if (!Auth::user()->isAdmin()) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun ini bukan admin.']);
        }

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
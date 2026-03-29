<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil!',
            'data'    => ['token' => $token, 'user' => ['name' => $user->name, 'email' => $user->email]],
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'data'    => ['token' => $token, 'user' => ['name' => $user->name, 'email' => $user->email]],
        ]);
    }

    public function me(Request $request)
    {
        return response()->json(['success' => true, 'data' => $request->user()]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => true, 'message' => 'Logout berhasil!']);
    }
}
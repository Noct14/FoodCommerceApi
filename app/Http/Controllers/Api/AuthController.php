<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        return response()->json([
            'message' => 'register sukses',
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'login gagal'], 401);
        }

        $user = Auth::user();

        $user->tokens()->delete();

        $token = $user->createToken('auth_token', ['*'], now()->addHour())->plainTextToken;

        return response()->json([
            'message' => 'login sukses',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {

        $user = $request->user();


        if (!$user || !$user->currentAccessToken()) {
            return response()->json([
                'message' => 'Tidak ada token aktif. Anda sudah logout atau belum login.',
            ], 400); // 400 Bad Request
        }


        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout sukses',
        ], 200); // 200 OK
    }

}

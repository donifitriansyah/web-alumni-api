<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\BaseController;

class AuthController extends BaseController
{
    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
            'nama' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'role' => 'admin',
        ]);

        $admin = Admin::create([
            'id_user' => $user->id, // Simpan id_user
            'nama' => $request->nama,
            'nomor_induk' => $request->nomor_induk,
            'no_hp' => $request->no_hp,
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Admin registered successfully',
            'user' => $user,  // Mengembalikan data user
            'admin' => $admin, // Mengembalikan data admin
            'token' => $token  // Mengembalikan token JWT
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = JWTAuth::user();

        if ($user->role === 'admin') {
            $additionalData = $user->admin; // Ambil data admin
        } elseif ($user->role === 'alumni') {
            $additionalData = $user->alumni; // Ambil data alumni
        } elseif ($user->role === 'perusahaan') {
            $additionalData = $user->perusahaan; // Ambil data perusahaan
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token // Token JWT
        ]);
    }

    // Logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }
}

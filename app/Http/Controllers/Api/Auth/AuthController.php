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
use App\Models\Alumni;

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
            return $this->sendError('Validation Error', $validator->errors()); // Menggunakan BaseController
        }

        try {
            $user = User::create([
                'username' => $request->get('username'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'role' => 'admin',
            ]);

            $admin = Admin::create([
                'id_user' => $user->id,
                'nama' => $request->nama,
                'nomor_induk' => $request->nomor_induk,
                'no_hp' => $request->no_hp,
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Admin registered successfully',
                'user' => $user,
                'admin' => $admin,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            return $this->sendError('Server Error', ['error' => $e->getMessage()]);
        }
    }

    public function registerAlumni(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'nim' => 'required|string|max:10|unique:alumni',
            'nama_alumni' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'no_tlp' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()); // Menggunakan BaseController
        }

        try {
            $user = User::create([
                'username' => $request->get('username'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'role' => 'alumni',
            ]);

            $alumni = Alumni::create([
                'id_user' => $user->id,
                'nim' => $request->nim,
                'nama_alumni' => $request->nama_alumni,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'no_tlp' => $request->no_tlp,
                'status' => "pasif",
                'email' => $request->email,
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Alumni registered successfully',
                'user' => $user,
                'alumni' => $alumni,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            return $this->sendError('Server Error', ['error' => $e->getMessage()]);
        }
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{   
    public function index()
    {   
        return response()->json([
            "status" => false,
            "message" => "harap login terlebih dahulu"
        ], 401);
    }

    // Login Account
    public function login(Request $request)
    {   
        // Validasi Inputan
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();

        if (Auth::attempt($validated)) {
            $user = Auth::user();
            $payload = [
                'iss' => 'laundryin.com',
                'name' => $user->name,
                'role' => $user->role,
                'iat' => now()->timestamp,
                'exp' => now()->timestamp * 60
            ];

            // Generate token
            $jwt = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');

            // Kirim token ke user
            return response()->json(['messages' => 'Token Berhasil Dibuat', 'token' => $jwt], 200);
        }
        return response()->json(['messages' => 'Account Tidak Ditemukan.'], 422);
    }

    // Register Account Karyawan
    public function register (request $request){
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        // Jika inputan salah
        if($validator->fails()){
            return response()->json($validator->messages(), 422);
        }

        // Inputan yang benar
        $validated = $validator->validated();

        User::create([
            'nama' => $validated['nama'],
            'email'=> $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);
        
        return response()->json(['messages' => 'Register Berhasil'], 201);
    }

    // Register Account Owner
    public function registerOwner (request $request){
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        // Jika inputan salah
        if($validator->fails()){
            return response()->json($validator->messages(), 422);
        }

        // Inputan yang benar
        $validated = $validator->validated();

        User::create([
            'nama' => $validated['nama'],
            'email'=> $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'owner'
        ]);
        
        return response()->json(['messages' => 'Register Berhasil'], 201);
    }

    // Login With Google
    public function loginWithGoogle(Request $request){
        $token = $request->accessToken;
        $googleUser = Socialite::driver('google')->userFromToken($token);
        $user = User::where('email', $googleUser->email)->first();

        if ($user) {
                // Jika pengguna sudah ada, lakukan login
                $token = auth()->guard('api')->login($user);
                return response()->json([
                    "status" => true,
                    "message" => "Login berhasil dengan Google",
                    "token" => $token
                ], 200);
            } else {
                // Pengguna belum ada, buat pengguna baru
                $user = User::create([
                    'nama' => $googleUser->nama,
                    'email' => $googleUser->email,
                    'password' => bcrypt('123456dummy')
                ]);

                $token = auth()->guard('api')->login($user);
                return response()->json([
                    "status" => true,
                    "message" => "Pendaftaran berhasil dengan Google",
                    "token" => $token
                ], 200);
            }
    }
}

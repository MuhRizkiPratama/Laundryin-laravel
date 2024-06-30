<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request){ 
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
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
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            return response()->json([
                "status" => false,
                "message" => "Invalid state exception: " . $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Error: " . $e->getMessage()
            ], 401);
        }
    }
}

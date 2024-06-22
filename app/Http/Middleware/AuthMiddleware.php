<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        // Ambil token dan masukan ke dalam variable $jwt
        $jwt = $request->bearerToken();

        if($jwt == 'null' || $jwt == ''){
            return response()->json(['messages' => 'Akses ditolak, token tidak memenuhi'], 401);
        } else {

            // decode token
            $jwtDecoded = JWT::decode($jwt, new Key(env('JWT_SECRET_KEY'), 'HS256'));

            //Menyimpan hasil decode ke dalam request
            $request->auth = $jwtDecoded;

            return $next($request);
        }
        return response()->json(['messages'=>'Akses ditolak, token tidak memenuhi.'], 401);
    }
}

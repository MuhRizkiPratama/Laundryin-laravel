<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil informasi token dari request yang sudah di-verify oleh middleware Authenticate
        $auth = $request->auth;

        // Jika role adalah owner
        if (isset($auth->role) && $auth->role == 'owner') {
            return $next($request);
        }
        return response()->json(['messages' => 'Akses ditolak, Anda Bukan owner.'], 401);
    }
}

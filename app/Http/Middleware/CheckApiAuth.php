<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckApiAuth
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('CheckApiAuth middleware ejecutado', [
            'api_token' => session('api_token') ? 'EXISTE' : 'NO_EXISTE',
            'api_user' => session('api_user') ? 'EXISTE' : 'NO_EXISTE',
            'session_id' => session()->getId()
        ]);

        // Verificar si existe el token y usuario en sesión
        if (!session('api_token') || !session('api_user')) {
            Log::info('Usuario no autenticado, redirigiendo al login');
            return redirect()->route('demo.login');
        }

        Log::info('Usuario autenticado, continuando con la request');
        return $next($request);
    }
}
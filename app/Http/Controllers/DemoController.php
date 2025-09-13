<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Usuario;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;


class DemoController extends Controller
{
    // Mostrar el formulario de login
    public function loginForm()
    {
        return view('demo.login');
    }

    // Procesar el login del formulario
    public function loginDo(Request $request)
{
    // Validar datos del form
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

  

    try {
        // Llamada al endpoint de la API (servidor en puerto 8001)
        $response = Http::acceptJson()
            ->timeout(5)
            ->withoutRedirecting()
            ->post(config('services.api.base_url') . '/api/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

        

        if ($response->successful()) {
            $json = $response->json();
            
            Log::info('JSON parsed:', $json);
            
            // Guardar token y user en sesión
            session([
                'api_token' => $json['token'] ?? null,
                'api_user' => $json['usuario'] ?? null,
            ]);
            
            // Forzar guardado de sesión
            session()->save();
            
            Log::info('Session saved:', [
                'api_token_exists' => session()->has('api_token'),
                'api_user_exists' => session()->has('api_user'),
                'session_id' => session()->getId()
            ]);
            
            return redirect()->route('demo.bienvenida')->with('status', 'Has iniciado sesión correctamente');
        } else {
            Log::error('API Login failed:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        }
        
    } catch (\Exception $e) {
        Log::error('Login exception:', ['error' => $e->getMessage()]);
        return back()->withErrors([
            'email' => 'Error al conectar con la API: ' . $e->getMessage()
        ])->withInput();
    }

    // Si falla, mostrar el status y body
    return back()->withErrors([
        'email' => 'Login API fallo: status '.$response->status().' body: '.$response->body()
    ])->withInput();
}


// Mostrar el formulario de registro
    public function registerForm()
{
    return view('demo.register');
}
public function register(Request $request)
{
    // 1) Validación
    $validated = $request->validate([
        'name'      => ['required', 'string', 'min:3', 'max:100'],
        'ape'       => ['required', 'string', 'min:3', 'max:100'],
        'email'     => ['required', 'email:rfc,dns', 'unique:usuarios,email'],
        'phone'     => ['nullable', 'string', 'min:9', 'max:15'],
        'password'  => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        'rol'       => ['required', Rule::in(['alumno', 'entrenador'])],
    ]);

    // 2) Crear usuario
    $user = Usuario::create([
        'nombre'        => $validated['name'],
        'apellidos'     => $validated['ape'],
        'email'         => $validated['email'],
        'telefono'      => $validated['phone'] ?? null,
        'password_hash' => Hash::make($validated['password']),
        'rol'           => $validated['rol'],  // valor elegido por el usuario
        'estado'        => 'activo',           // siempre activo al registrarse
    ]);

    // 3) Autologin
    Auth::login($user);

    // 4) Redirigir
    return redirect()->route('demo.login')->with('status', 'Inicia sesión con tu nuevo usuario');
}
  public function bienvenida()
{
    // DEBUG: Verificar qué hay en la sesión
    Log::info('Bienvenida accessed:', [
        'session_id' => session()->getId(),
        'api_token_exists' => session()->has('api_token'),
        'api_user_exists' => session()->has('api_user'),
        'api_token_value' => session('api_token') ? 'EXISTE' : 'NO EXISTE',
        'api_user_value' => session('api_user') ? 'EXISTE' : 'NO EXISTE',
    ]);
    
    // Verificar si el usuario tiene sesión activa
    if (!session('api_token') || !session('api_user')) {
        Log::info('Bienvenida redirect to login:', [
            'reason' => 'Missing session data',
            'api_token' => session('api_token'),
            'api_user' => session('api_user')
        ]);
        
        return redirect()->route('demo.bienvenida')
               ->withErrors(['email' => 'Debes iniciar sesión primero']);
    }
    
    Log::info('Bienvenida showing view');
    return view('demo.bienvenida');
}


    public function logout(Request $request)
{
    // Borrar datos de la sesión de la API
    $request->session()->forget(['api_token', 'api_user']);
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Redirigir a tu login personalizado
    return redirect()->route('demo.login')->with('status', 'Sesión cerrada');
}




}

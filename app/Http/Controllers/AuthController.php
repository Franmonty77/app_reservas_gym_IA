<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    //

     public function register(RegisterRequest $request)
    {
        // Si la validación falla, Laravel devuelve 422 automáticamente
        $data = $request->validated();

        $user = Usuario::create([
            'nombre'        => $data['nombre'],
            'apellidos'     => $data['apellidos'],
            'email'         => $data['email'],
            'telefono'      => $data['telefono'] ?? null,
            'password_hash' => Hash::make($data['password']),
            'rol'           => $data['rol'] ?? 'alumno',
            'estado'        => $data['estado'] ?? 'activo',
        ]);

        // Token (solo si tienes Sanctum)
        $token = method_exists($user, 'createToken')
            ? $user->createToken('api')->plainTextToken
            : null;

        return response()->json([
            'message' => 'Usuario registrado correctamente.',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }


   public function login(Request $request)
{
    // 1) Validación
    $credenciales = $request->validate([
        'email'    => ['required','email'],
        'password' => ['required','string']
    ]);

    // Normaliza email por si viene con espacios o mayúsculas
    $email = strtolower(trim($credenciales['email']));

    // 2) Buscar usuario por email
    $usuario = Usuario::where('email', $email)->first();

    // 3) Verificar contraseña
    if (!$usuario || !Hash::check($credenciales['password'], $usuario->password_hash)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }


    // 4) Crear token (Sanctum)
    $token = $usuario->createToken('auth_token')->plainTextToken;

    // 5) Respuesta
    return response()->json([
        'message' => 'Login correcto',
        'token'   => $token,
        'usuario' => [
            'id'        => $usuario->id,
            'nombre'    => $usuario->nombre,
            'apellidos' => $usuario->apellidos,
            'email'     => $usuario->email,
            'rol'       => $usuario->rol,
            'estado'    => $usuario->estado,
        ]
    ], 200);
}


    public function logout(Request $request){

        //Borrar token actual
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logout correcto'],200);

    }
}

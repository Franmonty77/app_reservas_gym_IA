<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;


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


  public function login(LoginRequest $request)
{
    $credentials = $request->only('email', 'password');

    if (!auth()->attempt($credentials)) {
        return response()->json([
            'message' => 'Credenciales incorrectas'
        ], 401);
    }

    /** @var \App\Models\Usuario $usuario */
    $usuario = auth()->user();

    $token = $usuario->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login correcto',
        'usuario' => $usuario,
        'token' => $token,
    ]);
}



    public function logout(Request $request){

        //Borrar token actual
        $request->usuario()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logout correcto'],200);

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //

    public function register(Request $request){


        //1. Validar datos
        $validacion=$request->validate([
        'nombre'=>'required|string|max:120',
        'apellidos'=>'nullable|string|max:160',
        'email'=>'required|string|email|max:190|unique:users',
        'telefono'=>'nullable|string|max:30',
        'password'=>'required|string|min:6|'
        ]);

        //2.Crear usuario
        $usuario=Usuario::create([
            'nombre'=>$validacion['nombre'],
            'apellidos'=>$validacion['apellidos'] ?? null,
            'email'=>$validacion['email'],
            'telefono'=>$validacion['telefono'] ?? null,
            'password_hash'=>Hash::make($validacion['password']),
            'rol'=>'alumno',
            'estado'=>'activo'
        ]);

        //3.Respuesta
        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'usuario' => [
                'id'        => $usuario->id,
                'nombre'    => $usuario->nombre,
                'apellidos' => $usuario->apellidos,
                'email'     => $usuario->email,
                'telefono'  => $usuario->telefono,
                'rol'       => $usuario->rol,
                'estado'    => $usuario->estado,
                'created_at'=> $usuario->created_at
            ]
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

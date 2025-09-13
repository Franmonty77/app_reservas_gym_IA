<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir a cualquiera registrarse
    }

    public function rules(): array
    {
        return [
            'nombre'    => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:150'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:usuarios,email'],
            'telefono'  => ['nullable', 'string', 'max:20'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'rol'       => ['nullable', Rule::in(['alumno','entrenador','admin'])],
            'estado'    => ['nullable', Rule::in(['activo','inactivo'])],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'      => 'El nombre es obligatorio.',
            'apellidos.required'   => 'Los apellidos son obligatorios.',
            'email.required'       => 'El email es obligatorio.',
            'email.email'          => 'El email no es válido.',
            'email.unique'         => 'Este email ya está registrado.',
            'password.required'    => 'La contraseña es obligatoria.',
            'password.min'         => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed'   => 'Las contraseñas no coinciden.',
            'rol.in'               => 'El rol no es válido.',
            'estado.in'            => 'El estado no es válido.',
        ];
    }
}

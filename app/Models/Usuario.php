<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Importante: para login/auth
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'usuarios'; // nombre real de la tabla

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'password_hash',
        'rol',
        'estado',
    ];

    // Campos que no quieres que aparezcan en la respuesta JSON
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    // Si necesitas casts
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}


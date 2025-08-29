<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Importante: para login/auth
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre', 'apellidos', 'email', 'telefono', 'password_hash', 'rol', 'estado'
    ];

    protected $hidden = ['password_hash', 'remember_token'];

    // 👇 Indicamos que el "password" real es `password_hash`
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}



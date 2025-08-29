<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre', 'apellidos', 'email', 'telefono', 'password_hash', 'rol', 'estado'
    ];

    protected $hidden = [
        'password_hash', 'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}


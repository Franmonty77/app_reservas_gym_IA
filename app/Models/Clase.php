<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    use HasFactory;
    protected $table = 'clases';
    protected $fillable = ['nombre', 'descripcion','intensidad', 'duracion_min', 'aforo_base', 'color_hex','activa' ];


    public function sesiones()
    {
        return $this->hasMany(Reserva::class, 'clase_id');
    }
}

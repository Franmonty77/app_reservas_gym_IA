<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
    use HasFactory;
    protected $table = 'sesiones';
    protected $fillable = ['clase_id','entrenador_id','fecha_inicio','fecha_fin','aforo_max','centro','sala','estado','notas'];

    public function clase()
    {
        return $this->belongsTo(Clase::class, 'clase_id');
    }

    public function entrenador()
    {
        return $this->belongsTo(Usuario::class, 'entrenador_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'sesion_id');
    }


     // 👇 Accessor: se calcula a partir de aforo_max y withCount('reservas as ocupacion')
    public function getPlazasDisponiblesAttribute()
    {
        $ocupacion = (int) ($this->ocupacion ?? 0);
        return max(0, (int) $this->aforo_max - $ocupacion);
    }




}

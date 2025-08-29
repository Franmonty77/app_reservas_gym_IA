<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SesionController extends Controller
{
    public function index(Request $request)
    {
        $q = Sesion::query()
            ->with([
                'clase:id,nombre,intensidad,duracion_min,color_hex',
                'entrenador:id,nombre,apellidos,email'
            ])
            ->withCount(['reservas as ocupacion' => function ($sub) {
                $sub->whereIn('estado', ['confirmada','asistida']);
            }]);

        // --- Filtros ---
        if ($fecha = $request->query('fecha')) {
            try {
                $day = Carbon::parse($fecha)->startOfDay();
                $q->whereBetween('fecha_inicio', [$day, (clone $day)->endOfDay()]);
            } catch (\Throwable $e) {
                return response()->json(['message' => 'Formato de fecha inválido. Usa YYYY-MM-DD'], 422);
            }
        } else {
            $now = now();
            $q->whereBetween('fecha_inicio', [$now, (clone $now)->addHours(48)]);
        }

        if ($claseId = $request->query('clase_id')) {
            $q->where('clase_id', (int) $claseId);
        }
        if ($entrenadorId = $request->query('entrenador_id')) {
            $q->where('entrenador_id', (int) $entrenadorId);
        }
        if ($centro = $request->query('centro')) {
            $q->where('centro', 'like', "%{$centro}%");
        }
        if ($sala = $request->query('sala')) {
            $q->where('sala', 'like', "%{$sala}%");
        }

        $estado = $request->query('estado', 'programada');
        $q->where('estado', $estado);

        $q->orderBy('fecha_inicio')->orderBy('sala');

        $perPage = max(1, min(50, (int) $request->query('per_page', 10)));

        $perPage = max(1, min(50, (int) $request->query('per_page', 10)));
    return $q->paginate($perPage);

}
}
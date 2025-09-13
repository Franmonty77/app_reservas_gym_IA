<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SesionController extends Controller
{
  public function index()
{
    $request = new Request(session('filtros', []));
    return $this->getSesiones($request);
}

public function filter(Request $request)
{
    // Guardar filtros en sesión
    $filtros = $request->only(['fecha', 'centro', 'sala', 'estado', 'clase_id', 'entrenador_id', 'per_page']);
    session(['filtros' => $filtros]);
    
    return redirect()->route('sesiones.index');
}

public function clear()
{
    session()->forget('filtros');
    return redirect()->route('sesiones.index');
}

private function getSesiones(Request $request)
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
    if ($fecha = $request->input('fecha')) {
        try {
            $day = Carbon::parse($fecha)->startOfDay();
            $q->whereBetween('fecha_inicio', [$day, (clone $day)->endOfDay()]);
        } catch (\Throwable $e) {
            return back()->withErrors(['fecha' => 'Formato de fecha inválido']);
        }
    } else {
        $now = now();
        $q->whereBetween('fecha_inicio', [$now, (clone $now)->addHours(48)]);
    }

    if ($claseId = $request->input('clase_id')) {
        $q->where('clase_id', (int) $claseId);
    }

    if ($entrenadorId = $request->input('entrenador_id')) {
        $q->where('entrenador_id', (int) $entrenadorId);
    }

    if ($centro = $request->input('centro')) {
        $q->where('centro', 'like', "%{$centro}%");
    }

    if ($sala = $request->input('sala')) {
        $q->where('sala', 'like', "%{$sala}%");
    }

    $estado = $request->input('estado', 'programada');
    $q->where('estado', $estado);
    $q->orderBy('fecha_inicio')->orderBy('sala');

    $perPage = max(1, min(50, (int) $request->input('per_page', 10)));
    $sesiones = $q->paginate($perPage);

    return view('sesiones.index', compact('sesiones'));
}
}
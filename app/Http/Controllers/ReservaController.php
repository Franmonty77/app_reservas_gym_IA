<?php
namespace App\Http\Controllers;
use App\Models\Reserva;
use App\Models\Sesion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
   public function index()
{
    // Obtener el usuario de la sesión
    $usuario = session('api_user');
    
    if (!$usuario || !isset($usuario['id'])) {
        return redirect()->route('demo.login')->with('error', 'Sesión expirada');
    }
    
    // Obtener las reservas del usuario con JOIN
    $reservas = DB::table('reservas as r')
        ->join('sesiones as s', 'r.sesion_id', '=', 's.id')
        ->join('clases as c', 's.clase_id', '=', 'c.id')
        ->leftJoin('usuarios as u_entrenador', 's.entrenador_id', '=', 'u_entrenador.id')
        ->where('r.usuario_id', $usuario['id']) // ✅ Cambiado: user_id → usuario_id
        ->select(
            'r.*',
            's.fecha_inicio',
            's.fecha_fin',
            'c.nombre as clase_nombre',
            'c.color_hex as clase_color',
            'u_entrenador.nombre as entrenador_nombre'
        )
        ->orderBy('s.fecha_inicio', 'desc')
        ->get();
    
    return view('reservas.index', compact('reservas'));
}

    public function create()
    {
        // Mostrar formulario para crear reserva
        return view('reservas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'actividad' => 'required|string',
        ]);

        // Buscar la clase por actividad
        $clase = \App\Models\Clase::where('nombre', $request->actividad)->first();
        if (!$clase) {
            return back()->withInput()->with('error', 'Actividad no encontrada');
        }

        $token = session('api_token');
        $usuario = session('api_user');
        if (!$token || !$usuario) {
            return redirect()->route('demo.login')->with('error', 'Sesión expirada');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ])->post('http://127.0.0.1:8000/api/reservas', [
                'clase_id' => $clase->id,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
            ]);

            if ($response->successful()) {
                return redirect()->route('reservas.index')->with('success', 'Reserva creada exitosamente');
            } else {
                $error = $response->json()['message'] ?? 'Error desconocido';
                return back()->withInput()->with('error', $error);
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error de conexión');
        }
    }
}
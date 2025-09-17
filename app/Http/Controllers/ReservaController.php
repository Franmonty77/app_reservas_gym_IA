<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Sesion;
use App\Models\Clase;
use Illuminate\Http\Request;
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
            ->where('r.usuario_id', $usuario['id'])
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
        // Obtener todas las clases disponibles
        $clases = Clase::all();
        return view('reservas.create', compact('clases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'actividad' => 'required|string',
        ]);

        $usuario = session('api_user');
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('demo.login')->with('error', 'Sesión expirada');
        }

        // Buscar la clase por actividad
        $clase = Clase::where('nombre', $request->actividad)->first();
        if (!$clase) {
            return back()->withInput()->with('error', 'Actividad no encontrada');
        }

        try {
            // Crear fecha y hora de inicio y fin
            $fechaHoraInicio = $request->fecha . ' ' . $request->hora . ':00';
            $fechaHoraFin = date('Y-m-d H:i:s', strtotime($fechaHoraInicio . ' +1 hour'));

            DB::beginTransaction();

            // Buscar si ya existe una sesión para esa clase, fecha y hora
            $sesion = Sesion::where('clase_id', $clase->id)
                           ->whereDate('fecha_inicio', $request->fecha)
                           ->whereTime('fecha_inicio', $request->hora . ':00')
                           ->first();

            Log::info('Buscando sesión para clase_id: ' . $clase->id . ', fecha: ' . $request->fecha . ', hora: ' . $request->hora);
            if ($sesion) {
                Log::info('Sesión encontrada con ID: ' . $sesion->id);
            } else {
                Log::info('No se encontró sesión existente, creando nueva');
            }

            // Si no existe la sesión, crearla
            if (!$sesion) {
                $sesion = Sesion::create([
                    'clase_id' => $clase->id,
                    'entrenador_id' => 2, // Usa un ID válido de tu tabla usuarios
                    'fecha_inicio' => $fechaHoraInicio,
                    'fecha_fin' => $fechaHoraFin,
                    'aforo_max' => 20, // Campo obligatorio según tu estructura
                    'centro' => 'Centro A', // Ajusta según tu gimnasio
                    'sala' => 'Sala 1', // Ajusta según tu gimnasio
                    'estado' => 'programada',
                    'notas' => null // Opcional
                ]);
            }

            // Verificar si el usuario ya tiene una reserva para esta sesión
            $reservaExistente = Reserva::where('usuario_id', $usuario['id'])
                                     ->where('sesion_id', $sesion->id)
                                     ->first();

            Log::info('Verificando reserva existente para usuario_id: ' . $usuario['id'] . ', sesion_id: ' . $sesion->id);
            
            if ($reservaExistente) {
                Log::info('Reserva existente encontrada: ' . $reservaExistente->id);
                DB::rollBack();
                return back()->withInput()->with('error', 'Ya tienes una reserva para la actividad "' . $clase->nombre . '" el ' . date('d/m/Y', strtotime($request->fecha)) . ' a las ' . $request->hora);
            } else {
                Log::info('No se encontró reserva existente, procediendo a crear');
            }

            // Verificar capacidad de la sesión
            $reservasConfirmadas = Reserva::where('sesion_id', $sesion->id)
                                         ->where('estado', 'confirmada')
                                         ->count();

            if ($reservasConfirmadas >= $sesion->aforo_max) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Esta clase ya está llena');
            }

            // Crear la reserva
            Log::info('Intentando crear reserva con datos:', [
                'usuario_id' => $usuario['id'],
                'sesion_id' => $sesion->id,
                'fecha_reserva' => $request->fecha,
                'hora_reserva' => $request->hora . ':00',
                'estado' => 'confirmada',
                'fuente' => 'web'
            ]);

            Reserva::create([
                'usuario_id' => $usuario['id'],
                'sesion_id' => $sesion->id,
                'fecha_reserva' => $request->fecha,
                'hora_reserva' => $request->hora . ':00', // Asegurar formato HH:MM:SS
                'estado' => 'confirmada',
                'posicion_espera' => null, // Inicialmente null
                'fuente' => 'web'
            ]);

            DB::commit();

            return redirect()->route('reservas.index')->with('success', 'Reserva creada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear reserva: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al crear la reserva: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers;
use App\Models\Clase;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaseController extends Controller
{
    //get/api/clases
    public function index(Request $request)
    {

        $q=Clase::query();

        //Filtros opcionales
        if($request->boolean('solo_activos',true)){
            $q->where('activo',true); // Mostrara la columna que se llama activo
        }

        if($intensidad=$request->query('intensidad')){
            $q->where('intensidad',$intensidad);//Filtra por intensidad (baja, media, alta)
        }

        if($busca=$request->query('q')){
            $q->where(('nombre'),'like',"%$busca%");//Filtra por nombre
        }



        //Paginacion segura
        $perPage=(int) $request->query('per_page',10);
        $perPage=max(1,min(50,$perPage));//Entre 1 y 50

         //Devuelve json con paginacion
        return $q->orderBy('nombre')->paginate($perPage);
        
    }



       // Nuevo método para la vista web
    public function showWeb(Request $request)
{
    // Verificar si el usuario tiene sesión activa
    if (!session('api_token') || !session('api_user')) {
        return redirect()->route('demo.login')
               ->withErrors(['email' => 'Debes iniciar sesión primero']);
    }

    Log::info('Fetching clases from API for web view');

    try {
        // Llamar a tu propia API
        $response = Http::acceptJson()
            ->timeout(10)
            ->withHeaders([
                'Authorization' => 'Bearer ' . session('api_token')
            ])
            ->get(config('services.api.base_url') . '/api/clases');

        Log::info('Clases API Response:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            $apiResponse = $response->json();
            
            // CORRECCIÓN: Extraer solo los datos del array paginado
            $clases = $apiResponse['data'] ?? [];
            $total = $apiResponse['total'] ?? 0;
            
            Log::info('Clases extraídas:', [
                'total_clases' => count($clases),
                'total_api' => $total
            ]);
            
            return view('clases.index', compact('clases', 'total'));
        } else {
            Log::error('Error fetching clases:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return back()->withErrors([
                'error' => 'No se pudieron cargar las clases. Error: ' . $response->status()
            ]);
        }

    } catch (\Exception $e) {
        Log::error('Exception fetching clases:', ['error' => $e->getMessage()]);
        
        return back()->withErrors([
            'error' => 'Error al conectar con la API: ' . $e->getMessage()
        ]);
    }
}

       
    
            
    }

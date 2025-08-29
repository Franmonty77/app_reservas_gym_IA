<?php

namespace App\Http\Controllers;
use App\Models\Clase;

use Illuminate\Http\Request;

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

       
    
            
    }

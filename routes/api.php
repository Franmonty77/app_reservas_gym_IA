<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\SesionController;

/*
|--------------------------------------------------------------------------
| API Routes 
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Ejemplo de get
Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

//Ruta post de register
Route::post('/register',[AuthController::class,'register']);

//Ruta post de login
Route::post('/login',[AuthController::class,'login']);

//Ruta post logout
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');



/********/
//Rutas para las clases

Route::get('/clases',[ClaseController::class,'index']);


Route::get('sesiones',[SesionController::class,'index']);

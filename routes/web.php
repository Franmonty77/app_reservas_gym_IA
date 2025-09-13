<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\ClaseController; 
use App\Http\Controllers\SesionController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Login
Route::get('/', function () {
    return view('welcome');
});


//Ruta para el formulario de login get
Route::get('/demo/login', [DemoController::class, 'loginForm'])->name('demo.login');

//Ruta para el formulario de login post
Route::post('/demo/login', [DemoController::class, 'loginDo'])->name('demo.login.do');


//Ruta para el formulario de registro get
Route::get('/demo/register', [DemoController::class, 'registerForm'])->name('demo.register');


// Ruta para procesar el formulario de registro
Route::post('/demo/register', [DemoController::class, 'register'])->name('demo.register.submit');

// Pantalla de bienvenida (simple)
Route::get('/demo/bienvenida', [DemoController::class, 'bienvenida'])->name('demo.bienvenida');

// Ruta para cerrar sesión
Route::post('/demo/logout', [DemoController::class, 'logout'])->name('demo.logout');

// Cambiar de DemoController a ClaseController
Route::get('/demo/clases', [ClaseController::class, 'showWeb'])->name('clases.web');

// Ruta para ver las sesiones (sesiones web)
Route::get('/sesiones', [SesionController::class, 'index'])->name('sesiones.index');
Route::post('/sesiones/filter', [SesionController::class, 'filter'])->name('sesiones.filter');
Route::get('/sesiones/clear', [SesionController::class, 'clear'])->name('sesiones.clear');

// Rutas para las reservas (web)
Route::middleware('api.auth')->group(function() {
    Route::get('/reservas', [App\Http\Controllers\ReservaController::class, 'index'])->name('reservas.index');
    Route::get('/reservas/crear', [App\Http\Controllers\ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/reservas', [App\Http\Controllers\ReservaController::class, 'store'])->name('reservas.store');
});






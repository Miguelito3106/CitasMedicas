<?php

use App\Http\Controllers\CitasController;
use App\Http\Controllers\MedicosController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\ConsultoriosController;
use App\Http\Controllers\HorariosMedicosController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas públicas de autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para obtener usuario actual
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('listarpacientes', [PacientesController::class, 'index']);    
    Route::post('crearpacientes', [PacientesController::class, 'store']);   
    Route::put('editarpacientes/{id}', [PacientesController::class, 'update']); 
    Route::delete('eliminarpacientes/{id}', [PacientesController::class, 'destroy']); 


    Route::get('listarmedicos', [MedicosController::class, 'index']);     
    Route::post('crearmedicos', [MedicosController::class, 'store']);    
    Route::put('editarmedicos/{id}', [MedicosController::class, 'update']); 
    Route::delete('eliminarmedicos/{id}', [MedicosController::class, 'destroy']);


    Route::get('listarconsultorios', [ConsultoriosController::class, 'index']);     
    Route::post('crearconsultorios', [ConsultoriosController::class, 'store']);    
    Route::put('editarconsultorios/{id}', [ConsultoriosController::class, 'update']);
    Route::delete('eliminarconsultorios/{id}', [ConsultoriosController::class, 'destroy']);


    Route::get('listarhorarios', [HorariosMedicosController::class, 'index']);    
    Route::post('crearhorarios', [HorariosMedicosController::class, 'store']);   
    Route::put('editarhorarios/{id}', [HorariosMedicosController::class, 'update']); 
    Route::delete('eliminarhorarios/{id}', [HorariosMedicosController::class, 'destroy']);


    Route::get('listarcitas', [CitasController::class, 'index']);    
    Route::post('crearcitas', [CitasController::class, 'store']);    
    Route::put('editarcitas/{id}', [CitasController::class, 'update']); 
    Route::delete('eliminarcitas/{id}', [CitasController::class, 'destroy']); 
});
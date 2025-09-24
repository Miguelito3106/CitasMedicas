<?php

use App\Http\Controllers\CitaController;
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
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| API de Usuarios
|--------------------------------------------------------------------------
*/
Route::get('usuarios', [UsuarioController::class, 'index']);     // Listar todos ✅
Route::post('usuarios', [UsuarioController::class, 'store']);    // Crear ✅
Route::get('usuarios/{id}', [UsuarioController::class, 'show']); // Ver uno ✅
Route::put('usuarios/{id}', [UsuarioController::class, 'update']);// Editar ✅
Route::delete('usuarios/{id}', [UsuarioController::class, 'destroy']); // Eliminar ✅

/*
|--------------------------------------------------------------------------
| API de Pacientes
|--------------------------------------------------------------------------
*/
Route::get('pacientes', [PacienteController::class, 'index']);     
Route::post('pacientes', [PacienteController::class, 'store']);    
Route::get('pacientes/{id}', [PacienteController::class, 'show']); 
Route::put('pacientes/{id}', [PacienteController::class, 'update']);
Route::delete('pacientes/{id}', [PacienteController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| API de Especialidades
|--------------------------------------------------------------------------
*/
Route::get('especialidades', [EspecialidadController::class, 'index']);     
Route::post('especialidades', [EspecialidadController::class, 'store']);    
Route::get('especialidades/{id}', [EspecialidadController::class, 'show']); 
Route::put('especialidades/{id}', [EspecialidadController::class, 'update']);
Route::delete('especialidades/{id}', [EspecialidadController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| API de Médicos
|--------------------------------------------------------------------------
*/
Route::get('medicos', [MedicoController::class, 'index']);     
Route::post('medicos', [MedicoController::class, 'store']);    
Route::get('medicos/{id}', [MedicoController::class, 'show']); 
Route::put('medicos/{id}', [MedicoController::class, 'update']);
Route::delete('medicos/{id}', [MedicoController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| API de Citas
|--------------------------------------------------------------------------
*/
Route::get('citas', [CitaController::class, 'index']);     
Route::post('citas', [CitaController::class, 'store']);    
Route::get('citas/{id}', [CitaController::class, 'show']); 
Route::put('citas/{id}', [CitaController::class, 'update']);
Route::delete('citas/{id}', [CitaController::class, 'destroy']);

});
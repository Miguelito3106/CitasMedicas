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
  Route::get('/me', [AuthController::class, 'me']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para obtener usuario actual
    Route::get('/user', function (Request $request) {
        return $request->user()->load(['paciente', 'medico']);
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // ==================== RUTAS DE ADMINISTRADOR ====================
    Route::middleware('checkRole:admin')->group(function () {
        // Gestión de usuarios
        Route::get('/usuarios', [AuthController::class, 'indexUsuarios']);
        Route::put('/usuarios/{id}', [AuthController::class, 'actualizarUsuario']);
        Route::delete('/usuarios/{id}', [AuthController::class, 'eliminarUsuario']);
        
        // Gestión completa
        Route::post('crearmedicos', [MedicosController::class, 'store']);
        Route::put('editarmedicos/{id}', [MedicosController::class, 'update']);
        Route::delete('eliminarmedicos/{id}', [MedicosController::class, 'destroy']);
        
        Route::post('crearconsultorios', [ConsultoriosController::class, 'store']);
        Route::put('editarconsultorios/{id}', [ConsultoriosController::class, 'update']);
        Route::delete('eliminarconsultorios/{id}', [ConsultoriosController::class, 'destroy']);
        
        Route::post('crearhorarios', [HorariosMedicosController::class, 'store']);
        Route::put('editarhorarios/{id}', [HorariosMedicosController::class, 'update']);
        Route::delete('eliminarhorarios/{id}', [HorariosMedicosController::class, 'destroy']);
        
        Route::post('crearpacientes', [PacientesController::class, 'store']);
        Route::put('editarpacientes/{id}', [PacientesController::class, 'update']);
        Route::delete('eliminarpacientes/{id}', [PacientesController::class, 'destroy']);
        
        Route::post('crearcitas', [CitasController::class, 'store']);
        Route::put('editarcitas/{id}', [CitasController::class, 'update']);
        Route::delete('eliminarcitas/{id}', [CitasController::class, 'destroy']);
    });

    // ==================== RUTAS DE DOCTOR ====================
    Route::middleware('checkRole:doctor,admin')->group(function () {
        Route::get('mis-citas', [CitasController::class, 'citasPorMedico']);
        Route::put('atender-cita/{id}', [CitasController::class, 'update']);
    });

    // ==================== RUTAS DE PACIENTE ====================
    Route::middleware('checkRole:paciente,admin')->group(function () {
        Route::get('mis-citas-paciente', [CitasController::class, 'citasPorPaciente']);
        Route::post('solicitar-cita', [CitasController::class, 'store']);
        Route::put('modificar-mi-cita/{id}', [CitasController::class, 'update']);
        Route::delete('cancelar-mi-cita/{id}', [CitasController::class, 'destroy']);
    });

    // ==================== RUTAS PÚBLICAS AUTENTICADAS ====================
    
    // Listados generales (todos pueden ver)
    Route::get('listarpacientes', [PacientesController::class, 'index']);
    Route::get('listarcitas', [CitasController::class, 'index']);
    Route::get('listarmedicos', [MedicosController::class, 'index']);
    Route::get('listarconsultorios', [ConsultoriosController::class, 'index']);
    Route::get('listarhorarios', [HorariosMedicosController::class, 'index']);

    // Ver detalles
    Route::get('pacientes/{id}', [PacientesController::class, 'show']);
    Route::get('medicos/{id}', [MedicosController::class, 'show']);
    Route::get('consultorios/{id}', [ConsultoriosController::class, 'show']);
    Route::get('citas/{id}', [CitasController::class, 'show']);
    Route::get('horarios/{id}', [HorariosMedicosController::class, 'show']);
});
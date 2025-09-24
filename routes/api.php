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

    // Rutas para citas
    Route::apiResource('citas', CitaController::class);
    Route::get('citas/medico/{medicoId}', [CitaController::class, 'citasPorMedico']);
    Route::get('citas/paciente/{pacienteId}', [CitaController::class, 'citasPorPaciente']);
    Route::put('citas/{id}/estado', [CitaController::class, 'cambiarEstado']);

    // Rutas para médicos
    Route::apiResource('medicos', MedicosController::class);
    Route::get('medicos/{id}/horarios', [MedicosController::class, 'horarios']);
    Route::get('medicos/{id}/consultorios', [MedicosController::class, 'consultorios']);
    Route::get('medicos/{id}/citas', [MedicosController::class, 'citas']);

    // Rutas para pacientes
    Route::apiResource('listarpacientes', PacientesController::class)->only(['index', 'show']);
    Route::apiResource('pacientes', PacientesController::class);
    Route::get('pacientes/{id}/citas', [PacientesController::class, 'citas']);

    // Rutas para consultorios
    Route::apiResource('consultorios', ConsultoriosController::class);
    Route::get('consultorios/disponibles', [ConsultoriosController::class, 'disponibles']);

    // Rutas para horarios médicos
    Route::apiResource('horarios-medicos', HorariosMedicosController::class);
    Route::get('horarios-medicos/medico/{medicoId}', [HorariosMedicosController::class, 'porMedico']);
});

// Rutas públicas (sin autenticación)
Route::get('medicos', [MedicosController::class, 'index']);
Route::get('medicos/{id}', [MedicosController::class, 'show']);
Route::get('medicos/{id}/horarios-disponibles', [MedicosController::class, 'horariosDisponibles']);

// Health check
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API EPS Clínica funcionando correctamente',
        'timestamp' => now()->toDateTimeString()
    ]);
});
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\ConsultoriosController;
use App\Http\Controllers\MedicosController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\HorariosMedicosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('registrar', [AuthController::class, 'register']); 

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('perfil', [AuthController::class, 'me']);

    Route::middleware('role:admin,recepcionista')->group(function () {
        Route::apiResource('citas', CitasController::class)->except(['destroy']);
        Route::apiResource('pacientes', PacientesController::class);
        
        Route::get('medicos', [MedicosController::class, 'index']);
        Route::get('medicos/{id}', [MedicosController::class, 'show']);
        
        Route::get('horarios-medicos', [HorariosMedicosController::class, 'index']);
        Route::get('horarios-medicos/{id}', [HorariosMedicosController::class, 'show']);
        
        Route::get('consultorios', [ConsultoriosController::class, 'index']);
        Route::get('consultorios/{id}', [ConsultoriosController::class, 'show']);
        Route::get('consultorios/por-medico/{medicoId}', [ConsultoriosController::class, 'porMedico']);
    });

    Route::middleware('role:medico')->group(function () {
        Route::get('mis-citas', [CitasController::class, 'misCitas']);
        Route::put('citas/{id}/estado', [CitasController::class, 'actualizarEstado']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('medicos', MedicosController::class)->except(['index', 'show']);
        Route::apiResource('horarios-medicos', HorariosMedicosController::class)->except(['index', 'show']);
        Route::apiResource('consultorios', ConsultoriosController::class)->except(['index', 'show']);
        Route::delete('citas/{id}', [CitasController::class, 'destroy']);
        
        Route::get('usuarios', [AuthController::class, 'indexUsuarios']);
        Route::put('usuarios/{id}', [AuthController::class, 'actualizarUsuario']);
        Route::delete('usuarios/{id}', [AuthController::class, 'eliminarUsuario']);
    });
});
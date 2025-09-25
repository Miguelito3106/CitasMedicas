<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Citas;
use App\Models\Medico;
use App\Models\Pacientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitasController extends Controller
{
    public function index()
    {
        $citas = Citas::with(['paciente', 'medico'])
                    ->orderBy('fecha_cita', 'desc')
                    ->orderBy('hora_cita', 'desc')
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $citas
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idPaciente' => 'required|exists:pacientes,id',
            'idMedico' => 'required|exists:medicos,id',
            'fecha_cita' => 'required|date|after_or_equal:today',
            'hora_cita' => 'required|date_format:H:i',
            'motivo' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar disponibilidad del médico
        $citaExistente = Citas::where('idMedico', $request->idMedico)
                            ->where('fecha_cita', $request->fecha_cita)
                            ->where('hora_cita', $request->hora_cita)
                            ->where('estado', '!=', 'cancelada')
                            ->exists();

        if ($citaExistente) {
            return response()->json([
                'success' => false,
                'message' => 'El médico ya tiene una cita programada para esa fecha y hora'
            ], 409);
        }

        $cita = Citas::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cita creada exitosamente',
            'data' => $cita->load(['paciente', 'medico'])
        ], 201);
    }

    public function show($id)
    {
        $cita = Citas::with(['paciente', 'medico'])->find($id);

        if (!$cita) {
            return response()->json([
                'success' => false,
                'message' => 'Cita no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $cita
        ]);
    }

    public function update(Request $request, $id)
    {
        $cita = Citas::find($id);

        if (!$cita) {
            return response()->json([
                'success' => false,
                'message' => 'Cita no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'estado' => 'sometimes|in:pendiente,confirmada,cancelada,atendida',
            'motivo' => 'sometimes|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $cita->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cita actualizada exitosamente',
            'data' => $cita->load(['paciente', 'medico'])
        ]);
    }

    public function destroy($id)
    {
        $cita = Citas::find($id);

        if (!$cita) {
            return response()->json([
                'success' => false,
                'message' => 'Cita no encontrada'
            ], 404);
        }

        $cita->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cita eliminada exitosamente'
        ]);
    }

    public function citasPorMedico($medicoId)
    {
        $citas = Citas::with('paciente')
                    ->where('idMedico', $medicoId)
                    ->where('fecha_cita', '>=', today())
                    ->orderBy('fecha_cita')
                    ->orderBy('hora_cita')
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $citas
        ]);
    }

    public function citasPorPaciente($pacienteId)
    {
        $citas = Citas::with('medico')
                    ->where('idPaciente', $pacienteId)
                    ->orderBy('fecha_cita', 'desc')
                    ->orderBy('hora_cita', 'desc')
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $citas
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Citas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitasController extends Controller
{
    public function index(){
        $citas = Citas::with(['medico', 'paciente'])->get();
        return response()->json($citas);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idMedico' => 'required|exists:medicos,id',
            'idPaciente' => 'required|exists:pacientes,id',
            'fecha_cita' => 'required|date',
            'hora_cita' => 'required',
            'estado' => 'required|in:pendiente,confirmada,cancelada,atendida',
            'motivo' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $cita = Citas::create($validator->validated()); 
        return response()->json($cita, 201);
    }

    public function show(string $id)
    {
        $cita = Citas::with(['medico', 'paciente'])->find($id);
        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }
        return response()->json($cita);
    }

    public function update(Request $request, string $id)
    {
        $cita = Citas::find($id);
        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'idMedico' => 'sometimes|exists:medicos,id',
            'idPaciente' => 'sometimes|exists:pacientes,id',
            'fecha_cita' => 'sometimes|date',
            'hora_cita' => 'sometimes',
            'estado' => 'sometimes|in:pendiente,confirmada,cancelada,atendida',
            'motivo' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cita->update($validator->validated());
        return response()->json($cita);
    }

    public function destroy(string $id)
    {
        $cita = Citas::find($id);
        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }
        $cita->delete();
        return response()->json(['message' => 'Cita eliminada correctamente']);
    }
}
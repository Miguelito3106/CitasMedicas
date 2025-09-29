<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Citas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitasController extends Controller
{
    public function index()
    {
        $citas = Citas::with(['paciente', 'medico'])->get();
        return response()->json($citas);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medico_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'hora' => 'required|string',
            'motivo' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $cita = Citas::create([
            'paciente_id' => $request->user()->id,
            'medico_id' => $request->medico_id,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'motivo' => $request->motivo,
            'estado' => 'pendiente',
        ]);

        return response()->json([
            'message' => 'Cita creada exitosamente',
            'cita' => $cita->load('medico')
        ], 201);
    }

    public function citasPaciente(Request $request)
    {
        $citas = Citas::where('paciente_id', $request->user()->id)
                    ->with('medico')
                    ->get();
        
        return response()->json($citas);
    }

    public function citasMedico(Request $request)
    {
        $citas = Citas::where('medico_id', $request->user()->id)
                    ->with('paciente')
                    ->get();
        
        return response()->json($citas);
    }

    public function actualizarEstado(Request $request, $id)
    {
        $cita = Citas::where('medico_id', $request->user()->id)
                    ->where('id', $id)
                    ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:confirmada,cancelada,completada',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $cita->update(['estado' => $request->estado]);

        return response()->json([
            'message' => 'Estado de cita actualizado',
            'cita' => $cita
        ]);
    }
}
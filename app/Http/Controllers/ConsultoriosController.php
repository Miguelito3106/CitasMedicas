<?php

namespace App\Http\Controllers;

use App\Models\Consultorios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsultoriosController extends Controller
{
    public function index()
    {
        $consultorios = Consultorios::with('medico')->get();
        return response()->json($consultorios);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'BloqueConsultorio' => 'required|string|max:255',
            'NumeroConsultorio' => 'required|string|max:255|unique:consultorios',
            'idMedico' => 'required|exists:medicos,id', // QUITAR unique:consultorios,idMedico
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $consultorio = Consultorios::create($validator->validated());
        return response()->json($consultorio->load('medico'), 201);
    }

    public function show(string $id)
    {
        $consultorio = Consultorios::with('medico')->find($id);
        if (!$consultorio) {
            return response()->json(['message' => 'Consultorio no encontrado'], 404);
        }
        return response()->json($consultorio);
    }

    public function update(Request $request, string $id)
    {
        $consultorio = Consultorios::find($id);
        if (!$consultorio) {
            return response()->json(['message' => 'Consultorio no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'BloqueConsultorio' => 'sometimes|string|max:255',
            'NumeroConsultorio' => 'sometimes|string|max:255|unique:consultorios,NumeroConsultorio,' . $id,
            'idMedico' => 'sometimes|exists:medicos,id', // QUITAR unique:consultorios,idMedico,' . $id
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $consultorio->update($validator->validated());
        return response()->json($consultorio->load('medico'));
    }

    public function destroy(string $id)
    {
        $consultorio = Consultorios::find($id);
        if (!$consultorio) {
            return response()->json(['message' => 'Consultorio no encontrado'], 404);
        }
        
        $consultorio->delete();
        return response()->json(['message' => 'Consultorio eliminado correctamente']);
    }

    // Método adicional para buscar consultorio por médico
    public function porMedico($idMedico)
    {
        $consultorio = Consultorios::with('medico')
            ->where('idMedico', $idMedico)
            ->first();
        
        if (!$consultorio) {
            return response()->json(['message' => 'Consultorio no encontrado para este médico'], 404);
        }
        
        return response()->json($consultorio);
    }
}
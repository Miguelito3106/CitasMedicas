<?php

namespace App\Http\Controllers;

use App\Models\Medicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicosController extends Controller
{
    public function index()
    {
        $Medicos = Medicos::all();
        return response()->json($Medicos);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'documento' => 'required|string|max:255|unique:medicos,documento',
            'telefono' => 'required|string|max:15',
            'email' => 'required|email|max:255|unique:medicos,email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $medico = Medicos::create($validator->validated());
        return response()->json($medico, 201);
    }

    public function show(string $id)
    {
        $medico = Medicos::find($id);
        if (!$medico) {
            return response()->json(['message' => 'Medico no encontrado'], 404);
        }
        return response()->json($medico);
    }

    public function update(Request $request, string $id)
    {
        $medico = Medicos::find($id);
        if (!$medico) {
            return response()->json(['message' => 'Medico no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'string|max:255',
            'apellido' => 'string|max:255',
            'documento' => 'string|max:255|unique:medicos,documento,' . $id,
            'telefono' => 'string|max:15',
            'email' => 'email|max:255|unique:medicos,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $medico->update($validator->validated());
        return response()->json($medico);
    }

    public function destroy(string $id)
    {
        $medico = Medicos::find($id);
        if (!$medico) {
            return response()->json(['message' => 'Medico no encontrado'], 404);
        }

        $medico->delete();
        return response()->json(['message' => 'Medico eliminado correctamente']);
    }
}
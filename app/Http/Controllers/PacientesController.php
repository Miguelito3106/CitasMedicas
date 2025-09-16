<?php

namespace App\Http\Controllers;

use App\Models\Pacientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PacientesController extends Controller
{
    public function index(){
        $pacientes = Pacientes::all();
        return response()->json($pacientes);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'documento' => 'required|string|max:255|unique:pacientes',
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'required|in:M,F',
            'telefono' => 'required|string|max:20',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $paciente = Pacientes::create($validator->validated());
        return response()->json($paciente, 201);
    }

    public function show(string $id){
        $paciente = Pacientes::find($id);
        if(!$paciente){
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }
        return response()->json($paciente);
    }

    public function update(Request $request, string $id){
        $paciente = Pacientes::find($id);
        if(!$paciente){
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }

        $validator = Validator::make($request->all(),[
            'nombre' => 'sometimes|string|max:255',
            'apellido' => 'sometimes|string|max:255',
            'documento' => 'sometimes|string|max:255|unique:pacientes,documento,' . $id,
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'sometimes|in:M,F',
            'telefono' => 'sometimes|string|max:20',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $paciente->update($validator->validated());
        return response()->json($paciente);
    }

    public function destroy(string $id){
        $paciente = Pacientes::find($id);
        if(!$paciente){
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }
        
        $paciente->delete();
        return response()->json(['message' => 'Paciente eliminado correctamente']);
    }
}
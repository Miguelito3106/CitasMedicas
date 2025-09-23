<?php

namespace App\Http\Controllers;

use App\Models\Medicos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MedicosController extends Controller
{
    public function index()
    {
        $medicos = Medicos::with('user')->get(); // MODIFICADO
        return response()->json($medicos);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'documento' => 'required|string|max:255|unique:medicos,documento',
            'telefono' => 'required|string|max:15',
            'email' => 'required|email|max:255|unique:medicos,email',
            'user_email' => 'required|email|unique:users,email', // AÑADIDO
            'user_password' => 'required|string|min:6', // AÑADIDO
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Crear usuario para el médico
        $user = User::create([
            'name' => $request->nombre . ' ' . $request->apellido,
            'email' => $request->user_email,
            'password' => Hash::make($request->user_password),
            'role' => 'medico',
        ]);

        // Crear médico asociado al usuario
        $medicoData = $validator->validated();
        $medicoData['user_id'] = $user->id;
        unset($medicoData['user_email']);
        unset($medicoData['user_password']);

        $medico = Medicos::create($medicoData);
        return response()->json($medico->load('user'), 201);
    }

    public function show(string $id)
    {
        $medico = Medicos::with('user')->find($id); // MODIFICADO
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
            'nombre' => 'sometimes|string|max:255',
            'apellido' => 'sometimes|string|max:255',
            'documento' => 'sometimes|string|max:255|unique:medicos,documento,' . $id,
            'telefono' => 'sometimes|string|max:15',
            'email' => 'sometimes|email|max:255|unique:medicos,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $medico->update($validator->validated());
        return response()->json($medico->load('user')); // MODIFICADO
    }

    public function destroy(string $id)
    {
        $medico = Medicos::with('user')->find($id); // MODIFICADO
        if (!$medico) {
            return response()->json(['message' => 'Medico no encontrado'], 404);
        }

        // Eliminar usuario asociado
        if ($medico->user) {
            $medico->user->delete();
        }

        $medico->delete();
        return response()->json(['message' => 'Medico eliminado correctamente']);
    }
}
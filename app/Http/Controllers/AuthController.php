<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pacientes;
use App\Models\Medicos;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:paciente,doctor,admin',
            // Campos específicos para paciente
            'documento' => 'required_if:role,paciente|string|unique:pacientes,documento',
            'fecha_nacimiento' => 'required_if:role,paciente|date',
            'telefono' => 'required_if:role,paciente|string|max:20',
            'genero' => 'required_if:role,paciente|in:M,F',
            'direccion' => 'required_if:role,paciente|string',
            // Campos específicos para doctor
            'licencia_medica' => 'required_if:role,doctor|string|unique:medicos,licencia_medica',
            'especialidad' => 'required_if:role,doctor|string',
            'documento_medico' => 'required_if:role,doctor|string|unique:medicos,documento',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Determinar el rol (si es admin, verificar permisos)
        $role = $request->role;
        if ($role === 'admin') {
            // Solo usuarios autenticados pueden crear admins
            if (!$request->user() || !$request->user()->isAdmin()) {
                return response()->json([
                    'message' => 'No autorizado para crear usuarios administrador'
                ], 403);
            }
        }

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        // Crear datos específicos según el rol
        if ($role === 'paciente') {
            Pacientes::create([
                'user_id' => $user->id,
                'nombre' => $request->name,
                'apellido' => '',
                'documento' => $request->documento,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'telefono' => $request->telefono,
                'genero' => $request->genero,
                'direccion' => $request->direccion,
            ]);
        } elseif ($role === 'doctor') {
            Medicos::create([
                'user_id' => $user->id,
                'nombre' => $request->name,
                'apellido' => '',
                'documento' => $request->documento_medico,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'licencia_medica' => $request->licencia_medica,
                'especialidad' => $request->especialidad,
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user' => $user->load(['paciente', 'medico']),
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => $user->load(['paciente', 'medico']),
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load(['paciente', 'medico'])
        ]);
    }

    public function indexUsuarios()
    {
        // Solo administradores pueden ver todos los usuarios
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $usuarios = User::with(['paciente', 'medico'])->get();
        return response()->json($usuarios);
    }

    public function actualizarUsuario(Request $request, $id)
    {
        // Solo administradores pueden actualizar usuarios
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $usuario = User::find($id);
        
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|unique:users,email,' . $id,
            'role' => 'sometimes|in:paciente,doctor,admin'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $usuario->update($validator->validated());
        return response()->json($usuario->load(['paciente', 'medico']));
    }

    public function eliminarUsuario($id)
    {
        // Solo administradores pueden eliminar usuarios
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $usuario = User::find($id);
        
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        if ($usuario->id === Auth::user()->id) {
            return response()->json(['message' => 'No puedes eliminarte a ti mismo'], 403);
        }

        $usuario->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
}
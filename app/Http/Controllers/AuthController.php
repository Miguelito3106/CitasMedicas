<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,medico,paciente',
            'especialidad' => 'required_if:role,medico',
            'telefono' => 'nullable|string|max:15',
            'fecha_nacimiento' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];

        if ($request->filled('telefono')) {
            $userData['telefono'] = $request->telefono;
        }

        if ($request->filled('fecha_nacimiento')) {
            $userData['fecha_nacimiento'] = $request->fecha_nacimiento;
        }

        if ($request->role === 'medico' && $request->filled('especialidad')) {
            $userData['especialidad'] = $request->especialidad;
        }

        $user = User::create($userData);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        // ESTRUCTURA CORREGIDA - Incluye 'user' en el nivel raíz
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'especialidad' => $user->especialidad,
                'telefono' => $user->telefono,
                'fecha_nacimiento' => $user->fecha_nacimiento
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Sesión cerrada exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error en logout: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al cerrar sesión'
            ], 500);
        }
    }

    /**
     * Obtener el usuario actualmente autenticado
     */
    public function me(Request $request)
    {
        try {
            Log::info('Solicitud a /me recibida', [
                'user_id' => $request->user() ? $request->user()->id : 'null',
                'ip' => $request->ip()
            ]);

            $user = $request->user();
            
            if (!$user) {
                Log::warning('Intento de acceso a /me sin autenticación');
                return response()->json([
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            Log::info('Usuario autenticado encontrado', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role
            ]);

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'especialidad' => $user->especialidad,
                    'telefono' => $user->telefono,
                    'fecha_nacimiento' => $user->fecha_nacimiento,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error en endpoint /me: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'message' => 'Error interno del servidor',
                'error' => config('app.debug') ? $e->getMessage() : 'Contacte al administrador'
            ], 500);
        }
    }

    public function userProfile(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            return response()->json([
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error en userProfile: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function checkAuth(Request $request)
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'user' => $request->user()
        ]);
    }

    /**
     * Métodos de administración de usuarios (para admin)
     */
    public function indexUsuarios(Request $request)
    {
        try {
            // Verificar que el usuario sea admin
            if (!$request->user() || $request->user()->role !== 'admin') {
                return response()->json([
                    'message' => 'No autorizado'
                ], 403);
            }

            $usuarios = User::all();
            
            return response()->json([
                'usuarios' => $usuarios
            ]);
        } catch (\Exception $e) {
            Log::error('Error en indexUsuarios: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function actualizarUsuario(Request $request, $id)
    {
        try {
            // Verificar que el usuario sea admin
            if (!$request->user() || $request->user()->role !== 'admin') {
                return response()->json([
                    'message' => 'No autorizado'
                ], 403);
            }

            $usuario = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
                'role' => 'sometimes|in:admin,medico,paciente',
                'especialidad' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:15',
                'fecha_nacimiento' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            $usuario->update($request->all());

            return response()->json([
                'message' => 'Usuario actualizado exitosamente',
                'usuario' => $usuario
            ]);
        } catch (\Exception $e) {
            Log::error('Error en actualizarUsuario: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function eliminarUsuario(Request $request, $id)
    {
        try {
            // Verificar que el usuario sea admin
            if (!$request->user() || $request->user()->role !== 'admin') {
                return response()->json([
                    'message' => 'No autorizado'
                ], 403);
            }

            $usuario = User::findOrFail($id);
            
            // No permitir eliminar el propio usuario
            if ($usuario->id === $request->user()->id) {
                return response()->json([
                    'message' => 'No puedes eliminar tu propio usuario'
                ], 422);
            }

            $usuario->delete();

            return response()->json([
                'message' => 'Usuario eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error en eliminarUsuario: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
}
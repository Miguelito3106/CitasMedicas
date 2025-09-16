<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'sometimes|in:admin,medico,recepcionista'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Solo los administradores pueden crear usuarios con roles específicos
        $role = 'recepcionista'; // valor por defecto
        if ($request->user() && $request->user()->isAdmin() && $request->has('role')) {
            $role = $request->role;
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $role,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email',
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
            'user'    => $user,
            'token'   => $token,
        ], 200);
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    /**
     * Obtener usuario autenticado
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    /**
     * Obtener todos los usuarios (solo admin)
     */
    public function indexUsuarios()
    {
        $usuarios = User::all();
        return response()->json($usuarios);
    }

    /**
     * Actualizar usuario (solo admin)
     */
    public function actualizarUsuario(Request $request, $id)
    {
        $usuario = User::find($id);
        
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|unique:users,email,' . $id,
            'role' => 'sometimes|in:admin,medico,recepcionista'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $usuario->update($validator->validated());
        return response()->json($usuario);
    }

    /**
     * Eliminar usuario (solo admin)
     */
    public function eliminarUsuario($id)
    {
        $usuario = User::find($id);
        
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // No permitir auto-eliminación
        if ($usuario->id === auth()->id) {
            return response()->json(['message' => 'No puedes eliminarte a ti mismo'], 403);
        }

        $usuario->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
}
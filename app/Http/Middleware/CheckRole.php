<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar si el usuario está autenticado
        if (!$request->user()) {
            return response()->json([
                'message' => 'No autenticado. Por favor inicie sesión.'
            ], 401);
        }

        $user = $request->user();
        
        // Verificar si el usuario tiene alguno de los roles permitidos
        if (!in_array($user->role, $roles)) {
            $rolesTexto = implode(', ', $roles);
            return response()->json([
                'message' => 'No autorizado para esta acción.',
                'detail' => "Su rol actual es: {$user->role}. Roles permitidos: {$rolesTexto}",
                'user_role' => $user->role,
                'required_roles' => $roles
            ], 403);
        }
        
        return $next($request);
    }
}
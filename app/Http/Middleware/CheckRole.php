<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        if (!in_array($request->user()->role, $roles)) {
            return response()->json(['error' => 'No autorizado para este rol'], 403);
        }

        return $next($request);
    }
}
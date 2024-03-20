<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //$user=auth()->user();
        $user = $request->user();
        
        

        // Verificar si el usuario tiene el rol de administrador
        if ($user && $user->roles()->where('role_name', 'admin')->exists()) {
            return $next($request);
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}

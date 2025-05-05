<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Lógica de autorización
        if (auth()->user()->role_id != $role) {
            return redirect('/');
        }

        return $next($request);
    }
}

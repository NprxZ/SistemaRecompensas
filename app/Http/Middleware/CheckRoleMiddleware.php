<?php
// ============================================

// app/Http/Middleware/CheckRoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Session::has('user_role') || Session::get('user_role') !== $role) {
            return redirect()->route('admin.dashboard')->with('error', 'No tienes permisos para acceder a esta página.');
        }

        return $next($request);
    }
}
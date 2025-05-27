<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StagiaireMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Autorise aussi les Admins et Encadrants car ils ont des privilèges supérieurs ou liés
        if (!Auth::check() || !(Auth::user()->hasRole("Stagiaire") || Auth::user()->hasRole("Encadrant") || Auth::user()->hasRole("Admin"))) {
            abort(403, "Accès non autorisé.");
        }
        return $next($request);
    }
}


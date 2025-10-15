<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role = null): Response
    {
        $user = Auth::user();

        // If no specific role is required, just check if user is authenticated
        if ($role === null) {
            if (!$user) {
                abort(403, 'У вас нет прав для выполнения этого действия');
            }
            return $next($request);
        }

        // If specific role is required, check it
        if (!$user || !$user->role || $user->role->value !== $role) {
            abort(403, 'У вас нет прав для выполнения этого действия');
        }

        return $next($request);
    }
}
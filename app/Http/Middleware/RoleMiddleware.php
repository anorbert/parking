<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Check if user has one of the allowed roles.
     * Usage: middleware('role:1,2') for Super Admin and Company Admin.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $allowedRoles = collect($roles)->map(fn($r) => (int) $r);

        if (!$allowedRoles->contains($user->role_id)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}

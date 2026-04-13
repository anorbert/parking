<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyScope
{
    /**
     * Ensure all queries are scoped to the authenticated user's company.
     * Super Admin (role_id=1, company_id=null) bypasses this.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super Admin bypasses company scope
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Non-super users must belong to a company
        if (!$user->company_id) {
            abort(403, 'You are not assigned to any company.');
        }

        return $next($request);
    }
}

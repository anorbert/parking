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



        // Allow company admins (role_id == 2) to access dashboard and company creation routes even if not assigned to a company
        $route = $request->route();
        $isAdminDashboard = $route && $route->getName() === 'admin.dashboard';
        $isCompanyCreate = $route && (
            $route->getName() === 'superadmin.companies.create' ||
            $route->getName() === 'superadmin.companies.store'
        );

        if (!$user->company_id && !$user->isCompanyAdmin()) {
            abort(403, 'You are not assigned to any company.');
        }

        // If company admin and not assigned to a company, allow dashboard and company creation
        if ($user->isCompanyAdmin() && !$user->company_id && ($isAdminDashboard || $isCompanyCreate)) {
            return $next($request);
        }

        return $next($request);
    }
}

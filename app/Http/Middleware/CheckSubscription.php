<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Block actions if the company's subscription has expired.
     * Super Admin bypasses this check.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super Admin bypasses
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
        if (!$user->company_id && $user->role_id == 2 && ($isAdminDashboard || $isCompanyCreate)) {
            return $next($request);
        }

        if (!$user->company_id) {
            abort(403, 'No company assigned.');
        }

        $company = $user->company;

        if (!$company || !$company->hasActiveSubscription()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your company subscription has expired. Please renew to continue.',
                ], 403);
            }

            return redirect()->route('subscription.expired')
                ->with('error', 'Your company subscription has expired. Please renew to continue.');
        }

        return $next($request);
    }
}

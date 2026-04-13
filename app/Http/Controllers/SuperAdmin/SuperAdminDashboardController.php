<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Parking;
use App\Models\Subscription;
use App\Models\User;
use App\Models\PaymentHistory;
use Carbon\Carbon;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $totalCompanies = Company::count();
        $activeCompanies = Company::where('status', 'Active')->count();
        $totalUsers = User::count();
        $totalRevenue = Parking::sum('bill');

        $activeSubscriptions = Subscription::where('status', 'Active')
            ->where('end_date', '>=', now())
            ->count();

        $expiredSubscriptions = Subscription::where(function ($q) {
            $q->where('status', 'Expired')
                ->orWhere('end_date', '<', now());
        })->count();

        $subscriptionRevenue = Subscription::where('status', 'Active')->sum('amount');

        // Revenue per company (top 10)
        $revenueByCompany = Company::select('companies.id', 'companies.name')
            ->selectSub(
                Parking::selectRaw('COALESCE(SUM(bill), 0)')
                    ->whereColumn('parkings.company_id', 'companies.id'),
                'total_revenue'
            )
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Monthly subscription revenue (last 6 months)
        $monthlySubRevenue = Subscription::where('status', 'Active')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(paid_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = $monthlySubRevenue->map(fn($item) => date('F', mktime(0, 0, 0, $item->month, 1)));
        $subRevenues = $monthlySubRevenue->pluck('total');

        // Recent companies
        $recentCompanies = Company::with('activeSubscription')
            ->latest()
            ->limit(5)
            ->get();

        return view('superadmin.dashboard', compact(
            'totalCompanies',
            'activeCompanies',
            'totalUsers',
            'totalRevenue',
            'activeSubscriptions',
            'expiredSubscriptions',
            'subscriptionRevenue',
            'revenueByCompany',
            'months',
            'subRevenues',
            'recentCompanies'
        ));
    }
}

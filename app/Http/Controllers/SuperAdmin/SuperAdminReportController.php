<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Parking;
use App\Models\Subscription;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Parking::query();

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $filtered = $query->get();

        // Revenue per company (also respect filters)
        $revenueQuery = Parking::with('company')
            ->selectRaw('company_id, SUM(bill) as total_revenue, COUNT(*) as total_parkings')
            ->groupBy('company_id');

        if ($request->filled('company_id')) {
            $revenueQuery->where('company_id', $request->company_id);
        }
        if ($request->filled('start_date')) {
            $revenueQuery->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $revenueQuery->whereDate('created_at', '<=', $request->end_date);
        }

        $revenueByCompany = $revenueQuery->get();

        // Daily revenue
        $dailyRevenue = Parking::whereDate('created_at', today())->sum('bill');

        // Totals from filtered data
        $totalRevenue = $filtered->sum('bill');
        $totalParkings = $filtered->count();

        // Payment methods
        $cashTotal = $filtered->where('payment_method', 'cash')->sum('bill');
        $momoTotal = $filtered->where('payment_method', 'momo')->sum('bill');

        // Active parked
        $activeParked = Parking::where('status', 'active')->whereNull('exit_time')->count();

        // Expired subscriptions
        $expiredSubs = Subscription::where('end_date', '<', now())
            ->orWhere('status', 'Expired')
            ->with('company')
            ->latest()
            ->get();

        // Monthly revenue trend (last 6 months)
        $monthlyRevenue = Parking::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(bill) as revenue, COUNT(*) as parkings")
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $companies = Company::orderBy('name')->get();

        return view('superadmin.reports.index', compact(
            'revenueByCompany',
            'dailyRevenue',
            'totalRevenue',
            'totalParkings',
            'cashTotal',
            'momoTotal',
            'activeParked',
            'expiredSubs',
            'companies',
            'monthlyRevenue'
        ));
    }
}

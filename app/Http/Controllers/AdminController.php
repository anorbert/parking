<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slot;
use App\Models\Zone;
use App\Models\Parking;
use App\Models\User;
use App\Models\PaymentHistory as Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // If user is company admin and not assigned to a company, redirect to their own company creation page
        if ($user->isCompanyAdmin() && !$user->company_id) {
            return redirect()->route('admin.companies.create')
                ->with('error', 'You must register your company before accessing the dashboard.');
        }

        $companyId = $user->company_id;

        // Total slots (company's zones only)
        $totalSlots = Slot::whereHas('zone', fn($q) => $q->where('company_id', $companyId))->count();

        // Occupied slots
        $occupiedSlots = Slot::where('is_occupied', true)
            ->whereHas('zone', fn($q) => $q->where('company_id', $companyId))
            ->count();

        // Total revenue Today
        $totalRevenue = Parking::where('company_id', $companyId)
            ->whereDate('created_at', today())
            ->sum('bill');

        // Total revenue MOMO
        $momo = Parking::where('company_id', $companyId)
            ->where('payment_method', 'momo')
            ->whereDate('created_at', today())
            ->sum('bill');

        // Total revenue Cash
        $cash = Parking::where('company_id', $companyId)
            ->where('payment_method', 'cash')
            ->whereDate('created_at', today())
            ->sum('bill');

        // Active tickets
        $activeTickets = Parking::where('company_id', $companyId)
            ->where('status', 'active')
            ->count();

        // Occupancy by zone
        $zones = Zone::where('company_id', $companyId)
            ->withCount([
                'slots as occupied_count' => function ($query) {
                    $query->where('is_occupied', true);
                }
            ])->get();
        $zoneNames = $zones->pluck('name');
        $occupancyCounts = $zones->pluck('occupied_count');

        // Revenue trends (last 6 months)
        $monthlyRevenue = Payment::where('company_id', $companyId)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $months = $monthlyRevenue->map(fn($item) => date('F', mktime(0, 0, 0, $item->month, 1)));
        $revenues = $monthlyRevenue->pluck('total');

        // Today's revenue
        $todaysRevenue = Parking::where('company_id', $companyId)
            ->whereDate('created_at', today())
            ->sum('bill');

        // Today's transaction count
        $todaysTransactions = Parking::where('company_id', $companyId)
            ->whereDate('created_at', today())
            ->count();

        // Most used zone this week
        $mostUsedZone = Parking::where('company_id', $companyId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->selectRaw('zone_id, COUNT(*) as count')
            ->groupBy('zone_id')
            ->with('zone')
            ->orderByDesc('count')
            ->first();

        // Average parking duration today
        $avgDuration = Parking::where('company_id', $companyId)
            ->whereDate('created_at', today())
            ->whereNotNull('exit_time')
            ->get()
            ->map(function ($item) {
                return Carbon::parse($item->created_at)->diffInMinutes(Carbon::parse($item->exit_time));
            })
            ->average();

        // Exempted vehicles count
        $exemptedCount = Parking::where('company_id', $companyId)
            ->where('status', 'exempt')
            ->count();

        return view('admin.dashboard', compact(
            'totalSlots',
            'occupiedSlots',
            'totalRevenue',
            'activeTickets',
            'zoneNames',
            'occupancyCounts',
            'months',
            'revenues',
            'todaysRevenue',
            'todaysTransactions',
            'mostUsedZone',
            'avgDuration',
            'exemptedCount',
            'momo',
            'cash'
        ));
    }
}

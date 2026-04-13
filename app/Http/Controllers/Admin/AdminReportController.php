<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parking;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
       $companyId = auth()->user()->company_id;
       $query = Parking::where('company_id', $companyId);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $filtered = $query->with(['zone', 'user'])->latest()->get();

        // Total revenue
        $totalRevenue = $filtered->sum('bill');

        // Most used zone
        $mostUsedZoneData = $filtered->groupBy('zone_id')
            ->map(fn($group) => ['count' => $group->count(), 'name' => optional($group->first()->zone)->name])
            ->sortByDesc('count')
            ->first();
        $mostUsedZone = $mostUsedZoneData['name'] ?? 'N/A';

        // Top client
        $topClientData = $filtered->groupBy('plate_number')
            ->map(fn($group) => ['count' => $group->count(), 'plate' => $group->first()->plate_number])
            ->sortByDesc('count')
            ->first();
        $topClient = $topClientData['plate'] ?? 'N/A';

        // Average duration (in minutes)
        $durations = $filtered->map(function ($parking) {
            if ($parking->entry_time && $parking->exit_time) {
                return Carbon::parse($parking->entry_time)->diffInMinutes(Carbon::parse($parking->exit_time));
            }
            return null;
        })->filter();

        $avgDuration = $durations->count() ? round($durations->avg()) : 0;

        // Payment method breakdown
        $cashPayments = $filtered->where('payment_method', 'cash')->sum('bill');
        $momoPayments = $filtered->where('payment_method', 'momo')->sum('bill');

        // Exempted vehicles count (only count if currently valid)
        $exemptedCount = Vehicle::where('company_id', $companyId)
            ->where(function ($q) {
                $q->whereNull('expired_at')->orWhere('expired_at', '>=', now());
            })->count();

        // Daily revenue trend (last 14 days)
        $dailyTrend = Parking::where('company_id', $companyId)
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw("DATE(created_at) as day, SUM(bill) as revenue, COUNT(*) as parkings")
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('admin.reports.index', compact(
            'totalRevenue',
            'mostUsedZone',
            'topClient',
            'avgDuration',
            'cashPayments',
            'momoPayments',
            'exemptedCount',
            'filtered',
            'dailyTrend'
        ));
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $query = Parking::query();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $filtered = $query->with(['zone', 'user'])->get();

        // Total revenue
        $totalRevenue = $filtered->sum('bill');

        // Most used zone
        $mostUsedZone = $filtered->groupBy('zone_id')
            ->map(fn($group) => ['count' => $group->count(), 'name' => optional($group->first()->zone)->name])
            ->sortByDesc('count')
            ->first();

        // Top client
        $topClient = $filtered->groupBy('user_id')
            ->map(fn($group) => ['count' => $group->count(), 'name' => optional($group->first()->user)->name])
            ->sortByDesc('count')
            ->first();

        // Average duration (in minutes)
        $durations = $filtered->map(function ($parking) {
            if ($parking->entry_time && $parking->exit_time) {
                return Carbon::parse($parking->entry_time)->diffInMinutes(Carbon::parse($parking->exit_time));
            }
            return null;
        })->filter();

        $avgDuration = $durations->count() ? round($durations->avg()) : 0;

        // Payment method breakdown
        $cashPayments = $filtered->where('payment_method', 'cash')->count();
        $momoPayments = $filtered->where('payment_method', 'momo')->count();

        // Exempted vehicles count (only count if currently valid)
        $exemptedCount = Vehicle::where(function ($q) {
                $q->whereNull('expired_at')->orWhere('expired_at', '>=', now());
            })->count();

        return view('admin.reports.index', compact(
            'totalRevenue',
            'mostUsedZone',
            'topClient',
            'avgDuration',
            'cashPayments',
            'momoPayments',
            'exemptedCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

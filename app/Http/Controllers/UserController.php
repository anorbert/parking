<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parking;
use App\Models\Slot;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $activeParkings = Parking::where('status', 'active')
                                ->where('zone_id', $user->zone_id)
                                ->where('company_id', $user->company_id)
                                ->whereNull('exit_time')
                                ->get();

        $totalSlots = Slot::where('zone_id', $user->zone_id)
                          ->count();

        $todayRevenue = Parking::where('zone_id', $user->zone_id)
                               ->where('company_id', $user->company_id)
                               ->whereNotNull('exit_time')
                               ->whereDate('exit_time', Carbon::today())
                               ->sum('bill');

        return view('users.dashboard', compact('activeParkings', 'totalSlots', 'todayRevenue'));
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

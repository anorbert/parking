<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParkingRate;
use App\Models\Zone;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $zones = Zone::all();
        $rates = ParkingRate::with('zone')->get();
        return view('admin.rates.index', compact('zones', 'rates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $zones = Zone::all();
        return view('admin.rates.create', compact('zones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'zone_id' => 'nullable|exists:zones,id',
            'duration_minutes' => 'required|integer',
            'rate' => 'required|numeric|min:0',
        ]);

        $parking=ParkingRate::create($request->only('zone_id', 'duration_minutes', 'rate'));
        if (!$parking) {
            return back()->with('error', 'Failed to add rate. Please try again.');
        }
        // If the rate is added successfully, redirect back with a success message

        return back()->with('success', 'Payment Rates added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $rate = ParkingRate::with('zone')->findOrFail($id);
        return view('admin.rates.show', compact('rate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $rate = ParkingRate::with('zone')->findOrFail($id);
        $zones = Zone::all();
        return view('admin.rates.edit', compact('rate', 'zones'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'zone_id' => 'nullable|exists:zones,id',
            'duration_minutes' => 'required|integer',
            'rate' => 'required|numeric|min:0',
        ]);
        $parking = ParkingRate::findOrFail($id);
        $parking->update($request->only('zone_id', 'duration_minutes', 'rate'));
        if (!$parking) {
            return back()->with('error', 'Failed to update rate. Please try again.');
        }
        // If the rate is updated successfully, redirect back with a success message
        return back()->with('success', 'Payment Rates updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $parking = ParkingRate::findOrFail($id);
        if (!$parking->delete()) {
            return back()->with('error', 'Failed to delete rate. Please try again.');
        }
        // If the rate is deleted successfully, redirect back with a success message
        return back()->with('success', 'Payment Rates deleted successfully.');
    }
}

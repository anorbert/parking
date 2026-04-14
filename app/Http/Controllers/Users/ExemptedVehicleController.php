<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vehicle;

class ExemptedVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::where('company_id', auth()->user()->company_id)->latest()->get();
        return view('users.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_contact' => 'nullable|string|max:255',
            'billing_type' => 'nullable|string|max:255',
            'reason' => 'nullable|string|max:255',
        ]);

        $exists = Vehicle::where('plate_number', $request->plate_number)
            ->where('company_id', auth()->user()->company_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Vehicle with this plate number already exists.');
        }

        Vehicle::create([
            'plate_number' => $request->plate_number,
            'vehicle_type' => 'Car',
            'owner_name' => $request->owner_name,
            'owner_contact' => $request->owner_contact,
            'billing_type' => $request->billing_type ?? 'Free',
            'reason' => $request->reason,
            'company_id' => auth()->user()->company_id,
        ]);

        return back()->with('success', 'Vehicle added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vehicle = Vehicle::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('users.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vehicle = Vehicle::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('users.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'plate_number' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_contact' => 'nullable|string|max:255',
            'billing_type' => 'nullable|string|max:255',
            'reason' => 'nullable|string|max:255',
        ]);

        $vehicle = Vehicle::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $vehicle->update($request->only(['plate_number', 'owner_name', 'owner_contact', 'billing_type', 'reason']));

        return back()->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vehicle = Vehicle::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $vehicle->delete();
        return back()->with('success', 'Vehicle deleted successfully.');
    }
}

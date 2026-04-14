<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\ParkingRate;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = auth()->user()->company_id;
        $vehicles = Vehicle::where('company_id', $companyId)->get();
        return view('admin.vehicles.index', compact('vehicles'));
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
        $companyId = auth()->user()->company_id;

        $request->validate([
            'plate_number' => 'required|string|max:255',
            'vehicle_type' => 'nullable|string|max:255',
            'expired_at' => 'nullable|date',
            'owner_name' => 'required|string|max:255',
            'owner_contact' => 'nullable|string|max:255',
            'billing_type' => 'nullable|string|max:255',
            'reason' => 'nullable|string|max:255',
        ]);

        $existingVehicle = Vehicle::where('plate_number', $request->plate_number)
            ->where('company_id', $companyId)
            ->first();
        if ($existingVehicle) {
            return back()->with('error', 'Vehicle with this plate number already exists.');
        }

        $vehicle = Vehicle::create([
            'plate_number' => $request->plate_number,
            'vehicle_type' => $request->vehicle_type ?? 'Car',
            'expired_at' => $request->expired_at,
            'owner_name' => $request->owner_name,
            'owner_contact' => $request->owner_contact,
            'billing_type' => $request->billing_type ?? 'Free',
            'reason' => $request->reason,
            'company_id' => $companyId,
        ]);

        if (!$vehicle) {
            return back()->with('error', 'Failed to add vehicle. Please try again.');
        }

        return back()->with('success', 'Vehicle added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vehicle = Vehicle::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vehicle = Vehicle::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $companyId = auth()->user()->company_id;

        $request->validate([
            'plate_number' => 'required|string|max:255|unique:vehicles,plate_number,' . $id,
            'vehicle_type' => 'nullable|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_contact' => 'nullable|string|max:255',
            'billing_type' => 'nullable|string|max:255',
            'reason' => 'nullable|string|max:255',
        ]);

        $vehicle = Vehicle::where('company_id', $companyId)->findOrFail($id);
        $vehicle->update([
            'plate_number' => $request->plate_number,
            'vehicle_type' => $request->vehicle_type ?? $vehicle->vehicle_type,
            'owner_name' => $request->owner_name,
            'owner_contact' => $request->owner_contact,
            'billing_type' => $request->billing_type ?? 'Free',
            'reason' => $request->reason,
        ]);

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

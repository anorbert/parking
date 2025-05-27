<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zone;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $zones = Zone::with('slots')->get();
        $users = User::where('role_id',3)->get();
        return view('admin.zones.index', compact('zones', 'users'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $request->validate(['name' => 'required|unique:zones']);

        Zone::create(['name' => strtoupper($request->name)]);
        return back()->with('success', 'Zone created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'unique:zones,name',
            'name' => 'required|string|max:255',
            'name' => 'regex:/^[a-zA-Z0-9\s]+$/', // Allow alphanumeric and spaces only
            'name' => 'min:3|max:50', // Minimum 3 characters, maximum 50 characters
            'capacity' => 'required|integer|min:1|max:1000', // Assuming a maximum capacity of 1000
            
        ]);

        Zone::create([
            'name' => $request->name,
            'capacity' => $request->capacity,
        ]);

        return back()->with('success', 'Zone added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $zone = Zone::with('slots')->findOrFail($id);
        $users = User::where('role_id', 3)->get();
        return view('admin.zones.show', compact('zone', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $zone = Zone::findOrFail($id);
        $users = User::where('role_id', 3)->get();
        return view('admin.zones.edit', compact('zone', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255|unique:zones,name,' . $id,
            'capacity' => 'required|integer|min:1|max:1000', // Assuming a maximum capacity of 1000
        ]);
        $zone = Zone::findOrFail($id);
        $zone->update([
            'name' => $request->name,
            'capacity' => $request->capacity,
        ]);
        return redirect()->route('zones.index')->with('success', 'Zone updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $zone = Zone::findOrFail($id);
        // Check if the zone has any slots before deleting
        if ($zone->slots()->count() > 0) {
            return back()->with('error', 'Cannot delete zone with existing slots.');
        }
        $zone->delete();
        return redirect()->route('zones.index')->with('success', 'Zone deleted successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function slotstore(Request $request)
    {
        //
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'number' => 'required'
        ]);

        Slot::create([
            'zone_id' => $request->zone_id,
            'number' => strtoupper($request->number),
        ]);

        return back()->with('success', 'Slot added successfully.');
    }
}

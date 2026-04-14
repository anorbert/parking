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
        $companyId = auth()->user()->company_id;
        $zones = Zone::where('company_id', $companyId)->with('slots')->get();
        $users = User::where('role_id', 3)->where('company_id', $companyId)->get();
        return view('admin.zones.index', compact('zones', 'users'));
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
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-Z0-9\s]+$/', 'unique:zones,name'],
            'total_slots' => 'required|integer|min:1|max:1000',
        ]);

        Zone::create([
            'name' => $request->name,
            'total_slots' => $request->total_slots,
            'company_id' => auth()->user()->company_id,
        ]);

        return back()->with('success', 'Zone added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $companyId = auth()->user()->company_id;
        $zone = Zone::where('company_id', $companyId)->with('slots')->findOrFail($id);
        $users = User::where('role_id', 3)->where('company_id', $companyId)->get();
        return view('admin.zones.show', compact('zone', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $companyId = auth()->user()->company_id;
        $zone = Zone::where('company_id', $companyId)->findOrFail($id);
        $users = User::where('role_id', 3)->where('company_id', $companyId)->get();
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
            'total_slots' => 'required|integer|min:1|max:1000',
        ]);
        $zone = Zone::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $zone->update([
            'name' => $request->name,
            'total_slots' => $request->total_slots,
        ]);
        return redirect()->route('admin.zones.index')->with('success', 'Zone updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $zone = Zone::where('company_id', auth()->user()->company_id)->findOrFail($id);
        if ($zone->slots()->count() > 0) {
            return back()->with('error', 'Cannot delete zone with existing slots.');
        }
        $zone->delete();
        return redirect()->route('admin.zones.index')->with('success', 'Zone deleted successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function slotstore(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'prefix' => 'required|string|max:5',
            'start' => 'required|integer|min:1|max:999',
            'count' => 'required|integer|min:1|max:100',
        ]);

        $prefix = strtoupper($request->prefix);
        $start = (int) $request->start;
        $count = (int) $request->count;
        $zoneId = $request->zone_id;
        $created = 0;
        $skipped = 0;

        for ($i = 0; $i < $count; $i++) {
            $number = $prefix . ($start + $i);
            $exists = Slot::where('zone_id', $zoneId)->where('number', $number)->exists();
            if (!$exists) {
                Slot::create(['zone_id' => $zoneId, 'number' => $number]);
                $created++;
            } else {
                $skipped++;
            }
        }

        $msg = $created . ' slot(s) created successfully.';
        if ($skipped > 0) {
            $msg .= ' ' . $skipped . ' skipped (already exist).';
        }

        return back()->with('success', $msg);
    }
}

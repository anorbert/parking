<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Zone;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $staff =  User::with('role')->paginate(10);
        return view('admin.staffs.index',compact('staff'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $zones= Zone::all();
        $roles = Role::all();
        return view('admin.staffs.create',compact('zones','roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
        ]);
        
        //chechk if user already exists
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'User with this email already exists.');
        }
        // check the zone
        if ($request->role_id == 3) { // Assuming role_id 3 is for 'editor'
            $zone = Zone::find($request->zone_id);
            if (!$zone) {
                return redirect()->back()->with('error', 'Invalid zone selected.');
            }
        }
        // If validation passes, proceed to create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'role_id' => $request->role_id,
            'zone_id' => $request->zone_id ?? null, // Optional zone_id for roles that require it
            'password' => Hash::make(12345678),
        ]);
        // Check if the user was created successfully

        if ($user) {
            return redirect()->route('staff.index')->with('success', 'Staff created successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to create staff.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::with('role')->findOrFail($id);
        return view('admin.staffs.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $user = User::with('role')->findOrFail($id);
        $zones = Zone::all();
        $roles = Role::all();
        return view('admin.staffs.edit', compact('user', 'zones', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 
        // return $request->all();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
        ]);
        // check if role is 3
        if ($request->role_id == 3) { // Assuming role_id 3 is for 'editor'
            $zone = Zone::find($request->zone_id);
            if (!$zone) {
                return redirect()->back()->with('error', 'Invalid zone selected.');
            }
        }
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->zone_id = $request->zone_id ?? null; // Optional zone_id for roles that require it
        $user->role_id = $request->role_id;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return redirect()->route('staff.index')->with('success', 'Staff updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::with('role')->findOrFail($id);
        if ($user->delete()) {
            return redirect()->route('staff.index')->with('success', 'Staff deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete staff.');
        }
    }
}

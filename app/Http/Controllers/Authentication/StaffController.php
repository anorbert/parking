<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Zone;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\NewStaffNotification;

class StaffController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;
        $staff = User::with('role')
            ->where('company_id', $companyId)
            ->paginate(10);
        return view('admin.staffs.index', compact('staff'));
    }

    public function create()
    {
        $companyId = auth()->user()->company_id;
        $zones = Zone::where('company_id', $companyId)->get();
        $roles = Role::whereIn('id', [2, 3, 4])->get(); // Exclude Super Admin
        return view('admin.staffs.create', compact('zones', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20|unique:users,phone_number',
            'role_id' => 'required|exists:roles,id',
            'zone_id' => 'nullable|exists:zones,id',
        ]);

        // Validate zone if required for role
        if ($request->role_id == 3 && !$request->zone_id) {
            return redirect()->back()->with('error', 'Zone is required for this role.');
        }

        // Set a 4-digit random PIN or use default (you may customize this logic)
        $defaultPin = 1234;

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'role_id'      => $request->role_id,
            'zone_id'      => $request->zone_id ?? null,
            'password'     => Hash::make($defaultPin),
            'company_id'   => auth()->user()->company_id,
        ]);

        if ($user) {
            // Notify company admin
            $roleName = Role::find($request->role_id)->name ?? 'Staff';
            auth()->user()->notify(new NewStaffNotification($user->name, $roleName));

            return redirect()->route('admin.staff.index')->with('success', 'Staff created successfully. Default PIN: ' . $defaultPin);
        }

        return redirect()->back()->with('error', 'Failed to create staff.');
    }

    public function show(string $id)
    {
        $user = User::with('role')
            ->where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        return view('admin.staffs.show', compact('user'));
    }

    public function edit(string $id)
    {
        $companyId = auth()->user()->company_id;
        $user = User::where('company_id', $companyId)->findOrFail($id);
        $zones = Zone::where('company_id', $companyId)->get();
        $roles = Role::whereIn('id', [2, 3, 4])->get();
        return view('admin.staffs.edit', compact('user', 'zones', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::where('company_id', auth()->user()->company_id)->findOrFail($id);

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:20|unique:users,phone_number,' . $id,
            'role_id'      => 'required|exists:roles,id',
            'zone_id'      => 'nullable|exists:zones,id',
            'password'     => 'nullable|digits:4',
        ]);

        if ($request->role_id == 3 && !$request->zone_id) {
            return redirect()->back()->with('error', 'Zone is required for this role.');
        }

        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone_number = $request->phone_number;
        $user->role_id      = $request->role_id;
        $user->zone_id      = $request->zone_id ?? null;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy(string $id)
    {
        $user = User::where('company_id', auth()->user()->company_id)->findOrFail($id);

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->delete()) {
            return redirect()->route('admin.staff.index')->with('success', 'Staff deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete staff.');
    }
}

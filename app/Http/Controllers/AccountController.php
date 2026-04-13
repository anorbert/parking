<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    /**
     * Show profile page (admin or user layout based on role).
     */
    public function profile()
    {
        $user = Auth::user();
        $layout = $user->role_id <= 2 ? 'admin' : 'user';
        return view('account.profile', compact('user', 'layout'));
    }

    /**
     * Update profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => ['required', 'regex:/^07[2389][0-9]{7}$/', 'unique:users,phone_number,' . $user->id],
        ]);

        $user->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Show settings page with change PIN.
     */
    public function settings()
    {
        $user = Auth::user();
        $layout = $user->role_id <= 2 ? 'admin' : 'user';
        return view('account.settings', compact('user', 'layout'));
    }

    /**
     * Update PIN from settings page.
     */
    public function updatePin(Request $request)
    {
        $request->validate([
            'current_pin'      => 'required|digits:4',
            'new_pin'          => 'required|digits:4|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_pin, $user->password)) {
            return back()->withErrors(['current_pin' => 'Current PIN is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_pin),
        ]);

        Log::info('User PIN changed from settings. User ID: ' . $user->id);

        return back()->with('success', 'PIN updated successfully.');
    }
}

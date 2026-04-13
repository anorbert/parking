<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * Show registration form.
     */
    public function index()
    {
        return view('Auth.register');
    }

    /**
     * Show registration form (alias).
     */
    public function create()
    {
        return view('Auth.register');
    }

    /**
     * Handle registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'regex:/^07[2389][0-9]{7}$/', 'unique:users,phone_number'],
            'pin' => ['required', 'digits:4', 'confirmed'],
        ]);

        try {
            $user = DB::transaction(function () use ($request) {
                return User::create([
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'password' => Hash::make($request->pin),
                    'role_id' => 3,
                ]);
            });

            event(new Registered($user));
            Auth::login($user);

            return redirect()->route('user.dashboard')->with('success', 'Registration successful. Welcome!');

        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong. Please try again.');
        }
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

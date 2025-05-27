<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Auth;
use Log;


class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $remember = $request->has('remember');

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
                // Authenticated successfully
                Log::info('User logged in successfully. User ID: ' . $user->id);

                // Check role_id and redirect accordingly
                switch ($user->role_id) {
                    case 1:
                        return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
                    case 2:
                        return redirect()->route('editor.dashboard')->with('success', 'Welcome Editor!');
                    case 3:
                        return redirect()->route('user.dashboard')->with('success', 'Welcome!');
                    default:
                        Auth::logout();
                        return redirect()->back()->with('error', 'Unauthorized role.');
                }

            } else {
                Log::warning('Failed login attempt for email: ' . $request->email);
                return redirect()->back()->with('error', 'Invalid credentials.');
            }

        } else {
            Log::warning('Login failed: user not found for email ' . $request->email);
            return redirect()->back()->with('error', 'User not found.');
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

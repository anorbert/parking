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
            'phone' => 'required',
            'pin' => 'required|digits:4',
        ]);

        // Attempt login
        $credentials = [
            'phone_number' => $request->phone,
            'password' => $request->pin, // still called password in DB
        ];

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            Log::info('User logged in successfully. User ID: ' . $user->id);

            // Redirect based on role
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
        }

        Log::warning('Failed login attempt for phone: ' . $request->phone);
        return redirect()->back()->with('error', 'Invalid credentials.');
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

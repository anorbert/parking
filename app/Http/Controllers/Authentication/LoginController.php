<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function index()
    {
        return view('Auth.login');
    }

    /**
     * Show the login form (named alias).
     */
    public function showLoginForm()
    {
        return view('Auth.login');
    }

    /**
     * Show the change-pin form.
     */
    public function create()
    {
        $user = Auth::user();
        return view('Auth.change-pin', compact('user'));
    }

    /**
     * Handle login request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
            'pin' => 'required|digits:4',
        ]);

        try {
            $credentials = [
                'phone_number' => $request->phone,
                'password' => $request->pin,
            ];

            $remember = $request->boolean('remember');

            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();

                $user = Auth::user();
                Log::info('User logged in successfully. User ID: ' . $user->id);

                // Check if using default PIN and force change
                if ($request->pin === '1234') {
                    Log::info('User attempted to login with default pin. Phone: ' . $request->phone);
                    return redirect()->route('user.change-pin.create')
                        ->with('warning', 'Please change your default PIN.');
                }

                // Redirect based on role
                switch ($user->role_id) {
                    case 1:
                        return redirect()->route('superadmin.dashboard')->with('success', 'Welcome Super Admin!');
                    case 2:
                        return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
                    case 3:
                        return redirect()->route('user.dashboard')->with('success', 'Welcome!');
                    case 4:
                        return redirect()->route('user.dashboard')->with('success', 'Welcome!');
                    default:
                        Auth::logout();
                        $request->session()->invalidate();
                        return redirect()->route('login')->with('error', 'Unauthorized role.');
                }
            }

            Log::warning('Failed login attempt for phone: ' . $request->phone);
            return back()->withInput()->with('error', 'Invalid credentials.');

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
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
     * Update PIN (change-pin).
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|digits:4|confirmed',
        ]);

        try {
            $user = User::findOrFail($id);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current PIN does not match.']);
            }

            DB::transaction(function () use ($user, $request) {
                $user->update([
                    'password' => Hash::make($request->new_password),
                ]);
            });

            Log::info('User PIN changed successfully. User ID: ' . $user->id);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'PIN changed successfully. Please log in again.');

        } catch (\Exception $e) {
            Log::error('PIN change error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

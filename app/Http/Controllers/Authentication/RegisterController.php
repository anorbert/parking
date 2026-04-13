<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Invoice;
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
     * Handle registration request — creates user + company + trial subscription.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'phone_number'    => ['required', 'regex:/^07[2389][0-9]{7}$/', 'unique:users,phone_number'],
            'pin'             => ['required', 'digits:4', 'confirmed'],
            'company_name'    => ['required', 'string', 'max:255'],
            'company_tin'     => ['nullable', 'string', 'max:50', 'unique:companies,tin'],
            'company_phone'   => ['nullable', 'string', 'max:20'],
            'company_email'   => ['nullable', 'email', 'max:255', 'unique:companies,email'],
            'company_address' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $result = DB::transaction(function () use ($request) {
                // 1. Create the company
                $company = Company::create([
                    'name'    => $request->company_name,
                    'tin'     => $request->company_tin,
                    'phone'   => $request->company_phone,
                    'email'   => $request->company_email,
                    'address' => $request->company_address,
                    'status'  => 'Active',
                ]);

                // 2. Create the admin user
                $user = User::create([
                    'name'         => $request->name,
                    'phone_number' => $request->phone_number,
                    'password'     => Hash::make($request->pin),
                    'role_id'      => 2,
                    'company_id'   => $company->id,
                ]);

                // 3. Create a starter subscription (auto-active, 30-day trial)
                $plan = SubscriptionPlan::active()->orderBy('price', 'asc')->first();
                $amount   = $plan ? $plan->price : 15000;
                $duration = $plan ? $plan->duration_days : 30;

                $subscription = Subscription::create([
                    'company_id' => $company->id,
                    'plan_id'    => $plan?->id,
                    'amount'     => $amount,
                    'status'     => 'Active',
                    'start_date' => now(),
                    'end_date'   => now()->addDays($duration),
                    'paid_at'    => now(),
                    'created_by' => $user->id,
                ]);

                // 4. Create paid invoice
                Invoice::create([
                    'company_id' => $company->id,
                    'amount'     => $amount,
                    'status'     => 'Paid',
                    'due_date'   => now()->addDays($duration),
                    'reference'  => 'INV-REG-' . strtoupper(uniqid()),
                ]);

                return $user;
            });

            event(new Registered($result));
            Auth::login($result);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Registration successful! Your company is now active.');

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

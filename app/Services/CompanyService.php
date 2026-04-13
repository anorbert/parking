<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanyService
{
    /**
     * Register a new company with admin user and initial subscription.
     */
    public function register(array $companyData, array $adminData): array
    {
        return DB::transaction(function () use ($companyData, $adminData) {
            // Create company
            $company = Company::create([
                'name'    => $companyData['name'],
                'tin'     => $companyData['tin'] ?? null,
                'phone'   => $companyData['phone'] ?? null,
                'email'   => $companyData['email'] ?? null,
                'address' => $companyData['address'] ?? null,
                'status'  => 'Active',
            ]);

            // Create company admin user
            $defaultPin = $adminData['pin'] ?? '1234';
            $user = User::create([
                'name'         => $adminData['name'],
                'email'        => $adminData['email'] ?? null,
                'phone_number' => $adminData['phone_number'],
                'password'     => Hash::make($defaultPin),
                'role_id'      => 2, // Company Admin
                'company_id'   => $company->id,
            ]);

            // Create initial subscription (auto-activated)
            $subscription = Subscription::create([
                'company_id' => $company->id,
                'amount'     => 20000,
                'status'     => 'Active',
                'start_date' => now(),
                'end_date'   => now()->addMonth(),
                'paid_at'    => now(),
                'created_by' => $user->id,
            ]);

            // Create invoice (auto-paid)
            $invoice = Invoice::create([
                'company_id' => $company->id,
                'amount'     => 20000,
                'status'     => 'Paid',
                'due_date'   => now()->addDays(7),
                'reference'  => 'INV-' . strtoupper(Str::random(8)),
            ]);

            return [
                'company'      => $company,
                'user'         => $user,
                'subscription' => $subscription,
                'invoice'      => $invoice,
                'pin'          => $defaultPin,
            ];
        });
    }

    /**
     * Activate a subscription payment.
     */
    public function activateSubscription(int $subscriptionId, ?int $userId = null): Subscription
    {
        return DB::transaction(function () use ($subscriptionId, $userId) {
            $subscription = Subscription::findOrFail($subscriptionId);
            $subscription->update([
                'status'  => 'Active',
                'paid_at' => now(),
            ]);

            // Mark related invoice as paid
            Invoice::where('company_id', $subscription->company_id)
                ->where('status', 'Unpaid')
                ->where('amount', $subscription->amount)
                ->latest()
                ->first()
                ?->update(['status' => 'Paid']);

            return $subscription;
        });
    }

    /**
     * Renew subscription for a company.
     */
    public function renewSubscription(int $companyId, ?int $userId = null): array
    {
        return DB::transaction(function () use ($companyId, $userId) {
            $subscription = Subscription::create([
                'company_id' => $companyId,
                'amount'     => 20000,
                'status'     => 'Active',
                'start_date' => now(),
                'end_date'   => now()->addMonth(),
                'paid_at'    => now(),
                'created_by' => $userId,
            ]);

            $invoice = Invoice::create([
                'company_id' => $companyId,
                'amount'     => 20000,
                'status'     => 'Paid',
                'due_date'   => now(),
                'reference'  => 'INV-' . strtoupper(Str::random(8)),
            ]);

            return [
                'subscription' => $subscription,
                'invoice'      => $invoice,
            ];
        });
    }
}

<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Bank;
use App\Models\PaymentToken;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CompanyService
{
    /**
     * Register a new company with admin user and initial subscription.
     */
    public function register(array $companyData, array $adminData, ?int $planId = null): array
    {
        return DB::transaction(function () use ($companyData, $adminData, $planId) {
            // Resolve plan
            $plan = $planId
                ? SubscriptionPlan::findOrFail($planId)
                : SubscriptionPlan::active()->first();

            $amount = $plan ? $plan->price : 20000;
            $durationDays = $plan ? $plan->duration_days : 30;

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
                'plan_id'    => $plan?->id,
                'amount'     => $amount,
                'status'     => 'Active',
                'start_date' => now(),
                'end_date'   => now()->addDays($durationDays),
                'paid_at'    => now(),
                'created_by' => $user->id,
                'payment_method' => 'cash',
            ]);

            // Create invoice (auto-paid)
            $invoice = Invoice::create([
                'company_id' => $company->id,
                'amount'     => $amount,
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
     * Renew subscription for a company (cash).
     */
    public function renewSubscription(int $companyId, int $planId, ?int $userId = null): array
    {
        return DB::transaction(function () use ($companyId, $planId, $userId) {
            $plan = SubscriptionPlan::findOrFail($planId);

            // Expire any current active subscriptions
            Subscription::where('company_id', $companyId)
                ->where('status', 'Active')
                ->update(['status' => 'Expired']);

            $subscription = Subscription::create([
                'company_id' => $companyId,
                'plan_id'    => $plan->id,
                'amount'     => $plan->price,
                'status'     => 'Active',
                'start_date' => now(),
                'end_date'   => now()->addDays($plan->duration_days),
                'paid_at'    => now(),
                'created_by' => $userId,
                'payment_method' => 'cash',
            ]);

            $invoice = Invoice::create([
                'company_id' => $companyId,
                'amount'     => $plan->price,
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

    /**
     * Create a pending subscription and initiate MoMo payment.
     */
    public function renewWithMomo(int $companyId, int $planId, string $phone, ?int $userId = null): array
    {
        return DB::transaction(function () use ($companyId, $planId, $phone, $userId) {
            $plan = SubscriptionPlan::findOrFail($planId);
            $bank = Bank::where('payment_owner', 'FDI')->firstOrFail();

            // Get or refresh FDI token
            $token = PaymentToken::where('bank_id', $bank->id)
                ->where('expired_at', '>', now())
                ->first();

            if (!$token) {
                $res = Http::post('https://payments-api.fdibiz.com/v2/auth', [
                    'appId'  => $bank->appId,
                    'secret' => $bank->secret,
                ]);

                if (!$res->successful()) {
                    throw new \Exception('Payment authentication failed.');
                }

                $data = $res->json();
                $token = PaymentToken::create([
                    'bank_id'    => $bank->id,
                    'token'      => $data['data']['token'],
                    'expired_at' => $data['data']['expires_at'],
                ]);
            }

            $number = preg_replace('/\D/', '', $phone);
            $prefix = substr($number, -9, 2);
            $trx_ref = 'SUB-' . Str::random(28);

            $operator = match ($prefix) {
                '78', '79' => 'momo-mtn-rw',
                '72', '73' => 'momo-airtel-rw',
                default    => null,
            };

            if (!$operator) {
                throw new \Exception('Invalid phone number for MoMo payment.');
            }

            // Create pending subscription
            $subscription = Subscription::create([
                'company_id'     => $companyId,
                'plan_id'        => $plan->id,
                'amount'         => $plan->price,
                'status'         => 'Pending',
                'start_date'     => now(),
                'end_date'       => now()->addDays($plan->duration_days),
                'created_by'     => $userId,
                'payment_method' => 'momo',
                'payment_phone'  => $phone,
                'trx_ref'        => $trx_ref,
            ]);

            // Create unpaid invoice
            $invoice = Invoice::create([
                'company_id' => $companyId,
                'amount'     => $plan->price,
                'status'     => 'Unpaid',
                'due_date'   => now()->addDays(7),
                'reference'  => 'INV-' . strtoupper(Str::random(8)),
            ]);

            // Initiate MoMo pull
            $res = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token->token,
                'Content-Type'  => 'application/json',
            ])->post('https://payments-api.fdibiz.com/v2/momo/pull', [
                'trxRef'    => $trx_ref,
                'channelId' => $operator,
                'accountId' => $bank->appId,
                'msisdn'    => '250' . substr($number, -9),
                'amount'    => $plan->price,
                'callback'  => config('app.url') . '/api/subscription/callback',
            ]);

            // Log in payment_histories
            PaymentHistory::create([
                'amount'       => $plan->price,
                'phone_number' => $phone,
                'bank_id'      => $bank->id,
                'parking_id'   => 0,
                'type'         => 'MOMO',
                'status'       => 'Processing',
                'channel'      => $operator,
                'description'  => 'Subscription payment for plan: ' . $plan->name,
                'trx_ref'      => $trx_ref,
                'company_id'   => $companyId,
            ]);

            return [
                'success'      => true,
                'subscription' => $subscription,
                'invoice'      => $invoice,
                'trx_ref'      => $trx_ref,
                'message'      => 'MoMo payment initiated. Please approve on your phone.',
            ];
        });
    }

    /**
     * Handle MoMo callback for subscription payment.
     */
    public function handleSubscriptionCallback(string $trxRef, string $status, ?string $gwRef = null, ?string $failureMessage = null): bool
    {
        $subscription = Subscription::where('trx_ref', $trxRef)->first();
        if (!$subscription) {
            return false;
        }

        if ($subscription->status === 'Active') {
            return true; // already processed
        }

        $transaction = PaymentHistory::where('trx_ref', $trxRef)->first();

        if ($status === 'success') {
            // Expire previous active subscriptions
            Subscription::where('company_id', $subscription->company_id)
                ->where('id', '!=', $subscription->id)
                ->where('status', 'Active')
                ->update(['status' => 'Expired']);

            $subscription->update([
                'status'  => 'Active',
                'paid_at' => now(),
            ]);

            // Mark invoice as paid
            Invoice::where('company_id', $subscription->company_id)
                ->where('status', 'Unpaid')
                ->where('amount', $subscription->amount)
                ->latest()
                ->first()
                ?->update(['status' => 'Paid']);

            if ($transaction) {
                $bank = $transaction->bank;
                if ($bank) {
                    $charges = $bank->charges;
                    $netAmount = $subscription->amount - ($subscription->amount * $charges / 100);
                    $bank->balance += $netAmount;
                    $bank->save();
                }
                $transaction->update([
                    'status'      => 'Completed',
                    'gwRef'       => $gwRef,
                    'description' => 'Subscription payment completed.',
                ]);
            }

            return true;
        }

        // Failed
        $subscription->update(['status' => 'Expired']);

        if ($transaction) {
            $transaction->update([
                'status'      => 'Failed',
                'description' => $failureMessage ?? 'Payment failed.',
            ]);
        }

        return false;
    }
}

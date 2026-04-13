<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionPaymentController extends Controller
{
    protected CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * Show the subscription expired page with plans and payment options.
     */
    public function expired()
    {
        $plans = SubscriptionPlan::active()->orderBy('sort_order')->get();
        $company = Auth::user()->company;
        $lastSubscription = $company
            ? Subscription::where('company_id', $company->id)->latest()->first()
            : null;

        return view('subscription.expired', compact('plans', 'company', 'lastSubscription'));
    }

    /**
     * Initiate MoMo payment for subscription.
     */
    public function payWithMomo(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'phone'   => ['required', 'regex:/^(078|079|072|073|07[2389])\d{7}$/'],
        ]);

        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return back()->with('error', 'No company associated with your account.');
        }

        try {
            $result = $this->companyService->renewWithMomo(
                $company->id,
                $request->plan_id,
                $request->phone,
                $user->id
            );

            return redirect()->route('subscription.processing', ['trx_ref' => $result['trx_ref']]);
        } catch (\Exception $e) {
            Log::error('Subscription MoMo payment failed: ' . $e->getMessage());
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Show payment processing/polling page.
     */
    public function processing(Request $request)
    {
        $trxRef = $request->query('trx_ref');
        if (!$trxRef) {
            return redirect()->route('subscription.expired');
        }

        return view('subscription.processing', compact('trxRef'));
    }

    /**
     * API: Check subscription payment status.
     */
    public function checkStatus(Request $request)
    {
        $trxRef = $request->query('trx_ref');
        if (!$trxRef) {
            return response()->json(['status' => 'error', 'message' => 'Missing trx_ref']);
        }

        $subscription = Subscription::where('trx_ref', $trxRef)->first();
        if (!$subscription) {
            return response()->json(['status' => 'error', 'message' => 'Subscription not found']);
        }

        return response()->json([
            'status'  => strtolower($subscription->status),
            'message' => match ($subscription->status) {
                'Active'  => 'Payment successful! Redirecting...',
                'Pending' => 'Waiting for payment confirmation...',
                default   => 'Payment failed or expired.',
            },
        ]);
    }

    /**
     * API: Handle FDI callback for subscription payments.
     */
    public function handleCallback(Request $request)
    {
        $data = $request->all();
        Log::info('Subscription callback received', $data);

        $trxRef = $data['trxRef'] ?? $data['trx_ref'] ?? null;
        $status = $data['status'] ?? null;
        $gwRef  = $data['gwRef'] ?? null;
        $failureMessage = $data['failureMessage'] ?? $data['message'] ?? null;

        if (!$trxRef) {
            return response()->json(['message' => 'Missing trxRef'], 400);
        }

        $success = $this->companyService->handleSubscriptionCallback(
            $trxRef,
            $status === 'successful' ? 'success' : 'failed',
            $gwRef,
            $failureMessage
        );

        return response()->json(['message' => $success ? 'OK' : 'Failed']);
    }
}

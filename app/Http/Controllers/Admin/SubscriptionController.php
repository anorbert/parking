<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    protected CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * Show subscription management page with current plan, comparison, and renew/upgrade.
     */
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        $plans = SubscriptionPlan::active()->orderBy('sort_order')->get();

        $currentSubscription = null;
        $subscriptionHistory = collect();

        if ($company) {
            $currentSubscription = Subscription::where('company_id', $company->id)
                ->latest()
                ->first();

            $subscriptionHistory = Subscription::where('company_id', $company->id)
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();
        }

        return view('admin.subscription.index', compact(
            'plans',
            'currentSubscription',
            'subscriptionHistory',
            'company'
        ));
    }

    /**
     * Renew current plan via MoMo.
     */
    public function renew(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'phone'   => ['required', 'regex:/^(078|079|072|073)\d{7}$/'],
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
            Log::error('Subscription renew failed: ' . $e->getMessage());
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Upgrade to a different plan via MoMo.
     */
    public function upgrade(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'phone'   => ['required', 'regex:/^(078|079|072|073)\d{7}$/'],
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
            Log::error('Subscription upgrade failed: ' . $e->getMessage());
            return back()->with('error', 'Upgrade failed: ' . $e->getMessage());
        }
    }
}

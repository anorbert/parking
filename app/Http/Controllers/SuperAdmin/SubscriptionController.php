<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    protected CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index()
    {
        $subscriptions = Subscription::with(['company', 'creator'])->latest()->get();
        return view('superadmin.subscriptions.index', compact('subscriptions'));
    }

    public function activate(string $id)
    {
        try {
            $this->companyService->activateSubscription($id);
            return back()->with('success', 'Subscription activated successfully.');
        } catch (\Exception $e) {
            Log::error('Subscription activation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to activate subscription.');
        }
    }

    public function renew(Request $request, string $companyId)
    {
        try {
            $this->companyService->renewSubscription($companyId, $request->user()->id);
            return back()->with('success', 'Subscription renewed successfully.');
        } catch (\Exception $e) {
            Log::error('Subscription renewal failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to renew subscription.');
        }
    }
}

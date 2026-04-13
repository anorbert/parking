<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyStoreRequest;
use App\Models\Company;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Parking;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Services\CompanyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    protected CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index()
    {
        $companies = Company::withCount(['users', 'zones', 'parkings'])
            ->with('activeSubscription')
            ->latest()
            ->get();

        return view('superadmin.companies.index', compact('companies'));
    }

    public function create()
    {
        $plans = SubscriptionPlan::active()->orderBy('sort_order')->get();
        return view('superadmin.companies.create', compact('plans'));
    }

    public function store(CompanyStoreRequest $request)
    {
        try {
            $result = $this->companyService->register(
                $request->only(['name', 'tin', 'phone', 'email', 'address']),
                [
                    'name'         => $request->admin_name,
                    'email'        => $request->admin_email,
                    'phone_number' => $request->admin_phone,
                ],
                $request->plan_id
            );

            return redirect()->route('superadmin.companies.index')
                ->with('success', "Company '{$result['company']->name}' created. Admin PIN: {$result['pin']}");
        } catch (\Exception $e) {
            Log::error('Company creation failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create company: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $company = Company::withCount(['users', 'zones', 'parkings', 'vehicles'])
            ->with(['subscriptions' => fn($q) => $q->latest()->take(5)])
            ->with(['invoices' => fn($q) => $q->latest()->take(5)])
            ->findOrFail($id);

        $totalRevenue = Parking::where('company_id', $company->id)->sum('bill');

        return view('superadmin.companies.show', compact('company', 'totalRevenue'));
    }

    public function edit(string $id)
    {
        $company = Company::findOrFail($id);
        return view('superadmin.companies.edit', compact('company'));
    }

    public function update(Request $request, string $id)
    {
        $company = Company::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'tin'     => 'nullable|string|max:50|unique:companies,tin,' . $id,
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255|unique:companies,email,' . $id,
            'address' => 'nullable|string|max:500',
            'status'  => 'required|in:Active,Inactive',
        ]);

        $company->update($request->only(['name', 'tin', 'phone', 'email', 'address', 'status']));

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(string $id)
    {
        try {
            $company = Company::findOrFail($id);
            $company->delete();

            return redirect()->route('superadmin.companies.index')
                ->with('success', 'Company deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Company deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Cannot delete company: ' . $e->getMessage());
        }
    }
}

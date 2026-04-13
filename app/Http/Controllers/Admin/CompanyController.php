<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Show company profile page for the logged-in admin.
     */
    public function profile()
    {
        $user = Auth::user();

        if (!$user->company_id) {
            return redirect()->route('admin.companies.create')
                ->with('error', 'You must register your company first.');
        }

        $company = Company::withCount(['users', 'zones', 'vehicles'])
            ->with(['activeSubscription.plan'])
            ->findOrFail($user->company_id);

        return view('admin.companies.profile', compact('company'));
    }

    /**
     * Update company information.
     */
    public function updateCompany(Request $request)
    {
        $user = Auth::user();
        $company = Company::findOrFail($user->company_id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'tin'     => 'nullable|string|max:50|unique:companies,tin,' . $company->id,
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255|unique:companies,email,' . $company->id,
            'address' => 'nullable|string|max:500',
        ]);

        $company->update($request->only(['name', 'tin', 'phone', 'email', 'address']));

        return back()->with('success', 'Company information updated successfully.');
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tin' => 'nullable|string|max:50|unique:companies',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:companies',
            'address' => 'nullable|string|max:500',
        ]);

        $company = Company::create($request->only(['name', 'tin', 'phone', 'email', 'address']) + [
            'status' => 'Active',
        ]);

        // Assign company to admin user
        $user = Auth::user();
        $user->company_id = $company->id;
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Company registered successfully.');
    }
}

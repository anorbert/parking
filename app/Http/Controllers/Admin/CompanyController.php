<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
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

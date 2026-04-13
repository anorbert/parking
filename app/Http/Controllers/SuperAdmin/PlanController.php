<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('sort_order')->get();
        return view('superadmin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('superadmin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'description'   => 'nullable|string|max:500',
            'max_zones'     => 'nullable|integer|min:0',
            'max_slots'     => 'nullable|integer|min:0',
            'max_users'     => 'nullable|integer|min:0',
            'momo_payments'  => 'nullable|boolean',
            'reports_enabled' => 'nullable|boolean',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        SubscriptionPlan::create([
            'name'            => $request->name,
            'description'     => $request->description,
            'price'           => $request->price,
            'duration_days'   => $request->duration_days,
            'max_zones'       => $request->max_zones,
            'max_slots'       => $request->max_slots,
            'max_users'       => $request->max_users,
            'momo_payments'   => $request->boolean('momo_payments'),
            'reports_enabled' => $request->boolean('reports_enabled'),
            'is_active'       => true,
            'sort_order'      => $request->sort_order ?? 0,
        ]);

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('superadmin.plans.edit', compact('plan'));
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'description'   => 'nullable|string|max:500',
            'max_zones'     => 'nullable|integer|min:0',
            'max_slots'     => 'nullable|integer|min:0',
            'max_users'     => 'nullable|integer|min:0',
            'momo_payments'  => 'nullable|boolean',
            'reports_enabled' => 'nullable|boolean',
            'is_active'     => 'nullable|boolean',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        $plan->update([
            'name'            => $request->name,
            'description'     => $request->description,
            'price'           => $request->price,
            'duration_days'   => $request->duration_days,
            'max_zones'       => $request->max_zones,
            'max_slots'       => $request->max_slots,
            'max_users'       => $request->max_users,
            'momo_payments'   => $request->boolean('momo_payments'),
            'reports_enabled' => $request->boolean('reports_enabled'),
            'is_active'       => $request->boolean('is_active'),
            'sort_order'      => $request->sort_order ?? 0,
        ]);

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        if ($plan->subscriptions()->exists()) {
            return back()->with('error', 'Cannot delete plan with active subscriptions.');
        }

        $plan->delete();
        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan deleted successfully.');
    }
}

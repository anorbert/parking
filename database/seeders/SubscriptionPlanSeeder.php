<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'            => 'Starter',
                'description'     => 'Ideal for small parking lots with basic needs.',
                'price'           => 15000,
                'duration_days'   => 30,
                'max_zones'       => 2,
                'max_slots'       => 50,
                'max_users'       => 5,
                'momo_payments'   => false,
                'reports_enabled' => false,
                'is_active'       => true,
                'sort_order'      => 1,
            ],
            [
                'name'            => 'Professional',
                'description'     => 'Perfect for growing businesses with MoMo payments.',
                'price'           => 25000,
                'duration_days'   => 30,
                'max_zones'       => 5,
                'max_slots'       => 200,
                'max_users'       => 15,
                'momo_payments'   => true,
                'reports_enabled' => true,
                'is_active'       => true,
                'sort_order'      => 2,
            ],
            [
                'name'            => 'Enterprise',
                'description'     => 'Unlimited access for large-scale parking operations.',
                'price'           => 50000,
                'duration_days'   => 30,
                'max_zones'       => null,
                'max_slots'       => null,
                'max_users'       => null,
                'momo_payments'   => true,
                'reports_enabled' => true,
                'is_active'       => true,
                'sort_order'      => 3,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}

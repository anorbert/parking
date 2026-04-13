<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\BankSeeder;
use Database\Seeders\SuperAdminSeeder;
use Database\Seeders\SubscriptionPlanSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
            BankSeeder::class,
            SuperAdminSeeder::class,
            SubscriptionPlanSeeder::class,
        ]);
    }
}

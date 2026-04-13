<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'superadmin@parkflow.rw'],
            [
                'name'         => 'Super Admin',
                'phone_number' => '0780000000',
                'password'     => Hash::make('1234'),
                'role_id'      => 1,
                'company_id'   => null,
            ]
        );
    }
}

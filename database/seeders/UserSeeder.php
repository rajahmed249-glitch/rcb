<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::updateOrCreate(
            ['name' => 'Super Admin'],
            ['name_bn' => 'সুপার অ্যাডমিন', 'sort_order' => 1]
        );

        Role::updateOrCreate(
            ['name' => 'Admin'],
            ['name_bn' => 'অ্যাডমিন', 'sort_order' => 2]
        );

        Role::updateOrCreate(
            ['name' => 'Manager'],
            ['name_bn' => 'ম্যানেজার', 'sort_order' => 3]
        );

        Role::updateOrCreate(
            ['name' => 'Editor'],
            ['name_bn' => 'এডিটর', 'sort_order' => 4]
        );

        Role::updateOrCreate(
            ['name' => 'Viewer'],
            ['name_bn' => 'ভিউয়ার', 'sort_order' => 5]
        );

        User::updateOrCreate(
            ['email' => 'rajahmed249@gmail.com'],
            [
                'name' => 'Raj Ahmed',
                'password' => Hash::make('rajahmed2017'),
                'role_id' => $superAdmin->id,
                'is_active' => true,
            ]
        );
    }
}

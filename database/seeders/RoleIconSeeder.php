<?php

namespace Database\Seeders;

use App\Models\RoleIcon;
use Illuminate\Database\Seeder;

class RoleIconSeeder extends Seeder
{
    public function run(): void
    {
        $icons = [
            ['name' => 'Batsman', 'name_bn' => 'ব্যাটসম্যান', 'icon' => 'images/role-icons/batsman.webp', 'sort_order' => 1],
            ['name' => 'Bowler', 'name_bn' => 'বোলার', 'icon' => 'images/role-icons/bowler.webp', 'sort_order' => 2],
            ['name' => 'All-rounder', 'name_bn' => 'অল-রাউন্ডার', 'icon' => 'images/role-icons/allrounder.webp', 'sort_order' => 3],
            ['name' => 'Wicketkeeper', 'name_bn' => 'উইকেটকিপার', 'icon' => 'images/role-icons/wicket-keeper.webp', 'sort_order' => 4],
        ];

        foreach ($icons as $icon) {
            RoleIcon::updateOrCreate(
                ['name' => $icon['name']],
                ['name_bn' => $icon['name_bn'], 'icon' => $icon['icon'], 'sort_order' => $icon['sort_order']]
            );
        }
    }
}

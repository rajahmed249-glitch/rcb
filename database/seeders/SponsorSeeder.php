<?php

namespace Database\Seeders;

use App\Models\Sponsor;
use Illuminate\Database\Seeder;

class SponsorSeeder extends Seeder
{
    public function run(): void
    {
        $sponsors = [
            ['name' => 'RCB Sports Club', 'sort_order' => 1],
            ['name' => 'Patuakhali Cricket Academy', 'sort_order' => 2],
            ['name' => 'Your Brand Here', 'sort_order' => 3],
        ];

        foreach ($sponsors as $sponsor) {
            Sponsor::create($sponsor);
        }
    }
}

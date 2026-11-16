<?php

namespace Database\Seeders;

use App\Models\Sponsor;
use Illuminate\Database\Seeder;

class SponsorSeeder extends Seeder
{
    public function run(): void
    {
        Sponsor::query()->delete();

        $sponsors = [
            ['name' => 'RCB Sports Club', 'name_bn' => 'আরসিবি স্পোর্টস ক্লাব', 'sort_order' => 1],
            ['name' => 'Patuakhali Cricket Academy', 'name_bn' => 'পটুয়াখালী ক্রিকেট একাডেমি', 'sort_order' => 2],
            ['name' => 'Your Brand Here', 'name_bn' => 'আপনার ব্র্যান্ড এখানে', 'sort_order' => 3],
        ];

        foreach ($sponsors as $sponsor) {
            Sponsor::create($sponsor);
        }
    }
}

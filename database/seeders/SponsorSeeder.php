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
            ['name' => 'Youth IT', 'name_bn' => 'ইউথ আইটি', 'logo' => 'uploads/sponsors/youth-it.webp', 'sort_order' => 1],
            ['name' => 'Uthsahrago', 'name_bn' => 'উৎসাহডাঙ্গো', 'logo' => 'uploads/sponsors/uthsahrago.webp', 'sort_order' => 2],
            ['name' => 'Health Labs Ltd.', 'name_bn' => 'হেলথ ল্যাবস লিমিটেড', 'logo' => 'uploads/sponsors/health-labs-ltd.webp', 'sort_order' => 3],
            ['name' => 'Trauma CHS', 'name_bn' => 'ট্রমা সিএইচএস', 'logo' => 'uploads/sponsors/trauma-chs.webp', 'sort_order' => 4],
            ['name' => 'Trauma IMT & MATS', 'name_bn' => 'ট্রমা আইএমটি অ্যান্ড ম্যাটস', 'logo' => 'uploads/sponsors/trauma-imt-mats.webp', 'sort_order' => 5],
            ['name' => 'Trauma Nursing College', 'name_bn' => 'ট্রমা নার্সিং কলেজ', 'logo' => 'uploads/sponsors/trauma-nursing-college.webp', 'sort_order' => 6],
            ['name' => 'Amaar School', 'name_bn' => 'আমার স্কুল', 'logo' => 'uploads/sponsors/amaar-school.webp', 'sort_order' => 7],
        ];

        foreach ($sponsors as $sponsor) {
            Sponsor::create($sponsor);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\CricketMatch;
use App\Models\Tournament;
use Illuminate\Database\Seeder;

class TournamentSeeder extends Seeder
{
    public function run(): void
    {
        if (Tournament::count() > 0) {
            return;
        }

        Tournament::query()->delete();

        $tournaments = [
            ['name' => 'RCB Friendly T10 Series', 'name_bn' => 'আরসিবি ফ্রেন্ডলি টি১০ সিরিজ', 'slug' => 'rcb-friendly-t10-series', 'season' => '2025-26', 'is_active' => true, 'sort_order' => 0],
            ['name' => 'Patuakhali Winter Cup', 'name_bn' => 'পটুয়াখালী উইন্টার কাপ', 'slug' => 'patuakhali-winter-cup', 'season' => '2026', 'is_active' => true, 'sort_order' => 1],
        ];

        foreach ($tournaments as $tournament) {
            Tournament::create($tournament);
        }

        $first = Tournament::orderBy('sort_order')->first();

        if ($first) {
            CricketMatch::query()->update(['tournament_id' => $first->id]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PlayerSeeder::class,
            MatchSeeder::class,
            NewsSeeder::class,
            SponsorSeeder::class,
            TeamSeeder::class,
            GallerySeeder::class,
            TournamentSeeder::class,
            LocationSeeder::class,
            RoleIconSeeder::class,
            UserSeeder::class,
        ]);
    }
}

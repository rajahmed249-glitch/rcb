<?php

namespace Database\Seeders;

use App\Models\CricketMatch;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        if (Team::count() > 0) {
            return;
        }

        Team::query()->delete();

        $teams = [
            ['name' => 'Real Challenger Boys', 'name_bn' => 'রিয়েল চ্যালেঞ্জার বয়েজ', 'slug' => 'real-challenger-boys', 'short_name' => 'RCB', 'logo' => 'images/teams/real-challenger-boys.webp', 'location' => 'Patuakhali, Bangladesh', 'is_rcb' => true, 'sort_order' => 0],
            ['name' => 'Armed Police Battalion', 'name_bn' => 'আর্মড পুলিশ ব্যাটালিয়ন', 'slug' => 'armed-police-battalion', 'short_name' => 'APB', 'logo' => 'images/teams/armed-police-battalion.webp', 'sort_order' => 1],
            ['name' => 'Duronto Sporting Xi', 'name_bn' => 'দুরন্ত স্পোর্টিং একাদশ', 'slug' => 'duronto-sporting-xi', 'short_name' => 'DSX', 'logo' => 'images/teams/duronto-sporting-xi.webp', 'sort_order' => 2],
            ['name' => 'Patuakhali Govt. College', 'name_bn' => 'পটুয়াখালী সরকারি কলেজ', 'slug' => 'patuakhali-govt-college', 'short_name' => 'PGC', 'logo' => 'images/teams/patuakhali-govt-college.webp', 'sort_order' => 3],
            ['name' => 'Dynamic Falcon', 'name_bn' => 'ডাইনামিক ফ্যালকন', 'slug' => 'dynamic-falcon', 'short_name' => 'DF', 'logo' => 'images/teams/dynamic-falcon.webp', 'sort_order' => 4],
            ['name' => 'Durnibar', 'name_bn' => 'দুর্নিবার', 'slug' => 'durnibar', 'short_name' => 'DNB', 'logo' => 'images/teams/durnibar.webp', 'sort_order' => 5],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }

        $rcb = Team::where('slug', 'real-challenger-boys')->first();

        if ($rcb) {
            Player::query()->update(['team_id' => $rcb->id]);
        }

        $byName = Team::pluck('id', 'name');

        CricketMatch::query()->get()->each(function (CricketMatch $match) use ($byName) {
            if ($byName->has($match->opponent_name)) {
                $match->update(['opponent_team_id' => $byName[$match->opponent_name]]);
            }
        });
    }
}

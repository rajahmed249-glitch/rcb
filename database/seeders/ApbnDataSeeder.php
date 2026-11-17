<?php

namespace Database\Seeders;

use App\Models\CricketMatch;
use App\Models\Player;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Database\Seeder;

class ApbnDataSeeder extends Seeder
{
    public function run(): void
    {
        $apbn = Team::firstOrCreate(
            ['name' => 'Armed Police Battalion'],
            [
                'name_bn' => 'আর্মড পুলিশ ব্যাটালিয়ন',
                'short_name' => 'APBN',
                'is_rcb' => false,
                'status' => true,
                'sort_order' => (int) Team::max('sort_order') + 1,
            ]
        );

        $apbnPlayers = [
            'Hafiz - APBN' => ['captain' => true],
            'Sohan - APBN' => ['captain' => false],
            'Anik - APBN' => ['captain' => false],
            'Sabbir - APBN' => ['captain' => false],
            'Mahmudul - APBN' => ['captain' => false],
            'Habib - APBN' => ['captain' => false],
            'Faiyaz - APBN' => ['captain' => false],
            'Nayeem - APBN' => ['captain' => false],
            'Sourav - APBN' => ['captain' => false],
            'Sazid - APBN' => ['captain' => false],
            'Hazrat - APBN' => ['captain' => false],
            'Abid - APBN' => ['captain' => false],
        ];

        foreach ($apbnPlayers as $name => $meta) {
            Player::firstOrCreate(
                ['name' => $name, 'team_id' => $apbn->id],
                [
                    'status' => true,
                    'is_captain' => $meta['captain'],
                    'is_vice_captain' => false,
                    'nationality' => 'Bangladesh',
                ]
            );
        }

        $t2026 = Tournament::firstOrCreate(
            ['name' => '2026'],
            ['slug' => Tournament::uniqueSlug('2026'), 'is_active' => true, 'sort_order' => (int) Tournament::max('sort_order') + 1]
        );

        $t2027 = Tournament::firstOrCreate(
            ['name' => '2027'],
            ['slug' => Tournament::uniqueSlug('2027'), 'is_active' => true, 'sort_order' => (int) Tournament::max('sort_order') + 1]
        );

        $rcbTeam = Team::where('is_rcb', 1)->first();

        if ($rcbTeam) {
            foreach ([$t2026, $t2027] as $tournament) {
                $tournament->teams()->syncWithoutDetaching([$rcbTeam->id, $apbn->id]);
            }
        }

        $match = CricketMatch::firstOrCreate(
            ['match_name' => 'Friendly Cricket Match, January-2027'],
            [
                'match_number' => (int) CricketMatch::max('match_number') + 1,
                'tournament_id' => $t2026->id,
                'opponent_team_id' => $apbn->id,
                'opponent_name' => $apbn->name,
                'opponent_name_bn' => $apbn->name_bn,
                'opponent_short_name' => 'APBN',
                'venue' => 'Shyamoli Shishu Park Maath, Shyamoli, Dhaka',
                'match_date' => '2027-01-01 08:30:00',
                'status' => 'upcoming',
                'format' => 'T15',
            ]
        );

        if ($rcbTeam) {
            $rcbNames = [
                'Raj Ahmed',
                'Fazle Rabbi Nayan',
                'Soloyman Hossain',
                'Mohidul Haq Jony',
                'Ebadul Sheikh',
                'NMS Sadi',
                'Sufiyan Akash',
                'Syed Adib Rahman',
                'Tanver Ahmad',
                'Saiful Islam Shoron',
                'Akash Mollah',
            ];

            $rcbPlayerIds = Player::where('team_id', $rcbTeam->id)
                ->whereIn('name', $rcbNames)
                ->pluck('id')
                ->all();

            if ($rcbPlayerIds !== []) {
                $match->squad()->syncWithoutDetaching($rcbPlayerIds);
            }
        }
    }
}

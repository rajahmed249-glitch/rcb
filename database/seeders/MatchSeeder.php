<?php

namespace Database\Seeders;

use App\Models\CricketMatch;
use Illuminate\Database\Seeder;

class MatchSeeder extends Seeder
{
    public function run(): void
    {
        $matches = [
            [
                'match_number' => 15,
                'opponent_name' => 'Armed Police Battalion',
                'opponent_short_name' => 'APB',
                'venue' => 'Patuakhali Govt. College Ground, Patuakhali',
                'match_date' => '2025-08-26 09:30:00',
                'status' => 'completed',
                'format' => 'T15',
                'rcb_score' => '202-8',
                'rcb_overs' => '15.0',
                'opponent_score' => '178-9',
                'opponent_overs' => '15.0',
                'result_text' => 'REAL CHALLENGER BOYS won by 18 runs',
                'won' => true,
            ],
            [
                'match_number' => 14,
                'opponent_name' => 'Duronto Sporting Xi',
                'opponent_short_name' => 'DSXI',
                'venue' => 'Shyamoli Shishu Park Maath, Shyamoli, Dhaka',
                'match_date' => '2025-07-12 09:30:00',
                'status' => 'completed',
                'format' => 'T15',
                'rcb_score' => '145-6',
                'rcb_overs' => '15.0',
                'opponent_score' => '149-4',
                'opponent_overs' => '14.2',
                'result_text' => 'Duronto Sporting Xi won by 6 wickets',
                'won' => false,
            ],
            [
                'match_number' => 17,
                'opponent_name' => 'Patuakhali Govt. College',
                'opponent_short_name' => 'PGC',
                'venue' => 'Patuakhali Govt. College Ground, Patuakhali',
                'match_date' => '2026-01-24 09:30:00',
                'status' => 'upcoming',
                'format' => 'T15',
            ],
            [
                'match_number' => 18,
                'opponent_name' => 'Dynamic Falcon',
                'opponent_short_name' => 'DF',
                'venue' => 'Shyamoli Shishu Park Maath, Shyamoli, Dhaka',
                'match_date' => '2026-04-10 09:30:00',
                'status' => 'upcoming',
                'format' => 'T15',
            ],
            [
                'match_number' => 16,
                'opponent_name' => 'Duronto Sporting Xi',
                'opponent_short_name' => 'DSXI',
                'venue' => 'Patuakhali Govt. College Ground, Patuakhali',
                'match_date' => '2026-12-23 09:30:00',
                'status' => 'upcoming',
                'format' => 'T15',
            ],
        ];

        foreach ($matches as $match) {
            CricketMatch::create($match);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\CricketMatch;
use App\Models\MatchCommentary;
use App\Models\MatchExtra;
use App\Models\MatchFallOfWicket;
use App\Models\MatchPlayerStat;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Seeder;

class StumpsOpenMatchSeeder extends Seeder
{
    public function run(): void
    {
        $rcbTeam = Team::where('is_rcb', 1)->first();

        if (! $rcbTeam) {
            return;
        }

        $dfTeam = Team::firstOrCreate(
            ['name' => 'Dynamic Falcon'],
            [
                'name_bn' => 'ডাইনামিক ফ্যালকন',
                'short_name' => 'DF',
                'short_name_bn' => 'ডিএফ',
                'is_rcb' => false,
                'status' => true,
                'sort_order' => (int) Team::max('sort_order') + 1,
            ]
        );

        $rcbNewPlayers = [
            'Masum Rana Shamim',
            'Sakibul Islam Remon',
            'Sabbir Hossain Rabby',
            'Safat Khan',
            'Safwan Ahmed Shorif',
            'Siam Hossain',
        ];

        $rcbKnownPlayers = ['Raj Ahmed', 'Fazle Rabbi Nayan', 'Tanver Ahmad'];

        foreach ($rcbNewPlayers as $name) {
            Player::firstOrCreate(
                ['name' => $name, 'team_id' => $rcbTeam->id],
                [
                    'status' => true,
                    'is_captain' => false,
                    'is_vice_captain' => false,
                    'nationality' => 'Bangladesh',
                ]
            );
        }

        $dfPlayers = [
            'Ripon', 'Akash', 'Sakibul 98', 'Robin', 'Alamin2', 'Hakim 333',
            'Dr Rony', 'Amzad', 'Harun', 'Alamin 333', 'Yamin',
        ];

        foreach ($dfPlayers as $name) {
            Player::firstOrCreate(
                ['name' => $name, 'team_id' => $dfTeam->id],
                [
                    'status' => true,
                    'is_captain' => false,
                    'is_vice_captain' => false,
                    'nationality' => 'Bangladesh',
                ]
            );
        }

        $match = CricketMatch::firstOrCreate(
            ['match_name' => 'Open Match'],
            [
                'match_number' => (int) CricketMatch::max('match_number') + 1,
                'opponent_team_id' => $dfTeam->id,
                'opponent_name' => $dfTeam->name,
                'opponent_name_bn' => $dfTeam->name_bn,
                'opponent_short_name' => 'DF',
                'venue' => 'Shyamoli Shishu Park Maath, Shyamoli, Dhaka',
                'match_date' => '2026-10-09 08:00:00',
                'status' => 'completed',
                'format' => 'T20',
                'rcb_score' => '214-7',
                'rcb_overs' => '14.0',
                'rcb_extras' => 45,
                'opponent_score' => '207-5',
                'opponent_overs' => '14.0',
                'opponent_extras' => 25,
                'result_text' => 'Real Challenger Boys won by 7 runs',
                'won' => true,
                'toss_winner_id' => $dfTeam->id,
                'toss_elected' => 'bowl',
                'winner_team_id' => $rcbTeam->id,
                'player_of_match_id' => Player::where('name', 'Safwan Ahmed Shorif')->value('id'),
            ]
        );

        if (! $match->wasRecentlyCreated) {
            return;
        }

        $rcbSquad = array_merge($rcbKnownPlayers, $rcbNewPlayers, [
            'Anamul Haque Nayon', 'Abdur Rahman Eimon', 'Saidul Islam Babul',
        ]);

        $dfSquad = $dfPlayers;

        $rcbIds = Player::where('team_id', $rcbTeam->id)->whereIn('name', $rcbSquad)->pluck('id')->all();
        $dfIds = Player::where('team_id', $dfTeam->id)->whereIn('name', $dfSquad)->pluck('id')->all();

        $match->squad()->syncWithoutDetaching(array_merge($rcbIds, $dfIds));

        $nameToId = fn (array $names, int $teamId) => Player::where('team_id', $teamId)->whereIn('name', $names)->pluck('id', 'name')->all();

        $rcbMap = $nameToId($rcbSquad, $rcbTeam->id);
        $dfMap = $nameToId($dfSquad, $dfTeam->id);

        $rcbBatting = [
            ['Masum Rana Shamim', 21, 13, 0, 3, 'st Alamin 333 b Akash', 'Alamin 333', 'Akash'],
            ['Sakibul Islam Remon', 29, 15, 1, 3, 'run out (Yamin)', 'Yamin', null],
            ['Sabbir Hossain Rabby', 24, 12, 1, 3, 'run out (Ripon)', 'Ripon', null],
            ['Fazle Rabbi Nayan', 5, 5, 1, 0, 'b Sakibul 98', null, 'Sakibul 98'],
            ['Safat Khan', 29, 16, 2, 3, 'c Sakibul 98 b Alamin2', 'Sakibul 98', 'Alamin2'],
            ['Safwan Ahmed Shorif', 17, 10, 1, 2, 'b Sakibul 98', null, 'Sakibul 98'],
            ['Siam Hossain', 7, 4, 0, 1, 'b Sakibul 98', null, 'Sakibul 98'],
            ['Tanver Ahmad', 20, 10, 1, 2, 'not out', null, null],
            ['Raj Ahmed', 17, 9, 0, 2, 'not out', null, null],
        ];

        foreach ($rcbBatting as $i => $r) {
            MatchPlayerStat::create([
                'match_id' => $match->id,
                'side' => 'rcb',
                'stat_type' => 'batting',
                'player_id' => $rcbMap[$r[0]] ?? null,
                'name' => $r[0],
                'sort_order' => $i + 1,
                'runs' => $r[1],
                'balls' => $r[2],
                'fours' => $r[3],
                'sixes' => $r[4],
                'dismissal' => $r[5],
                'fielder_id' => $dfMap[$r[6]] ?? null,
                'bowler_id' => $dfMap[$r[7]] ?? null,
            ]);
        }

        $dfBatting = [
            ['Amzad', 0, 2, 0, 0, 'b Anamul Haque Nayon', null, 'Anamul Haque Nayon'],
            ['Hakim 333', 91, 39, 5, 11, 'not out', null, null],
            ['Harun', 1, 2, 0, 0, 'c Safat Khan b Abdur Rahman Eimon', 'Safat Khan', 'Abdur Rahman Eimon'],
            ['Sakibul 98', 21, 10, 0, 3, 'c Abdur Rahman Eimon b Masum Rana Shamim', 'Abdur Rahman Eimon', 'Masum Rana Shamim'],
            ['Alamin 333', 25, 17, 1, 3, 'b Safwan Ahmed Shorif', null, 'Safwan Ahmed Shorif'],
            ['Yamin', 44, 15, 0, 7, 'b Safwan Ahmed Shorif', null, 'Safwan Ahmed Shorif'],
            ['Dr Rony', 0, 1, 0, 0, 'not out', null, null],
        ];

        foreach ($dfBatting as $i => $r) {
            MatchPlayerStat::create([
                'match_id' => $match->id,
                'side' => 'opponent',
                'stat_type' => 'batting',
                'player_id' => $dfMap[$r[0]] ?? null,
                'name' => $r[0],
                'sort_order' => $i + 1,
                'runs' => $r[1],
                'balls' => $r[2],
                'fours' => $r[3],
                'sixes' => $r[4],
                'dismissal' => $r[5],
                'fielder_id' => $rcbMap[$r[6]] ?? null,
                'bowler_id' => $rcbMap[$r[7]] ?? null,
            ]);
        }

        $dfBowling = [
            ['Ripon', 3, 0, 53, 0, 2, 4],
            ['Akash', 3, 0, 37, 1, 11, 1],
            ['Sakibul 98', 3, 0, 19, 3, 5, 1],
            ['Robin', 1, 0, 25, 0, 6, 1],
            ['Alamin2', 1, 0, 34, 1, 1, 3],
            ['Hakim 333', 2, 0, 28, 0, 5, 0],
            ['Dr Rony', 1, 0, 13, 0, 0, 0],
        ];

        foreach ($dfBowling as $i => $r) {
            MatchPlayerStat::create([
                'match_id' => $match->id,
                'side' => 'rcb',
                'stat_type' => 'bowling',
                'player_id' => $dfMap[$r[0]] ?? null,
                'name' => $r[0],
                'sort_order' => $i + 1,
                'overs' => $r[1] . '.0',
                'balls' => $r[1] * 6,
                'maidens' => $r[2],
                'runs' => $r[3],
                'wickets' => $r[4],
                'wides' => $r[5],
                'noballs' => $r[6],
            ]);
        }

        $rcbBowling = [
            ['Anamul Haque Nayon', 2, 0, 27, 1, 4, 0],
            ['Abdur Rahman Eimon', 3, 0, 33, 1, 4, 0],
            ['Safat Khan', 2, 0, 33, 0, 3, 2],
            ['Masum Rana Shamim', 3, 0, 51, 1, 3, 0],
            ['Safwan Ahmed Shorif', 3, 0, 31, 2, 3, 0],
            ['Saidul Islam Babul', 1, 0, 28, 0, 2, 0],
        ];

        foreach ($rcbBowling as $i => $r) {
            MatchPlayerStat::create([
                'match_id' => $match->id,
                'side' => 'opponent',
                'stat_type' => 'bowling',
                'player_id' => $rcbMap[$r[0]] ?? null,
                'name' => $r[0],
                'sort_order' => $i + 1,
                'overs' => $r[1] . '.0',
                'balls' => $r[1] * 6,
                'maidens' => $r[2],
                'runs' => $r[3],
                'wickets' => $r[4],
                'wides' => $r[5],
                'noballs' => $r[6],
            ]);
        }

        $rcbFow = [
            ['Masum Rana Shamim', 51, 1, '3.2'],
            ['Sakibul Islam Remon', 63, 2, '4.1'],
            ['Sabbir Hossain Rabby', 104, 3, '6.3'],
            ['Fazle Rabbi Nayan', 105, 4, '6.5'],
            ['Safat Khan', 162, 5, '9.5'],
            ['Safwan Ahmed Shorif', 172, 6, '10.4'],
            ['Siam Hossain', 173, 7, '11.0'],
        ];

        foreach ($rcbFow as $i => $f) {
            MatchFallOfWicket::create([
                'match_id' => $match->id,
                'side' => 'rcb',
                'player_id' => $rcbMap[$f[0]] ?? null,
                'name' => $f[0],
                'score' => (string) $f[1],
                'wicket_no' => $f[2],
                'over' => $f[3],
                'sort_order' => $i + 1,
            ]);
        }

        $dfFow = [
            ['Amzad', 2, 1, '0.3'],
            ['Harun', 11, 2, '1.1'],
            ['Sakibul 98', 65, 3, '4.2'],
            ['Alamin 333', 118, 4, '9.1'],
            ['Yamin', 179, 5, '12.5'],
        ];

        foreach ($dfFow as $i => $f) {
            MatchFallOfWicket::create([
                'match_id' => $match->id,
                'side' => 'opponent',
                'player_id' => $dfMap[$f[0]] ?? null,
                'name' => $f[0],
                'score' => (string) $f[1],
                'wicket_no' => $f[2],
                'over' => $f[3],
                'sort_order' => $i + 1,
            ]);
        }

        MatchExtra::create([
            'match_id' => $match->id,
            'side' => 'rcb',
            'byes' => 2,
            'leg_byes' => 3,
            'wides' => 30,
            'no_balls' => 10,
            'total' => 45,
        ]);

        MatchExtra::create([
            'match_id' => $match->id,
            'side' => 'opponent',
            'byes' => 2,
            'leg_byes' => 2,
            'wides' => 19,
            'no_balls' => 2,
            'total' => 25,
        ]);

        $rcbCommentary = [
            ['24-0', 'Wd 6 1 1NB 6 NB WD 0 0 6', 'Explosive start as Masum Rana Shamim and Sakibul Islam Remon collected 24 off Ripon.',
                'Ripon'],
            ['40-0', '4 1 0 1+Wd 0 Wd 6 Wd 1', 'Masum Rana Shamim kept finding gaps as Akash leaked 16 more.',
                'Akash'],
            ['51-0', '1 1NB 6 1 0 1 0', 'Eleven came off Ripon with Masum Rana Shamim launching a six.',
                'Ripon'],
            ['62-1', '0 W 1 6 1 1+Wd 1', 'Sakibul Islam Remon fell for 29 in an 11-run Akash over.',
                'Akash'],
            ['76-2', '0 N W 0 6 WD 0 WD 1L 4', 'Sakibul 98 bowled a scrappy 14-run over as Sabbir Hossain Rabby settled in.',
                'Sakibul 98'],
            ['101-2', 'N 6 1 1 6 4 5+Wd 0', 'Robin went for 25 with Sabbir Hossain Rabby and Fazle Rabbi Nayan clearing the ropes.',
                'Robin'],
            ['105-4', 'WD 1B 1L W 1 W 0', 'A wild over: two wickets and only 4 runs off Sakibul 98.',
                'Sakibul 98'],
            ['115-4', '0 WD WD 0 0 WD 0 1+Wd 0 4N 0', 'Akash again, 10 runs as Safat Khan began to rebuild.',
                'Akash'],
            ['135-4', '0 4 6 1L 6N 1B 1', 'Twenty off Ripon including a huge Safat Khan six.',
                'Ripon'],
            ['169-5', '1N 6N 1N 6 0 6 4 W WD 6', 'Thirty-four carnage off Alamin2 with Safwan Ahmed Shorif and Siam Hossain swinging hard.',
                'Alamin2'],
            ['173-7', '1+Wd 0 1 0 W 1 W', 'Siam Hossain departed as Sakibul 98 suffocated the innings for 4 runs.',
                'Sakibul 98'],
            ['177-7', '0 1 1 1 0 1', 'Tanver Ahmad and Raj Ahmed calmly rotated strike against Hakim 333.',
                'Hakim 333'],
            ['190-7', '0 6 1 0 0 6', 'Tanver Ahmad lofted two sixes in a 13-run Dr Rony over.',
                'Dr Rony'],
            ['214-7', '6 1 4+Wd 4 6 1 1', 'Tanver Ahmad finished unbeaten on 20 with a 24-run flourish off Hakim 333.',
                'Hakim 333'],
        ];

        foreach ($rcbCommentary as $i => $c) {
            MatchCommentary::create([
                'match_id' => $match->id,
                'side' => 'rcb',
                'over_number' => (string) ($i + 1),
                'score_at' => $c[0],
                'ball_number' => '',
                'ball_per_run' => $c[1],
                'description' => $c[2],
                'sort_order' => $i + 1,
            ]);
        }

        $dfCommentary = [
            ['11-1', '1B 1L W WD 1 WD 6 0', 'Anamul Haque Nayon struck first ball of his spell as Amzad fell for a duck.',
                'Anamul Haque Nayon'],
            ['18-2', 'W 0 6 0 1 0', 'Abdur Rahman Eimon removed Harun, keeping the chase quiet.',
                'Abdur Rahman Eimon'],
            ['36-2', '1 6 WD 0 WD 4 4 1', 'Hakim 333 launched his first six as 18 came off Anamul Haque Nayon.',
                'Anamul Haque Nayon'],
            ['58-2', '0 6 1 6 0 1N WD 6', 'Hakim 333 unloaded three sixes in a 22-run blowout of Safat Khan.',
                'Safat Khan'],
            ['72-3', 'WD 6 W 0 0 WD 6 0', 'Alamin 333 joined the fireworks, though Masum Rana Shamim took a wicket.',
                'Masum Rana Shamim'],
            ['84-3', '6 WD 0 1L 0 4 0', 'Twelve off Abdur Rahman Eimon with Hakim 333 driving and tossing.',
                'Abdur Rahman Eimon'],
            ['97-3', '0 0 4 WD 1 6 1', 'Hakim 333 raced to 45 as 13 came off Masum Rana Shamim.',
                'Masum Rana Shamim'],
            ['106-3', '0 0 6 WD WD 1 0 0', 'Safwan Ahmed Shorif applied the brake, just 9 runs.',
                'Safwan Ahmed Shorif'],
            ['118-3', '1 1N WD 0 WD 1B 6 0 0', 'Hakim 333 reached his fifty as 12 came off Safat Khan.',
                'Safat Khan'],
            ['121-4', 'W 1 1 0 0 1', 'Yamin fell for 44 shortly after in another tidy Safwan Ahmed Shorif over.',
                'Safwan Ahmed Shorif'],
            ['145-4', '6 0 6 6 6 0', 'Yamin unloaded three sixes in a 24-run Masum Rana Shamim over.',
                'Masum Rana Shamim'],
            ['160-4', 'WD 0 0 6 WD WD 6 0 0', 'Yamin kept swinging as Abdur Rahman Eimon conceded 15.',
                'Abdur Rahman Eimon'],
            ['179-5', '0 6 6 6 WD W 0', 'Safwan Ahmed Shorif bowled Yamin in a 19-run over.',
                'Safwan Ahmed Shorif'],
            ['207-5', '6 WD 4 6 WD 0 6 4', 'Hakim 333 finished 91 not out, but 28 off Saidul Islam Babul fell 7 short.',
                'Saidul Islam Babul'],
        ];

        foreach ($dfCommentary as $i => $c) {
            MatchCommentary::create([
                'match_id' => $match->id,
                'side' => 'opponent',
                'over_number' => (string) ($i + 1),
                'score_at' => $c[0],
                'ball_number' => '',
                'ball_per_run' => $c[1],
                'description' => $c[2],
                'sort_order' => $i + 1,
            ]);
        }

        Player::whereIn('id', array_merge($rcbIds, $dfIds))->increment('matches_played');

        Player::whereIn('id', $rcbIds)
            ->selectRaw('id')
            ->get()
            ->each(function ($p) use ($match) {
                $runs = (int) $match->stats()->where('side', 'rcb')->where('stat_type', 'batting')->where('player_id', $p->id)->sum('runs');
                $wkts = (int) $match->stats()->where('side', 'opponent')->where('stat_type', 'bowling')->where('player_id', $p->id)->sum('wickets');
                if ($runs > 0) {
                    Player::where('id', $p->id)->increment('runs', $runs);
                }
                if ($wkts > 0) {
                    Player::where('id', $p->id)->increment('wickets', $wkts);
                }
            });

        Player::whereIn('id', $dfIds)->get()->each(function ($p) use ($match) {
            $runs = (int) $match->stats()->where('side', 'opponent')->where('stat_type', 'batting')->where('player_id', $p->id)->sum('runs');
            $wkts = (int) $match->stats()->where('side', 'rcb')->where('stat_type', 'bowling')->where('player_id', $p->id)->sum('wickets');
            if ($runs > 0) {
                Player::where('id', $p->id)->increment('runs', $runs);
            }
            if ($wkts > 0) {
                Player::where('id', $p->id)->increment('wickets', $wkts);
            }
        });
    }
}

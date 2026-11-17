<?php

namespace App\Http\Controllers;

use App\Models\CricketMatch;
use App\Models\Player;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = CricketMatch::query();

        if ($status === 'completed') {
            $query->completed();
        } elseif ($status === 'upcoming') {
            $query->upcoming();
        } else {
            $query->orderByDesc('match_date');
        }

        return view('matches.index', [
            'status' => $status,
            'matches' => $query->paginate(10)->withQueryString(),
        ]);
    }

    public function show(CricketMatch $match)
    {
        return view('matches.show', [
            'match' => $match,
            'scorecard' => $this->buildScorecard($match),
        ]);
    }

    private function parseScore(?string $score): array
    {
        if (!$score || $score === '-') {
            return [0, 0];
        }

        $parts = explode('-', $score, 2);

        return [(int) $parts[0], (int) ($parts[1] ?? 0)];
    }

    private function parseOvers(?string $overs): float
    {
        if (!$overs || $overs === '-') {
            return 0.0;
        }

        $parts = explode('.', $overs, 2);

        return (float) $parts[0] + ((int) ($parts[1] ?? 0)) / 6;
    }

    private function formatOvers(float $overs): string
    {
        $whole = (int) floor($overs);
        $balls = (int) round(($overs - $whole) * 6);

        if ($balls >= 6) {
            $whole += 1;
            $balls -= 6;
        }

        return $whole . '.' . $balls;
    }

    private function buildScorecard(CricketMatch $match): array
    {
        if ($match->status !== 'completed') {
            return ['rcb' => null, 'opponent' => null];
        }

        [$rcbRuns, $rcbWkts] = $this->parseScore($match->rcb_score);
        [$oppRuns, $oppWkts] = $this->parseScore($match->opponent_score);

        $squadIds = $match->squad()->pluck('players.id')->all();

        $rcbPlayers = $squadIds
            ? Player::whereIn('id', $squadIds)
                ->orderByDesc('is_captain')
                ->orderByDesc('is_vice_captain')
                ->orderBy('id')
                ->get()
            : Player::orderByDesc('is_captain')
                ->orderByDesc('is_vice_captain')
                ->orderBy('id')
                ->get();

        $opponentNames = [
            'Tanvir Hasan', 'Mehedi Hasan', 'Rakibul Islam', 'Sabbir Ahmed',
            'Naeem Sheikh', 'Ariful Haque', 'Shakil Khan', 'Roni Chowdhury',
            'Imran Hossain', 'Jubayer Rahman', 'Sajid Khan',
        ];

        $rcbBowlerNames = $rcbPlayers->filter(fn ($p) => in_array($p->role, ['Bowler', 'All-rounder']))
            ->concat($rcbPlayers)
            ->map(fn ($p) => $p->name)
            ->unique()
            ->values()
            ->all();

        $stored = $match->stats()->with('player')->get();

        if ($stored->isNotEmpty()) {
            return [
                'rcb' => $this->inningsFromStats(
                    $stored->where('side', 'rcb')->where('stat_type', 'batting')->values(),
                    $stored->where('side', 'rcb')->where('stat_type', 'bowling')->values(),
                    $match->rcb_score,
                    $match->rcb_overs,
                    $match->rcb_extras
                ),
                'opponent' => $this->inningsFromStats(
                    $stored->where('side', 'opponent')->where('stat_type', 'batting')->values(),
                    $stored->where('side', 'opponent')->where('stat_type', 'bowling')->values(),
                    $match->opponent_score,
                    $match->opponent_overs,
                    $match->opponent_extras
                ),
                'squads' => [
                    'rcb' => $this->buildSquad($rcbPlayers->take(11)->values(), $match->id, 'rcb'),
                    'opponent' => $this->buildSquad(null, $match->id, 'opp', $opponentNames),
                ],
            ];
        }

        return [
            'rcb' => $this->buildInnings(
                $rcbRuns, $rcbWkts, $match->rcb_overs,
                $rcbPlayers, $opponentNames,
                $oppRuns, $oppWkts,
                $match->id, 'rcb'
            ),
            'opponent' => $this->buildInnings(
                $oppRuns, $oppWkts, $match->opponent_overs,
                null, $rcbBowlerNames,
                $rcbRuns, $rcbWkts,
                $match->id, 'opp',
                $rcbPlayers->pluck('id', 'name')->all()
            ),
            'squads' => [
                'rcb' => $this->buildSquad($rcbPlayers->take(11)->values(), $match->id, 'rcb'),
                'opponent' => $this->buildSquad(null, $match->id, 'opp', $opponentNames),
            ],
        ];
    }

    private function inningsFromStats($batRows, $bowlRows, ?string $score, ?string $overs, ?int $extras): array
    {
        [$teamRuns, $teamWickets] = $this->parseScore($score);

        $batting = [];
        $fow = [];
        $fowScore = 0;
        $cumBalls = 0;

        foreach ($batRows as $row) {
            $name = $row->player?->name ?: $row->name;
            $runs = (int) $row->runs;
            $balls = (int) $row->balls;
            $dismissal = trim((string) $row->dismissal) ?: 'not out';
            $isOut = stripos($dismissal, 'not out') === false && stripos($dismissal, 'did not bat') === false;

            $batting[] = [
                'id' => $row->player_id,
                'name' => $name,
                'photo' => $row->player?->photo_url,
                'runs' => $runs,
                'balls' => $balls,
                'fours' => (int) $row->fours,
                'sixes' => (int) $row->sixes,
                'sr' => $balls > 0 ? number_format($runs * 100 / $balls, 2) : '0.00',
                'dismissal' => $dismissal,
            ];

            if ($isOut) {
                $fowScore += $runs;
                $fow[] = [
                    'id' => $row->player_id,
                    'name' => $name,
                    'score' => $fowScore . '-' . count($fow),
                    'over' => $this->formatOvers($cumBalls / 6),
                ];
            }

            $cumBalls += $balls;
        }

        $bowling = $bowlRows->map(function ($row) {
            $oversFloat = $this->parseOvers($row->overs);

            return [
                'id' => $row->player_id,
                'name' => $row->player?->name ?: $row->name,
                'overs' => $row->overs,
                'maidens' => (int) $row->maidens,
                'runs' => (int) $row->runs,
                'wickets' => (int) $row->wickets,
                'wides' => (int) $row->wides,
                'noballs' => (int) $row->noballs,
                'econ' => $oversFloat > 0 ? number_format($row->runs / $oversFloat, 2) : '0.00',
            ];
        })->values()->all();

        return [
            'batting' => $batting,
            'bowling' => $bowling,
            'fow' => $fow,
            'extras' => (int) ($extras ?? 0),
            'total' => $teamRuns,
            'wickets' => $teamWickets,
            'overs' => $overs,
        ];
    }

    private function buildInnings(
        int $teamRuns,
        int $teamWickets,
        ?string $teamOvers,
        $batterPool,
        array $bowlerNames,
        int $oppRuns,
        int $oppWickets,
        int $matchId,
        string $side,
        array $bowlerIds = []
    ): array {
        mt_srand($matchId * 131 + ($side === 'rcb' ? 7 : 13));

        $extras = mt_rand(8, 16);
        $batRuns = max(0, $teamRuns - $extras);
        $battedCount = min(11, max(7, $teamWickets + 2));

        if ($batterPool && $batterPool->count() > 0) {
            $batters = $batterPool->take($battedCount)->values()->map(fn ($p) => (object) [
                'id' => $p->id,
                'name' => $p->name,
                'photo' => $p->photo_url,
            ]);
        } else {
            $batters = collect(range(0, $battedCount - 1))->map(fn ($i) => (object) [
                'id' => null,
                'name' => $bowlerNames[$i % count($bowlerNames)],
                'photo' => null,
            ]);
        }

        $weights = [34, 27, 21, 17, 13, 10, 8, 6, 5, 4, 3];
        $runsList = [];
        $allocated = 0;

        foreach ($batters->values() as $i => $batter) {
            if ($i === $battedCount - 1) {
                $runsList[$i] = max(0, $batRuns - $allocated);
            } else {
                $runsList[$i] = (int) floor($batRuns * $weights[$i] / array_sum(array_slice($weights, 0, $battedCount)));
                $allocated += $runsList[$i];
            }
        }

        $dismissalTypes = ['bowled', 'caught', 'lbw', 'runout', 'stumped', 'c&b'];
        $batting = [];
        $cumBalls = 0;
        $fow = [];
        $fowScore = 0;

        foreach ($batters->values() as $i => $batter) {
            $runs = $runsList[$i];
            $isOut = $i < $teamWickets;

            $sr = mt_rand(85, 160);
            $balls = $runs > 0 ? max(1, (int) round($runs * 100 / $sr)) : mt_rand(2, 12);

            $fours = (int) floor($runs / 9);
            $sixes = (int) floor($runs / 22);
            while (4 * $fours + 6 * $sixes > $runs && ($fours > 0 || $sixes > 0)) {
                if ($fours > 0 && 4 * $fours > $runs) {
                    $fours--;
                } elseif ($sixes > 0) {
                    $sixes--;
                } else {
                    $fours--;
                }
            }

            $dismissal = 'not out';
            if ($isOut) {
                $type = $dismissalTypes[mt_rand(0, count($dismissalTypes) - 1)];
                $bowler = $bowlerNames[mt_rand(0, count($bowlerNames) - 1)];
                $fielder = $bowlerNames[mt_rand(0, count($bowlerNames) - 1)];

                $dismissal = match ($type) {
                    'bowled' => 'b ' . $bowler,
                    'caught' => 'c ' . $fielder . ' b ' . $bowler,
                    'lbw' => 'lbw b ' . $bowler,
                    'runout' => 'run out (' . $fielder . ')',
                    'stumped' => 'st ' . $fielder . ' b ' . $bowler,
                    'c&b' => 'c & b ' . $bowler,
                    default => 'b ' . $bowler,
                };

                $fowScore += $runs;
                $fow[] = [
                    'id' => $batter->id,
                    'name' => $batter->name,
                    'score' => $fowScore . '-' . count($fow),
                    'over' => $this->formatOvers($cumBalls / 6),
                ];
            }

            $cumBalls += $balls;

            $batting[] = [
                'id' => $batter->id,
                'name' => $batter->name,
                'photo' => $batter->photo,
                'runs' => $runs,
                'balls' => $balls,
                'fours' => $fours,
                'sixes' => $sixes,
                'sr' => $balls > 0 ? number_format($runs * 100 / $balls, 2) : '0.00',
                'dismissal' => $dismissal,
            ];
        }

        $totalOvers = $this->parseOvers($teamOvers);
        $bowlerCount = min(5, max(3, (int) floor($totalOvers)));
        $baseOvers = $totalOvers > 0 ? (int) floor($totalOvers / $bowlerCount) : 0;
        $remOvers = $totalOvers - $baseOvers * $bowlerCount;

        $wicketsLeft = $oppWickets;
        $runsLeft = $oppRuns;
        $bowling = [];

        for ($i = 0; $i < $bowlerCount; $i++) {
            $isLast = ($i === $bowlerCount - 1);

            $bowlerOvers = $baseOvers + ($i === 0 ? $remOvers : 0);
            if ($isLast || $bowlerOvers < 1) {
                $bowlerOvers = max(1, $bowlerOvers);
            }

            if ($isLast) {
                $bowlerRuns = max(0, $runsLeft);
                $bowlerWickets = min($wicketsLeft, mt_rand(0, 2));
            } else {
                $share = 1 / max(1, $bowlerCount - $i);
                $bowlerRuns = min($runsLeft, (int) round($oppRuns * $share * (mt_rand(85, 115) / 100)));
                $bowlerWickets = min($wicketsLeft, mt_rand(0, 2));
            }

            $wicketsLeft -= $bowlerWickets;
            $runsLeft -= $bowlerRuns;

            $maidens = ($bowlerOvers >= 2 && $bowlerRuns <= $bowlerOvers && mt_rand(0, 1)) ? 1 : 0;

            $bowlerName = $bowlerNames[$i % count($bowlerNames)];

            $bowling[] = [
                'id' => $bowlerIds[$bowlerName] ?? null,
                'name' => $bowlerName,
                'overs' => $this->formatOvers($bowlerOvers),
                'maidens' => $maidens,
                'runs' => $bowlerRuns,
                'wickets' => $bowlerWickets,
                'wides' => mt_rand(0, 3),
                'noballs' => mt_rand(0, 2),
                'econ' => $bowlerOvers > 0 ? number_format($bowlerRuns / $bowlerOvers, 2) : '0.00',
            ];
        }

        return [
            'batting' => $batting,
            'bowling' => $bowling,
            'fow' => $fow,
            'extras' => $extras,
            'total' => $teamRuns,
            'wickets' => $teamWickets,
            'overs' => $teamOvers,
        ];
    }

    private function buildSquad($players, int $matchId, string $side, array $names = []): array
    {
        mt_srand($matchId * 197 + ($side === 'rcb' ? 11 : 23));

        $squad = [];

        if ($players && $players->count() > 0) {
            foreach ($players as $p) {
                $matches = (int) $p->matches_played;
                $runs = (int) $p->runs;
                $wickets = (int) $p->wickets;
                $sr = mt_rand(95, 150);
                $econ = mt_rand(50, 90) / 10;

                $squad[] = [
                    'id' => $p->id,
                    'name' => $p->name,
                    'photo' => $p->photo_url,
                    'matches' => $matches,
                    'innings' => $matches,
                    'runs' => $runs,
                    'avg' => $matches > 0 ? round($runs / $matches, 2) : 0,
                    'hs' => $runs > 0 ? max(12, (int) round($runs * 0.45)) : 0,
                    'bat_sr' => $sr,
                    'fifties' => min(3, (int) floor($runs / 55)),
                    'hundreds' => $runs >= 100 ? 1 : 0,
                    'wickets' => $wickets,
                    'economy' => number_format($econ, 2),
                ];
            }
        } else {
            foreach (array_slice($names, 0, 11) as $name) {
                $matches = mt_rand(3, 14);
                $runs = mt_rand(40, 380);
                $wickets = mt_rand(0, 18);
                $econ = mt_rand(50, 90) / 10;

                $squad[] = [
                    'id' => null,
                    'name' => $name,
                    'photo' => null,
                    'matches' => $matches,
                    'innings' => $matches,
                    'runs' => $runs,
                    'avg' => $matches > 0 ? round($runs / $matches, 2) : 0,
                    'hs' => $runs > 0 ? max(12, (int) round($runs * 0.45)) : 0,
                    'bat_sr' => mt_rand(95, 150),
                    'fifties' => min(3, (int) floor($runs / 55)),
                    'hundreds' => $runs >= 100 ? 1 : 0,
                    'wickets' => $wickets,
                    'economy' => number_format($econ, 2),
                ];
            }
        }

        return $squad;
    }
}

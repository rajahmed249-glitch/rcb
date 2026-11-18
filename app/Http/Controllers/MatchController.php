<?php

namespace App\Http\Controllers;

use App\Models\CricketMatch;
use App\Models\Player;
use App\Models\Team;
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
            $query->orderByDesc('match_number')->orderByDesc('match_date');
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

    private function buildScorecard(CricketMatch $match): array
    {
        $rcbTeam = Team::where('is_rcb', 1)->first();
        $squadPlayers = $match->squad()->orderBy('players.id')->get();
        $stored = $match->stats()->get();

        $innings = [];
        foreach (['rcb', 'opponent'] as $side) {
            $sideBat = $stored->where('side', $side)->where('stat_type', 'batting')->values();
            $sideBowl = $stored->where('side', $side)->where('stat_type', 'bowling')->values();

            if ($sideBat->isEmpty() && $sideBowl->isEmpty()) {
                $innings[$side] = null;
                continue;
            }

            $innings[$side] = $this->inningsFromStats(
                $sideBat,
                $sideBowl,
                $side === 'rcb' ? $match->rcb_score : $match->opponent_score,
                $side === 'rcb' ? $match->rcb_overs : $match->opponent_overs,
                $side === 'rcb' ? $match->rcb_extras : $match->opponent_extras,
                $match->matchExtras->firstWhere('side', $side),
                $match->fow->where('side', $side)->sortBy('sort_order')->values()
            );
        }

        $squads = [
            'rcb' => $this->buildSquad(
                $squadPlayers->filter(fn ($p) => $rcbTeam && (int) $p->team_id === (int) $rcbTeam->id)->values()
            ),
            'opponent' => $this->buildSquad(
                $squadPlayers->filter(fn ($p) => !$rcbTeam || (int) $p->team_id !== (int) $rcbTeam->id)->values()
            ),
        ];

        return [
            'rcb' => $innings['rcb'],
            'opponent' => $innings['opponent'],
            'squads' => $squads,
            'commentary' => $match->commentary->sortBy('sort_order')->values()->map(fn ($c) => [
                'over' => (string) $c->over_number,
                'score' => (string) $c->score_at,
                'ball' => (string) $c->ball_number,
                'runs' => (string) $c->ball_per_run,
                'description' => (string) $c->description,
            ])->all(),
            'flags' => [
                'info' => trim((string) $match->match_info) !== '',
                'result' => $match->winner_team_id !== null
                    || filled($match->result_text)
                    || (filled($match->rcb_score) && filled($match->opponent_score)),
                'scorecard' => $stored->isNotEmpty(),
                'squad' => $squadPlayers->isNotEmpty(),
                'commentary' => $match->commentary()->exists(),
            ],
        ];
    }

    private function inningsFromStats($batRows, $bowlRows, ?string $score, ?string $overs, ?int $extrasColumn, $extrasRow, $fowRows): array
    {
        [$teamRuns, $teamWickets] = $this->parseScore($score);

        $batting = [];

        foreach ($batRows as $row) {
            $name = $row->player?->name ?: $row->name;
            $runs = (int) $row->runs;
            $balls = (int) $row->balls;

            $batting[] = [
                'id' => $row->player_id,
                'name' => $name,
                'photo' => $row->player?->photo_url,
                'runs' => $runs,
                'balls' => $balls,
                'fours' => (int) $row->fours,
                'sixes' => (int) $row->sixes,
                'sr' => $balls > 0 ? number_format($runs * 100 / $balls, 2) : '0.00',
                'dismissal' => trim((string) $row->dismissal) ?: 'not out',
            ];
        }

        $bowling = $bowlRows->map(function ($row) {
            $oversFloat = $this->parseOversText($row->overs);

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

        $extras = [
            'byes' => (int) ($extrasRow?->byes ?? 0),
            'leg_byes' => (int) ($extrasRow?->leg_byes ?? 0),
            'wides' => (int) ($extrasRow?->wides ?? 0),
            'no_balls' => (int) ($extrasRow?->no_balls ?? 0),
            'total' => 0,
        ];

        $extras['total'] = (int) ($extrasRow?->total ?? 0) > 0
            ? (int) $extrasRow->total
            : $extras['byes'] + $extras['leg_byes'] + $extras['wides'] + $extras['no_balls'];

        if ($extras['total'] <= 0) {
            $extras['total'] = (int) ($extrasColumn ?? 0);
        }

        $fow = $fowRows->map(function ($f) {
            $name = $f->player?->name ?: (string) $f->name;
            $scoreTxt = trim(trim((string) $f->score) . '-' . trim((string) $f->wicket_no), '-');

            return [
                'id' => $f->player_id,
                'name' => $name,
                'score' => $scoreTxt !== '' ? $scoreTxt : '-',
                'over' => (string) ($f->over ?? ''),
            ];
        })->values()->all();

        return [
            'batting' => $batting,
            'bowling' => $bowling,
            'fow' => $fow,
            'extras' => $extras['total'],
            'extras_breakdown' => $extras,
            'total' => $teamRuns,
            'wickets' => $teamWickets,
            'overs' => $overs,
        ];
    }

    private function parseOversText(?string $overs): float
    {
        if (!$overs || $overs === '-') {
            return 0.0;
        }

        $parts = explode('.', $overs, 2);

        return (float) $parts[0] + ((int) ($parts[1] ?? 0)) / 6;
    }

    private function buildSquad($players): array
    {
        return $players->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->display_name,
            'photo' => $p->photo_url,
            'matches' => (int) $p->matches_played,
            'innings' => (int) $p->matches_played,
            'runs' => (int) $p->runs,
            'avg' => $p->matches_played > 0 ? round($p->runs / $p->matches_played, 2) : '-',
            'hs' => '-',
            'bat_sr' => '-',
            'fifties' => '-',
            'hundreds' => '-',
            'wickets' => (int) $p->wickets,
            'economy' => '-',
        ])->all();
    }
}

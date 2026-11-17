<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CricketMatch;
use App\Models\MatchCommentary;
use App\Models\MatchFallOfWicket;
use App\Models\MatchPlayerStat;
use App\Models\Player;
use Illuminate\Http\Request;

class ScoreboardController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = (string) $request->get('status', '');
        $limit = in_array((int) $request->get('limit', 10), [10, 25, 50, 100]) ? (int) $request->get('limit', 10) : 10;

        $query = CricketMatch::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('opponent_name', 'like', "%{$search}%")
                    ->orWhere('opponent_name_bn', 'like', "%{$search}%")
                    ->orWhere('result_text', 'like', "%{$search}%");
            });
        }

        if (in_array($status, ['upcoming', 'completed', 'live', 'abandoned'])) {
            $query->where('status', $status);
        }

        $matches = $query->orderByDesc('match_number')
            ->orderByDesc('match_date')
            ->paginate($limit)
            ->appends($request->query());

        return view('admin.scoreboards.index', [
            'matches' => $matches,
            'filterSearch' => $search,
            'filterStatus' => $status,
            'filterLimit' => $limit,
        ]);
    }

    public function create()
    {
        $matches = CricketMatch::with('tournament')
            ->orderByDesc('match_number')
            ->orderByDesc('match_date')
            ->get()
            ->map(function ($match) {
                $name = $match->match_name ?: ('RCB vs ' . $match->display_opponent_name);

                return [
                    'id' => $match->id,
                    'label' => $name . ' (' . $match->match_date->format('d M Y') . ')',
                ];
            })
            ->values();

        return view('admin.scoreboards.create', ['matches' => $matches]);
    }

    public function edit(Request $request, CricketMatch $match)
    {
        $team = $request->query('team');
        $side = $team === 'opponent' ? 'opponent' : ($team === 'summary' ? 'summary' : 'rcb');

        $stats = $match->stats()->where('side', $side)->get()->groupBy(fn ($s) => $s->stat_type);

        return view('admin.scoreboards.form', [
            'match' => $match,
            'side' => $side,
            'players' => Player::whereNotNull('team_id')
                ->orderByDesc('is_captain')
                ->orderByDesc('is_vice_captain')
                ->orderBy('id')
                ->get(),
            'statRows' => [
                'batting' => $stats->get('batting', collect())->sortBy('sort_order')->values(),
                'bowling' => $stats->get('bowling', collect())->sortBy('sort_order')->values(),
            ],
            'fowRows' => $match->fow()->where('side', $side)->get()->values(),
            'commentaryRows' => $match->commentary()->where('side', $side)->get()->values(),
        ]);
    }

    public function update(Request $request, CricketMatch $match)
    {
        if ($request->boolean('save_summary')) {
            $data = $request->validate([
                'status' => ['required', 'in:completed,upcoming'],
                'rcb_score' => ['nullable', 'string', 'max:50'],
                'rcb_overs' => ['nullable', 'string', 'max:50'],
                'opponent_score' => ['nullable', 'string', 'max:50'],
                'opponent_overs' => ['nullable', 'string', 'max:50'],
                'result_text' => ['nullable', 'string', 'max:255'],
            ]);
            $data['won'] = $request->boolean('won');
            $match->update($data);

            return redirect()->route('admin.scoreboards.edit', ['match' => $match, 'team' => 'summary'])->with('success', __('site.admin.saved'));
        }

        $side = $request->input('stats_side');

        if (! in_array($side, ['rcb', 'opponent'])) {
            return redirect()->route('admin.scoreboards.index');
        }

        $this->saveStats($request, $match, $side);
        $this->saveFow($request, $match, $side);
        $this->saveCommentary($request, $match, $side);

        return redirect()->route('admin.scoreboards.edit', ['match' => $match, 'team' => $side])->with('success', __('site.admin.saved'));
    }

    private function composeDismissal(array $row, $playerNames): string
    {
        $type = (string) ($row['dismissal'] ?? '');

        if ($type === '' || $type === 'notout') {
            return 'not out';
        }

        if ($type === 'didnotbat') {
            return 'did not bat';
        }

        $fielderId = (int) ($row['fielder_id'] ?? 0) ?: null;
        $bowlerId = (int) ($row['bowler_id'] ?? 0) ?: null;
        $fielder = trim((string) ($row['fielder_name'] ?? '')) ?: (string) $playerNames->get((int) $fielderId);
        $bowler = trim((string) ($row['bowler_name'] ?? '')) ?: (string) $playerNames->get((int) $bowlerId);

        return match ($type) {
            'bowled' => 'b ' . $bowler,
            'caught' => 'c ' . $fielder . ' b ' . $bowler,
            'lbw' => 'lbw b ' . $bowler,
            'runout' => 'run out (' . $fielder . ')',
            'stumped' => 'st ' . $fielder . ' b ' . $bowler,
            'c&b' => 'c & b ' . $bowler,
            'hitwicket' => 'hit wicket b ' . $bowler,
            default => 'not out',
        };
    }

    private function saveStats(Request $request, CricketMatch $match, string $side): void
    {
        $input = $request->input('stats', [])[$side] ?? [];

        MatchPlayerStat::where('match_id', $match->id)->where('side', $side)->delete();

        $playerNames = Player::pluck('name', 'id');
        $rows = [];

        foreach (['batting', 'bowling'] as $type) {
            foreach ($input[$type] ?? [] as $index => $row) {
                if (! is_array($row)) {
                    continue;
                }

                $playerId = (int) ($row['player_id'] ?? 0) ?: null;
                $name = trim((string) ($row['name'] ?? ''));

                if ($type === 'batting') {
                    $runs = $row['runs'] ?? null;

                    if ($playerId === null && $name === '' && ($runs === null || $runs === '')) {
                        continue;
                    }

                    $rows[] = [
                        'match_id' => $match->id,
                        'player_id' => $playerId,
                        'name' => $name ?: ($playerId ? (string) $playerNames->get($playerId) : null),
                        'side' => $side,
                        'stat_type' => 'batting',
                        'sort_order' => $index,
                        'runs' => max(0, (int) ($row['runs'] ?? 0)),
                        'balls' => max(0, (int) ($row['balls'] ?? 0)),
                        'fours' => max(0, (int) ($row['fours'] ?? 0)),
                        'sixes' => max(0, (int) ($row['sixes'] ?? 0)),
                        'dismissal' => $this->composeDismissal($row, $playerNames),
                        'fielder_id' => (int) ($row['fielder_id'] ?? 0) ?: null,
                        'bowler_id' => (int) ($row['bowler_id'] ?? 0) ?: null,
                    ];
                } else {
                    $overs = trim((string) ($row['overs'] ?? ''));

                    if ($playerId === null && $name === '' && $overs === '') {
                        continue;
                    }

                    $rows[] = [
                        'match_id' => $match->id,
                        'player_id' => $playerId,
                        'name' => $name ?: ($playerId ? (string) $playerNames->get($playerId) : null),
                        'side' => $side,
                        'stat_type' => 'bowling',
                        'sort_order' => $index,
                        'overs' => $overs ?: null,
                        'maidens' => max(0, (int) ($row['maidens'] ?? 0)),
                        'runs' => max(0, (int) ($row['runs'] ?? 0)),
                        'wickets' => max(0, (int) ($row['wickets'] ?? 0)),
                        'wides' => max(0, (int) ($row['wides'] ?? 0)),
                        'noballs' => max(0, (int) ($row['noballs'] ?? 0)),
                    ];
                }
            }
        }

        foreach ($rows as $row) {
            MatchPlayerStat::create($row);
        }

        $extrasInput = $request->input('extras', [])[$side] ?? null;

        if (is_array($extrasInput)) {
            $match->update([$side === 'rcb' ? 'rcb_extras' : 'opponent_extras' => max(0, (int) ($extrasInput['total'] ?? 0))]);
        }
    }

    private function saveFow(Request $request, CricketMatch $match, string $side): void
    {
        $input = $request->input('fow', [])[$side] ?? [];

        MatchFallOfWicket::where('match_id', $match->id)->where('side', $side)->delete();

        $playerNames = Player::pluck('name', 'id');

        foreach ($input as $index => $row) {
            if (! is_array($row)) {
                continue;
            }

            $playerId = (int) ($row['player_id'] ?? 0) ?: null;
            $name = trim((string) ($row['name'] ?? ''));
            $score = trim((string) ($row['score'] ?? ''));

            if ($playerId === null && $name === '' && $score === '') {
                continue;
            }

            MatchFallOfWicket::create([
                'match_id' => $match->id,
                'side' => $side,
                'player_id' => $playerId,
                'name' => $name ?: ($playerId ? (string) $playerNames->get($playerId) : null),
                'score' => $score ?: null,
                'wicket_no' => max(0, (int) ($row['wicket_no'] ?? 0)) ?: null,
                'over' => trim((string) ($row['over'] ?? '')) ?: null,
                'sort_order' => $index,
            ]);
        }
    }

    private function saveCommentary(Request $request, CricketMatch $match, string $side): void
    {
        $input = $request->input('commentary', [])[$side] ?? [];

        MatchCommentary::where('match_id', $match->id)->where('side', $side)->delete();

        foreach ($input as $index => $row) {
            if (! is_array($row)) {
                continue;
            }

            $description = trim((string) ($row['description'] ?? ''));
            $overNumber = trim((string) ($row['over_number'] ?? ''));
            $scoreAt = trim((string) ($row['score_at'] ?? ''));

            if ($description === '' && $overNumber === '' && $scoreAt === '') {
                continue;
            }

            MatchCommentary::create([
                'match_id' => $match->id,
                'side' => $side,
                'over_number' => $overNumber ?: null,
                'score_at' => $scoreAt ?: null,
                'ball_number' => trim((string) ($row['ball_number'] ?? '')) ?: null,
                'ball_per_run' => trim((string) ($row['ball_per_run'] ?? '')) ?: null,
                'description' => $description ?: null,
                'sort_order' => $index,
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CricketMatch;
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

    public function edit(CricketMatch $match)
    {
        $stats = $match->stats()->get()->groupBy(fn ($s) => $s->side . '.' . $s->stat_type);

        return view('admin.scoreboards.form', [
            'match' => $match,
            'players' => Player::whereNotNull('team_id')->orderByDesc('is_captain')->orderByDesc('is_vice_captain')->orderBy('name')->get(),
            'statRows' => [
                'rcb' => [
                    'batting' => $stats->get('rcb.batting', collect()),
                    'bowling' => $stats->get('rcb.bowling', collect()),
                ],
                'opponent' => [
                    'batting' => $stats->get('opponent.batting', collect()),
                    'bowling' => $stats->get('opponent.bowling', collect()),
                ],
            ],
        ]);
    }

    public function update(Request $request, CricketMatch $match)
    {
        $data = $request->validate([
            'status' => ['required', 'in:completed,upcoming'],
            'rcb_score' => ['nullable', 'string', 'max:50'],
            'rcb_overs' => ['nullable', 'string', 'max:50'],
            'opponent_score' => ['nullable', 'string', 'max:50'],
            'opponent_overs' => ['nullable', 'string', 'max:50'],
            'result_text' => ['nullable', 'string', 'max:255'],
            'won' => ['nullable', 'boolean'],
        ]) + [
            'won' => $request->boolean('won'),
        ];

        $match->update($data);
        $this->saveStats($request, $match);

        return redirect()->route('admin.scoreboards.index')->with('success', __('site.admin.saved'));
    }

    private function saveStats(Request $request, CricketMatch $match): void
    {
        $input = $request->input('stats', []);
        $rows = [];

        foreach (['rcb', 'opponent'] as $side) {
            foreach (['batting', 'bowling'] as $type) {
                foreach ($input[$side][$type] ?? [] as $index => $row) {
                    if (! is_array($row)) {
                        continue;
                    }

                    $name = trim((string) ($row['name'] ?? ''));
                    $playerId = (int) ($row['player_id'] ?? 0) ?: null;

                    if ($type === 'batting') {
                        if ($row['runs'] === null && $name === '' && $playerId === null) {
                            continue;
                        }

                        $rows[] = [
                            'match_id' => $match->id,
                            'player_id' => $playerId,
                            'name' => $name,
                            'side' => $side,
                            'stat_type' => 'batting',
                            'sort_order' => $index,
                            'runs' => (int) ($row['runs'] ?? 0),
                            'balls' => (int) ($row['balls'] ?? 0),
                            'fours' => (int) ($row['fours'] ?? 0),
                            'sixes' => (int) ($row['sixes'] ?? 0),
                            'dismissal' => trim((string) ($row['dismissal'] ?? '')) ?: 'not out',
                        ];
                    } else {
                        $overs = trim((string) ($row['overs'] ?? ''));
                        if ($overs === '' && $name === '' && $playerId === null) {
                            continue;
                        }

                        $rows[] = [
                            'match_id' => $match->id,
                            'player_id' => $playerId,
                            'name' => $name,
                            'side' => $side,
                            'stat_type' => 'bowling',
                            'sort_order' => $index,
                            'overs' => $overs,
                            'maidens' => (int) ($row['maidens'] ?? 0),
                            'runs' => (int) ($row['runs'] ?? 0),
                            'wickets' => (int) ($row['wickets'] ?? 0),
                            'wides' => (int) ($row['wides'] ?? 0),
                            'noballs' => (int) ($row['noballs'] ?? 0),
                        ];
                    }
                }
            }
        }

        $match->stats()->delete();

        foreach ($rows as $row) {
            $match->stats()->create($row);
        }

        $match->update([
            'rcb_extras' => (int) ($input['rcb']['extras'] ?? 0),
            'opponent_extras' => (int) ($input['opponent']['extras'] ?? 0),
        ]);
    }
}

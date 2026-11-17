<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CricketMatch;
use App\Models\Player;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;

class SquadController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = (string) $request->get('status', '');
        $limit = in_array((int) $request->get('limit', 10), [10, 25, 50, 100]) ? (int) $request->get('limit', 10) : 10;

        $query = CricketMatch::with('tournament');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('opponent_name', 'like', "%{$search}%")
                    ->orWhere('opponent_name_bn', 'like', "%{$search}%")
                    ->orWhere('match_name', 'like', "%{$search}%");
            });
        }

        if (in_array($status, ['upcoming', 'completed', 'live', 'abandoned'])) {
            $query->where('status', $status);
        }

        $matches = $query->orderByDesc('match_number')
            ->orderByDesc('match_date')
            ->paginate($limit)
            ->appends($request->query());

        return view('admin.squads.index', [
            'matches' => $matches,
            'filterSearch' => $search,
            'filterStatus' => $status,
            'filterLimit' => $limit,
        ]);
    }

    public function create()
    {
        $tournaments = Tournament::with(['matches' => fn ($q) => $q->orderByDesc('match_number')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $playersByTeam = Player::whereNotNull('team_id')
            ->orderByDesc('id')
            ->get()
            ->groupBy('team_id')
            ->map(fn ($players) => $players
                ->map(fn ($p) => ['id' => $p->id, 'name' => $p->name, 'role' => $p->role])
                ->values()
                ->all())
            ->all();

        return view('admin.squads.create', [
            'tournaments' => $tournaments,
            'playersByTeam' => $playersByTeam,
            'rcbTeam' => Team::where('is_rcb', 1)->first(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'match_id' => ['required', 'integer'],
            'player_ids' => ['required', 'array', 'min:1'],
            'player_ids.*' => ['integer'],
        ]);

        $match = CricketMatch::findOrFail($data['match_id']);

        $existing = $match->squad()->pluck('players.id')->all();
        $merged = array_values(array_unique(array_merge($existing, array_map('intval', $data['player_ids']))));

        $match->squad()->sync($merged);

        return redirect()->route('admin.squads.create')->with('success', __('site.admin.saved'));
    }

    public function edit(CricketMatch $match)
    {
        return view('admin.squads.form', [
            'match' => $match,
            'players' => Player::whereNotNull('team_id')
                ->with('team')
                ->orderByDesc('is_captain')
                ->orderByDesc('is_vice_captain')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function update(Request $request, CricketMatch $match)
    {
        $ids = collect($request->input('squad', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->all();

        $match->squad()->sync($ids);

        return redirect()->route('admin.squads.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(CricketMatch $match)
    {
        $match->squad()->detach();

        return redirect()->route('admin.squads.index')->with('success', __('site.admin.deleted'));
    }
}

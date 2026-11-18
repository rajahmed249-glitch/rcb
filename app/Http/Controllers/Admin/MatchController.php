<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CricketMatch;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = (string) $request->get('status', '');
        $limit = in_array((int) $request->get('limit', 10), [10, 25, 50, 100]) ? (int) $request->get('limit', 10) : 10;

        $tournamentId = (int) $request->get('tournament', 0);

        $query = CricketMatch::with(['tournament', 'opponentTeam']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('opponent_name', 'like', "%{$search}%")
                    ->orWhere('opponent_name_bn', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        if (in_array($status, ['upcoming', 'completed', 'live', 'abandoned'])) {
            $query->where('status', $status);
        }

        if ($tournamentId > 0) {
            $query->where('tournament_id', $tournamentId);
        }

        $matches = $query->orderByDesc('match_number')
            ->orderByDesc('match_date')
            ->paginate($limit)
            ->appends($request->query());

        return view('admin.matches.index', [
            'matches' => $matches,
            'filterSearch' => $search,
            'filterStatus' => $status,
            'filterTournament' => $tournamentId,
            'filterLimit' => $limit,
            'tournaments' => Tournament::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.matches.form', [
            'match' => new CricketMatch(),
            'teams' => Team::orderBy('sort_order')->orderBy('id')->get(),
            'tournaments' => Tournament::orderBy('sort_order')->orderBy('id')->get(),
            'tournamentTeams' => $this->tournamentTeams(),
            'rcbTeam' => Team::where('is_rcb', 1)->first(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['match_number'] = (int) CricketMatch::max('match_number') + 1;
        $data['opponent_logo'] = optional(Team::find($data['opponent_team_id'] ?? null))->logo;

        $match = CricketMatch::create($data);

        return redirect()->route('admin.matches.index')->with('success', __('site.admin.saved'));
    }

    public function edit(CricketMatch $match)
    {
        $rcbTeam = Team::where('is_rcb', 1)->first();
        $tournamentTeams = $this->tournamentTeams();

        if ($match->tournament_id) {
            $list = $tournamentTeams[$match->tournament_id] ?? [];

            if ($match->opponent_team_id && ! in_array((int) $match->opponent_team_id, array_column($list, 'id'))) {
                $opponent = $match->opponentTeam ?: Team::find($match->opponent_team_id);

                if ($opponent) {
                    $tournamentTeams[$match->tournament_id][] = ['id' => $opponent->id, 'name' => $opponent->name];
                }
            }
        }

        $allTeams = Team::orderBy('sort_order')->orderBy('id')->get()
            ->filter(fn ($team) => ! ($rcbTeam && (int) $team->id === (int) $rcbTeam->id))
            ->map(fn ($team) => ['id' => $team->id, 'name' => $team->name])
            ->values()
            ->all();

        return view('admin.matches.form', [
            'match' => $match,
            'teams' => Team::orderBy('sort_order')->orderBy('id')->get(),
            'allTeams' => $allTeams,
            'tournaments' => Tournament::orderBy('sort_order')->orderBy('id')->get(),
            'tournamentTeams' => $tournamentTeams,
            'rcbTeam' => $rcbTeam,
        ]);
    }

    public function update(Request $request, CricketMatch $match)
    {
        $data = $this->validated($request);
        $data['opponent_logo'] = optional(Team::find($data['opponent_team_id'] ?? null))->logo
            ?? $match->opponent_logo;

        $match->update($data);

        return redirect()->route('admin.matches.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(CricketMatch $match)
    {
        $match->delete();

        return redirect()->route('admin.matches.index')->with('success', __('site.admin.deleted'));
    }

    private function tournamentTeams(): array
    {
        return Tournament::with(['teams' => fn ($q) => $q->where('status', 1)->orderBy('name')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->mapWithKeys(fn ($tournament) => [
                $tournament->id => $tournament->teams
                    ->map(fn ($team) => ['id' => $team->id, 'name' => $team->name])
                    ->values()
                    ->all(),
            ])
            ->all();
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'tournament_id' => ['nullable', 'integer', 'exists:tournaments,id'],
            'match_name' => ['nullable', 'string', 'max:255'],
            'opponent_team_id' => ['required', 'integer', 'exists:teams,id'],
            'venue' => ['required', 'string', 'max:255'],
            'match_date' => ['required', 'date'],
            'match_time' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:upcoming,live,completed,abandoned,cancelled'],
            'match_info' => ['nullable', 'string'],
        ]);

        if (! empty($data['opponent_team_id'])) {
            $team = Team::find($data['opponent_team_id']);

            if ($team) {
                $data['opponent_name'] = $team->name;
                $data['opponent_name_bn'] = $team->name_bn;
                $data['opponent_short_name'] = $team->short_name;
            }
        }

        if (! empty($data['match_time'])) {
            $data['match_date'] = $data['match_date'] . ' ' . $data['match_time'];
        } else {
            unset($data['match_time']);
        }

        return $data;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CricketMatch;
use App\Models\Player;
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

        $matches = $query->orderByDesc('match_date')
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
            'squadPlayers' => $this->squadPlayers(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['opponent_logo'] = $this->upload($request)
            ?? optional(Team::find($data['opponent_team_id'] ?? null))->logo;

        $match = CricketMatch::create($data);
        $this->syncSquad($request, $match);

        return redirect()->route('admin.matches.index')->with('success', __('site.admin.saved'));
    }

    public function edit(CricketMatch $match)
    {
        return view('admin.matches.form', [
            'match' => $match,
            'teams' => Team::orderBy('sort_order')->orderBy('id')->get(),
            'tournaments' => Tournament::orderBy('sort_order')->orderBy('id')->get(),
            'squadPlayers' => $this->squadPlayers(),
        ]);
    }

    public function update(Request $request, CricketMatch $match)
    {
        $data = $this->validated($request);
        $data['opponent_logo'] = $this->upload($request)
            ?? (isset($data['opponent_team_id']) && $data['opponent_team_id']
                ? (optional(Team::find($data['opponent_team_id']))->logo ?? $match->opponent_logo)
                : $match->opponent_logo);

        $match->update($data);
        $this->syncSquad($request, $match);

        return redirect()->route('admin.matches.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(CricketMatch $match)
    {
        $match->delete();

        return redirect()->route('admin.matches.index')->with('success', __('site.admin.deleted'));
    }

    private function squadPlayers()
    {
        return Player::whereNotNull('team_id')
            ->with('team')
            ->orderByDesc('is_captain')
            ->orderByDesc('is_vice_captain')
            ->orderBy('id')
            ->get();
    }

    private function syncSquad(Request $request, CricketMatch $match): void
    {
        if (! $request->has('squad')) {
            return;
        }

        $ids = collect($request->input('squad', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->all();

        $match->squad()->sync($ids);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'match_number' => ['required', 'integer'],
            'tournament_id' => ['nullable', 'integer', 'exists:tournaments,id'],
            'opponent_team_id' => ['nullable', 'integer', 'exists:teams,id'],
            'opponent_name' => ['required', 'string', 'max:255'],
            'opponent_name_bn' => ['nullable', 'string', 'max:255'],
            'opponent_short_name' => ['nullable', 'string', 'max:10'],
            'venue' => ['nullable', 'string', 'max:255'],
            'match_date' => ['required', 'date'],
            'status' => ['required', 'in:completed,upcoming'],
            'format' => ['nullable', 'string', 'max:255'],
            'rcb_score' => ['nullable', 'string', 'max:50'],
            'rcb_overs' => ['nullable', 'string', 'max:50'],
            'opponent_score' => ['nullable', 'string', 'max:50'],
            'opponent_overs' => ['nullable', 'string', 'max:50'],
            'result_text' => ['nullable', 'string', 'max:255'],
            'won' => ['nullable', 'boolean'],
        ]) + [
            'won' => $request->boolean('won'),
        ];

        if (! empty($data['opponent_team_id'])) {
            $team = Team::find($data['opponent_team_id']);

            if ($team) {
                $data['opponent_name'] = $team->name;
                $data['opponent_name_bn'] = $team->name_bn;
                $data['opponent_short_name'] = $team->short_name;
            }
        }

        return $data;
    }

    private function upload(Request $request): ?string
    {
        if (! $request->hasFile('opponent_logo') || ! $request->file('opponent_logo')->isValid()) {
            return null;
        }

        $file = $request->file('opponent_logo');
        $filename = 'team_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/teams'), $filename);

        return 'uploads/teams/' . $filename;
    }
}

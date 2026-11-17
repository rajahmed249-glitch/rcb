<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $limit = in_array((int) $request->get('limit', 10), [10, 25, 50, 100]) ? (int) $request->get('limit', 10) : 10;

        $query = Team::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_bn', 'like', "%{$search}%")
                    ->orWhere('short_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $teams = $query->orderBy('sort_order')
            ->orderBy('id')
            ->paginate($limit)
            ->appends($request->query());

        return view('admin.teams.index', [
            'teams' => $teams,
            'filterSearch' => $search,
            'filterLimit' => $limit,
        ]);
    }

    public function create()
    {
        return view('admin.teams.form', [
            'team' => new Team(),
            'players' => Player::orderBy('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'player_ids' => ['required', 'array', 'min:1'],
            'player_ids.*' => ['integer', 'exists:players,id'],
        ]);

        $data = $this->validated($request);
        $data['slug'] = Team::uniqueSlug($request->input('slug') ?: $request->input('name'));
        $data['logo'] = $this->upload($request);

        $team = Team::create($data);
        $this->syncPlayers($request, $team, true);

        return redirect()->route('admin.teams.index')->with('success', __('site.admin.saved'));
    }

    public function edit(Team $team)
    {
        return view('admin.teams.form', [
            'team' => $team,
            'players' => Player::orderBy('id')->get(),
        ]);
    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'player_ids' => ['required', 'array', 'min:1'],
            'player_ids.*' => ['integer', 'exists:players,id'],
        ]);

        $data = $this->validated($request);
        $data['logo'] = $this->upload($request) ?? $team->logo;

        if ($request->filled('slug') && $request->input('slug') !== $team->slug) {
            $data['slug'] = Team::uniqueSlug($request->input('slug'), $team->id);
        }

        $team->update($data);
        $this->syncPlayers($request, $team, false);

        return redirect()->route('admin.teams.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return redirect()->route('admin.teams.index')->with('success', __('site.admin.deleted'));
    }

    private function syncPlayers(Request $request, Team $team, bool $creating): void
    {
        if (! $request->has('player_ids')) {
            return;
        }

        $ids = collect($request->input('player_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->all();

        Player::whereIn('id', $ids)->update(['team_id' => $team->id]);

        if (! $creating) {
            Player::where('team_id', $team->id)->whereNotIn('id', $ids)->update(['team_id' => null]);
        }
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_bn' => ['required', 'string', 'max:255'],
            'short_name' => ['nullable', 'string', 'max:20'],
            'short_name_bn' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['status'] = $request->input('status') !== null ? $request->boolean('status') : true;

        return $data;
    }

    private function upload(Request $request): ?string
    {
        if (! $request->hasFile('logo') || ! $request->file('logo')->isValid()) {
            return null;
        }

        $file = $request->file('logo');
        $filename = 'team_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/teams'), $filename);

        return 'uploads/teams/' . $filename;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        return view('admin.teams.index', [
            'teams' => Team::orderBy('sort_order')->orderBy('id')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.teams.form', [
            'team' => new Team(),
            'players' => Player::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
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
            'players' => Player::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Team $team)
    {
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
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_bn' => ['nullable', 'string', 'max:255'],
            'short_name' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_rcb' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]) + [
            'is_rcb' => $request->boolean('is_rcb'),
        ];
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

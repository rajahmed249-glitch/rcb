<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Player;
use App\Models\RoleIcon;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $query = Player::query()
            ->orderByDesc('is_captain')
            ->orderByDesc('is_vice_captain')
            ->orderBy('id');

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_bn', 'like', "%{$search}%");
            });
        }

        if ($role = trim((string) $request->query('role'))) {
            $query->where('role', $role);
        }

        if (($status = $request->query('status')) !== null && $status !== '') {
            $query->where('status', $status === '1' ? 1 : 0);
        }

        $limit = in_array((int) $request->query('limit'), [10, 25, 50, 100], true)
            ? (int) $request->query('limit')
            : 10;

        return view('admin.players.index', [
            'players' => $query->with(['team', 'thana'])->paginate($limit)->appends($request->query()),
            'filterSearch' => $search,
            'filterRole' => trim((string) $request->query('role')),
            'filterStatus' => (string) $request->query('status'),
            'filterLimit' => $limit,
        ]);
    }

    public function create()
    {
        return view('admin.players.form', [
            'player' => new Player(),
            'teams' => Team::orderBy('sort_order')->orderBy('id')->get(),
            'roleIcons' => RoleIcon::orderBy('sort_order')->orderBy('id')->get(),
            'districts' => Location::where('type', 'district')->orderBy('sort_order')->orderBy('name')->get(),
            'thanas' => Location::where('type', 'thana')->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->upload($request, 'photo', 'players');

        Player::create($data);

        return redirect()->route('admin.players.index')->with('success', __('site.admin.saved'));
    }

    public function edit(Player $player)
    {
        return view('admin.players.form', [
            'player' => $player,
            'teams' => Team::orderBy('sort_order')->orderBy('id')->get(),
            'roleIcons' => RoleIcon::orderBy('sort_order')->orderBy('id')->get(),
            'districts' => Location::where('type', 'district')->orderBy('sort_order')->orderBy('name')->get(),
            'thanas' => Location::where('type', 'thana')->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Player $player)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->upload($request, 'photo', 'players') ?? $player->photo;

        $player->update($data);

        return redirect()->route('admin.players.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(Player $player)
    {
        $player->delete();

        return redirect()->route('admin.players.index')->with('success', __('site.admin.deleted'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'team_id' => ['nullable', 'integer', 'exists:teams,id'],
            'name' => ['required', 'string', 'max:255'],
            'name_bn' => ['nullable', 'string', 'max:255'],
            'short_name' => ['nullable', 'string', 'max:10'],
            'role' => ['required', 'string'],
            'date_of_birth' => ['nullable', 'date'],
            'role_icon_id' => ['nullable', 'integer', 'exists:role_icons,id'],
            'district_id' => ['nullable', 'integer', 'exists:locations,id'],
            'thana_id' => ['nullable', 'integer', 'exists:locations,id'],
            'village' => ['nullable', 'string', 'max:255'],
            'post_office' => ['nullable', 'string', 'max:255'],
            'religion' => ['nullable', 'string', 'max:50'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'debut' => ['nullable', 'date'],
            'player_tag' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'boolean'],
            'jersey_number' => ['nullable', 'integer'],
            'is_captain' => ['nullable', 'boolean'],
            'is_vice_captain' => ['nullable', 'boolean'],
            'matches_played' => ['nullable', 'integer'],
            'runs' => ['nullable', 'integer'],
            'wickets' => ['nullable', 'integer'],
            'batting_style' => ['nullable', 'string', 'max:255'],
            'bowling_style' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'bio_bn' => ['nullable', 'string'],
        ]) + [
            'is_captain' => $request->boolean('is_captain'),
            'is_vice_captain' => $request->boolean('is_vice_captain'),
            'status' => $request->boolean('status'),
        ];
    }

    private function upload(Request $request, string $field, string $folder): ?string
    {
        if (! $request->hasFile($field) || ! $request->file($field)->isValid()) {
            return null;
        }

        $file = $request->file($field);
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/' . $folder), $filename);

        return 'uploads/' . $folder . '/' . $filename;
    }
}

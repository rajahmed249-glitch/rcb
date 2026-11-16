<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlayerController extends Controller
{
    public function index()
    {
        return view('admin.players.index', [
            'players' => Player::orderByDesc('is_captain')->orderByDesc('is_vice_captain')->orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.players.form', ['player' => new Player()]);
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
        return view('admin.players.form', ['player' => $player]);
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
            'name' => ['required', 'string', 'max:255'],
            'name_bn' => ['nullable', 'string', 'max:255'],
            'short_name' => ['nullable', 'string', 'max:10'],
            'role' => ['required', 'string'],
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

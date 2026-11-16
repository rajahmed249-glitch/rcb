<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CricketMatch;
use App\Models\Player;
use Illuminate\Http\Request;

class SquadController extends Controller
{
    public function index()
    {
        return view('admin.squads.index', [
            'matches' => CricketMatch::with('squad')->orderByDesc('match_date')->get(),
        ]);
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
}

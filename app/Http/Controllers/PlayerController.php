<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');

        $query = Player::query()
            ->where('frontend_show', 1)
            ->orderByDesc('is_captain')
            ->orderByDesc('is_vice_captain')
            ->orderBy('id');

        if (($rcbTeam = \App\Models\Team::where('is_rcb', 1)->first())) {
            $query->where('team_id', $rcbTeam->id);
        }

        if ($role) {
            $query->where('role', $role);
        }

        return view('players.index', [
            'role' => $role,
            'players' => $query->paginate(12)->withQueryString(),
        ]);
    }

    public function show(Player $player)
    {
        return view('players.show', ['player' => $player]);
    }
}

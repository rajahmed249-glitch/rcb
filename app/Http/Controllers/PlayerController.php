<?php

namespace App\Http\Controllers;

use App\Models\Player;

class PlayerController extends Controller
{
    public function index()
    {
        return view('players.index', [
            'players' => Player::orderByDesc('is_captain')
                ->orderByDesc('is_vice_captain')
                ->orderBy('id')
                ->paginate(12),
        ]);
    }

    public function show(Player $player)
    {
        return view('players.show', ['player' => $player]);
    }
}

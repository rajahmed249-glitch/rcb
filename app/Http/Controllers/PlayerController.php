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
            ->orderByDesc('is_captain')
            ->orderByDesc('is_vice_captain')
            ->orderBy('id');

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

<?php

namespace App\Http\Controllers;

use App\Models\CricketMatch;
use App\Models\Player;

class PageController extends Controller
{
    public function about()
    {
        $completed = CricketMatch::completed()->get();

        return view('pages.about', [
            'matchesPlayed' => $completed->count(),
            'wins' => $completed->where('won', true)->count(),
            'playerCount' => Player::count(),
        ]);
    }

    public function gallery()
    {
        return view('pages.gallery');
    }

    public function teams()
    {
        return view('pages.teams', [
            'squad' => Player::orderByDesc('is_captain')
                ->orderByDesc('is_vice_captain')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function records()
    {
        return view('pages.records', [
            'topRuns' => Player::orderByDesc('runs')->take(5)->get(),
            'topWickets' => Player::orderByDesc('wickets')->take(5)->get(),
            'topMatches' => Player::orderByDesc('matches_played')->take(5)->get(),
            'completed' => CricketMatch::completed()->get(),
        ]);
    }

    public function contact()
    {
        return view('pages.contact');
    }
}

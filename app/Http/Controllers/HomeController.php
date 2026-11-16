<?php

namespace App\Http\Controllers;

use App\Models\CricketMatch;
use App\Models\NewsPost;
use App\Models\Player;
use App\Models\Sponsor;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'latestResult' => CricketMatch::completed()->first(),
            'upcomingMatches' => CricketMatch::upcoming()->take(3)->get(),
            'latestNews' => NewsPost::orderByDesc('published_at')->take(3)->get(),
            'players' => Player::orderByDesc('is_captain')->orderByDesc('is_vice_captain')->orderBy('id')->take(12)->get(),
            'sponsors' => Sponsor::orderBy('sort_order')->get(),
        ]);
    }
}

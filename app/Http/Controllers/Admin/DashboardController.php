<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CricketMatch;
use App\Models\NewsPost;
use App\Models\Player;
use App\Models\PlayerApplication;
use App\Models\Sponsor;
use App\Models\Team;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'completed' => CricketMatch::where('status', 'completed')->count(),
            'upcoming' => CricketMatch::where('status', 'upcoming')->count(),
            'live' => CricketMatch::where('status', 'live')->count(),
            'abandoned' => CricketMatch::where('status', 'abandoned')->count(),
        ];
        $stats['total'] = array_sum($stats);

        return view('admin.dashboard', [
            'playersCount' => Player::count(),
            'teamsCount' => Team::count(),
            'matchesCount' => CricketMatch::count(),
            'newsCount' => NewsPost::count(),
            'sponsorsCount' => Sponsor::count(),
            'applicationsCount' => PlayerApplication::count(),
            'stats' => $stats,
            'recentMatches' => CricketMatch::orderByDesc('match_date')->take(6)->get(),
            'recentNews' => NewsPost::orderByDesc('published_at')->take(4)->get(),
            'recentPlayers' => Player::orderByDesc('created_at')->take(4)->get(),
            'recentApplications' => PlayerApplication::orderByDesc('created_at')->take(5)->get(),
        ]);
    }
}

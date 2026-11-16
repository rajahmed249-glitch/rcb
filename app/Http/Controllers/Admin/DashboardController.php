<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CricketMatch;
use App\Models\NewsPost;
use App\Models\Player;
use App\Models\PlayerApplication;
use App\Models\Sponsor;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'playersCount' => Player::count(),
            'matchesCount' => CricketMatch::count(),
            'newsCount' => NewsPost::count(),
            'sponsorsCount' => Sponsor::count(),
            'applicationsCount' => PlayerApplication::count(),
            'recentApplications' => PlayerApplication::orderByDesc('created_at')->take(5)->get(),
        ]);
    }
}

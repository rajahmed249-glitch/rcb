<?php

namespace App\Http\Controllers;

use App\Models\CricketMatch;
use App\Models\Player;
use Illuminate\Support\Str;

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
            'teams' => $this->buildTeams(),
        ]);
    }

    public function teamProfile(string $slug)
    {
        $teams = $this->buildTeams();

        if (! isset($teams[$slug])) {
            abort(404);
        }

        $team = $teams[$slug];

        $players = collect();
        $topRuns = collect();
        $topWickets = collect();

        if ($team['is_rcb']) {
            $players = Player::orderByDesc('is_captain')
                ->orderByDesc('is_vice_captain')
                ->orderBy('id')
                ->get();
            $topRuns = Player::orderByDesc('runs')->take(5)->get();
            $topWickets = Player::orderByDesc('wickets')->take(5)->get();
        }

        $teamMatches = CricketMatch::query()
            ->when($team['is_rcb'],
                fn ($q) => $q->whereNotNull('opponent_name'),
                fn ($q) => $q->where('opponent_name', $team['name']))
            ->orderByDesc('match_date')
            ->get();

        return view('pages.team-profile', [
            'team' => $team,
            'players' => $players,
            'topRuns' => $topRuns,
            'topWickets' => $topWickets,
            'teamMatches' => $teamMatches,
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

    private function buildTeams(): array
    {
        $completed = CricketMatch::completed()->get();

        $teams = [];

        $teams['real-challenger-boys'] = [
            'slug' => 'real-challenger-boys',
            'name' => 'Real Challenger Boys',
            'display_name' => app()->getLocale() === 'bn' ? 'রিয়েল চ্যালেঞ্জার বয়েজ' : 'Real Challenger Boys',
            'short_name' => 'RCB',
            'logo' => 'images/teams/real-challenger-boys.webp',
            'location' => 'Patuakhali, Bangladesh',
            'is_rcb' => true,
            'played' => $completed->count(),
            'won' => $completed->where('won', true)->count(),
            'lost' => $completed->where('won', false)->count(),
        ];

        CricketMatch::query()
            ->get()
            ->groupBy('opponent_name')
            ->each(function ($matches, $name) use (&$teams) {
                $slug = Str::slug($name);
                if ($slug === '' || isset($teams[$slug])) {
                    return;
                }

                $done = $matches->where('status', 'completed');
                $logo = optional($matches->first(fn ($m) => (bool) $m->opponent_logo))->opponent_logo;

                $nameBn = $matches->first()->opponent_name_bn;

                $teams[$slug] = [
                    'slug' => $slug,
                    'name' => $name,
                    'display_name' => app()->getLocale() === 'bn' && $nameBn ? $nameBn : $name,
                    'short_name' => $matches->first()->opponent_short_name,
                    'logo' => $logo,
                    'location' => $matches->first()->venue,
                    'is_rcb' => false,
                    'played' => $done->count(),
                    'won' => $done->where('won', false)->count(),
                    'lost' => $done->where('won', true)->count(),
                ];
            });

        return $teams;
    }
}

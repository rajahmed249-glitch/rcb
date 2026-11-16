<?php

namespace App\Http\Controllers;

use App\Models\CricketMatch;
use App\Models\GalleryPhoto;
use App\Models\Player;
use App\Models\SiteSetting;
use App\Models\Team;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function about()
    {
        $completed = CricketMatch::completed()->get();
        $bn = app()->getLocale() === 'bn';

        return view('pages.about', [
            'matchesPlayed' => $completed->count(),
            'wins' => $completed->where('won', true)->count(),
            'playerCount' => Player::count(),
            'whoText' => SiteSetting::get($bn ? 'about_who_text_bn' : 'about_who_text', __('site.about.who_text')),
            'believeText' => SiteSetting::get($bn ? 'about_believe_text_bn' : 'about_believe_text', __('site.about.believe_text')),
            'missionText' => SiteSetting::get($bn ? 'about_mission_text_bn' : 'about_mission_text', __('site.about.mission_text')),
            'visionText' => SiteSetting::get($bn ? 'about_vision_text_bn' : 'about_vision_text', __('site.about.vision_text')),
            'homeText' => SiteSetting::get($bn ? 'about_home_text_bn' : 'about_home_text', __('site.about.home_text')),
            'since' => SiteSetting::get('about_since', '2004'),
        ]);
    }

    public function gallery()
    {
        $photos = GalleryPhoto::orderBy('sort_order')->orderBy('id')->get();

        if ($photos->isEmpty()) {
            $photos = collect([
                ['image' => 'images/banner1.png', 'alt' => 'RCB team on the field'],
                ['image' => 'images/banner2.webp', 'alt' => 'RCB team photo'],
                ['image' => 'images/banner3.webp', 'alt' => 'RCB celebration'],
                ['image' => 'images/news/team-photo-2.webp', 'alt' => 'RCB team group photo'],
                ['image' => 'images/news/team-photo-3.webp', 'alt' => 'RCB team group photo'],
                ['image' => 'images/logo.png', 'alt' => 'RCB logo'],
            ])->map(fn ($p) => (object) $p);
        }

        return view('pages.gallery', ['photos' => $photos]);
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
        $bn = app()->getLocale() === 'bn';

        return view('pages.contact', [
            'location' => SiteSetting::get($bn ? 'contact_location_bn' : 'contact_location', 'East Shewrapara, Begum Rokeya Sarani, Mirpur, Dhaka - 1216, Bangladesh'),
            'phone' => SiteSetting::get('contact_phone', '01674297522'),
            'email' => SiteSetting::get('contact_email', 'info@rcb.com.bd'),
            'whatsapp' => SiteSetting::get('contact_whatsapp', 'https://wa.link/c1y139'),
        ]);
    }

    private function buildTeams(): array
    {
        $completed = CricketMatch::completed()->get();

        $teams = [];

        $dbTeams = Team::query()->orderBy('sort_order')->orderBy('id')->get();

        if ($dbTeams->isNotEmpty()) {
            foreach ($dbTeams as $dbTeam) {
                $matches = CricketMatch::query()
                    ->when($dbTeam->is_rcb,
                        fn ($q) => $q,
                        fn ($q) => $q->where('opponent_name', $dbTeam->name))
                    ->get();

                $done = $matches->where('status', 'completed');

                $teams[$dbTeam->slug] = [
                    'slug' => $dbTeam->slug,
                    'name' => $dbTeam->name,
                    'display_name' => $dbTeam->display_name,
                    'short_name' => $dbTeam->short_name,
                    'logo' => $dbTeam->logo ?: ($dbTeam->is_rcb ? 'images/teams/real-challenger-boys.webp' : null),
                    'location' => $dbTeam->location ?: $matches->first()?->venue,
                    'is_rcb' => $dbTeam->is_rcb,
                    'played' => $dbTeam->is_rcb ? $done->count() : $done->count(),
                    'won' => $dbTeam->is_rcb ? $done->where('won', true)->count() : $done->where('won', false)->count(),
                    'lost' => $dbTeam->is_rcb ? $done->where('won', false)->count() : $done->where('won', true)->count(),
                ];
            }

            return $teams;
        }

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

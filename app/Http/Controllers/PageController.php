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
        $photos = GalleryPhoto::photos()
            ->orderByDesc('date')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(12);

        return view('pages.gallery', ['photos' => $photos]);
    }

    public function galleryVideos()
    {
        $videos = GalleryPhoto::videos()
            ->orderByDesc('date')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(9);

        return view('pages.gallery-videos', ['videos' => $videos]);
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
        $dbTeam = Team::where('slug', $slug)->first();

        $players = collect();
        $topRuns = collect();
        $topWickets = collect();

        if ($team['is_rcb']) {
            $players = $dbTeam
                ? Player::where('team_id', $dbTeam->id)
                    ->orderByDesc('is_captain')
                    ->orderByDesc('is_vice_captain')
                    ->orderBy('id')
                    ->get()
                : Player::orderByDesc('is_captain')
                    ->orderByDesc('is_vice_captain')
                    ->orderBy('id')
                    ->get();
            $topRuns = Player::orderByDesc('runs')->take(5)->get();
            $topWickets = Player::orderByDesc('wickets')->take(5)->get();
        } elseif ($dbTeam) {
            $players = Player::where('team_id', $dbTeam->id)
                ->orderByDesc('is_captain')
                ->orderBy('id')
                ->get();
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
        $rcbTeam = Team::where('is_rcb', true)->first();
        $rcbId = $rcbTeam?->id ?? 1;

        $matches = CricketMatch::completed()
            ->orderByDesc('match_date')
            ->get();

        $opponentIds = $matches->pluck('opponent_team_id')->filter()->unique();
        $teams = Team::whereIn('id', $opponentIds)->get()->keyBy('id');

        $opponents = [];

        foreach ($opponentIds as $oppId) {
            $team = $teams->get($oppId);
            $oppMatches = $matches->where('opponent_team_id', $oppId);

            $played = $oppMatches->count();
            $won = $oppMatches->where('won', true)->count();
            $lost = $oppMatches->where('won', false)->whereNotNull('won')->count();
            $draw = $played - $won - $lost;

            $opponents[] = [
                'id' => $oppId,
                'name' => $team?->display_name ?? $oppMatches->first()->display_opponent_name,
                'logo' => $team?->logo ?? $oppMatches->first()->opponent_logo,
                'slug' => $team?->slug,
                'played' => $played,
                'won' => $won,
                'lost' => $lost,
                'draw' => $draw,
                'w_l' => $lost > 0 ? round($won / $lost, 2) : ($won > 0 ? $won : 0),
                'w_percent' => $played > 0 ? round(($won / $played) * 100, 1) : 0,
                'l_percent' => $played > 0 ? round(($lost / $played) * 100, 1) : 0,
                'd_percent' => $played > 0 ? round(($draw / $played) * 100, 1) : 0,
                'matches' => $oppMatches->take(5)->values(),
            ];
        }

        usort($opponents, fn ($a, $b) => $b['played'] <=> $a['played']);

        $totalMatches = collect($opponents)->sum('played');
        $totalWins = collect($opponents)->sum('won');
        $totalLosses = collect($opponents)->sum('lost');
        $totalDraws = collect($opponents)->sum('draw');
        $winPercentage = $totalMatches > 0 ? round(($totalWins / $totalMatches) * 100, 1) : 0;

        $page = max(1, (int) request()->get('page', 1));
        $perPage = 10;
        $paged = array_slice($opponents, ($page - 1) * $perPage, $perPage);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paged,
            count($opponents),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('pages.records', [
            'opponents' => $paginator,
            'totalMatches' => $totalMatches,
            'totalWins' => $totalWins,
            'totalLosses' => $totalLosses,
            'totalDraws' => $totalDraws,
            'winPercentage' => $winPercentage,
            'rcbId' => $rcbId,
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

        $dbTeams = Team::query()->where('status', 1)->orderBy('sort_order')->orderBy('id')->get();

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

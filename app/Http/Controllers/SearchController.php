<?php

namespace App\Http\Controllers;

use App\Models\CricketMatch;
use App\Models\NewsPost;
use App\Models\Player;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function live(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        if ($query === '') {
            return response()->json(['players' => [], 'matches' => [], 'news' => []]);
        }

        $players = Player::where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->take(5)
            ->get();

        $matches = CricketMatch::where('opponent_name', 'like', "%{$query}%")
            ->orWhere('venue', 'like', "%{$query}%")
            ->orderByDesc('match_date')
            ->take(5)
            ->get();

        $news = NewsPost::where('title', 'like', "%{$query}%")
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        return response()->json([
            'players' => $players->map(fn ($p) => [
                'url' => route('players.show', $p),
                'name' => $p->display_name,
                'role' => $p->display_role,
                'photo' => $p->photo_url,
            ]),
            'matches' => $matches->map(fn ($m) => [
                'url' => route('matches.show', $m),
                'label' => __('site.match') . ' ' . $m->match_number . ' ' . __('site.vs') . ' ' . $m->display_opponent_name,
                'date' => $m->match_date->format('d M Y'),
            ]),
            'news' => $news->map(fn ($n) => [
                'url' => route('news.show', $n),
                'title' => $n->display_title,
                'date' => $n->published_at?->format('d M Y'),
            ]),
        ]);
    }

    public function __invoke(Request $request)
    {
        $query = trim((string) $request->query('q'));

        $players = $matches = $news = collect();

        if ($query !== '') {
            $players = Player::where('name', 'like', "%{$query}%")->take(10)->get();
            $matches = CricketMatch::where('opponent_name', 'like', "%{$query}%")
                ->orWhere('venue', 'like', "%{$query}%")
                ->orderByDesc('match_date')
                ->take(10)
                ->get();
            $news = NewsPost::where('title', 'like', "%{$query}%")
                ->orWhere('content', 'like', "%{$query}%")
                ->orderByDesc('published_at')
                ->take(10)
                ->get();
        }

        return view('search.results', [
            'query' => $query,
            'players' => $players,
            'matches' => $matches,
            'news' => $news,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CricketMatch;
use App\Models\NewsPost;
use App\Models\Player;
use Illuminate\Http\Request;

class SearchController extends Controller
{
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

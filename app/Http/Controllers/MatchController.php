<?php

namespace App\Http\Controllers;

use App\Models\CricketMatch;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = CricketMatch::query();

        if ($status === 'completed') {
            $query->completed();
        } elseif ($status === 'upcoming') {
            $query->upcoming();
        } else {
            $query->orderByDesc('match_date');
        }

        return view('matches.index', [
            'status' => $status,
            'matches' => $query->paginate(10)->withQueryString(),
        ]);
    }

    public function show(CricketMatch $match)
    {
        return view('matches.show', ['match' => $match]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CricketMatch;
use Illuminate\Http\Request;

class ScoreboardController extends Controller
{
    public function index()
    {
        return view('admin.scoreboards.index', [
            'matches' => CricketMatch::orderByDesc('match_date')->paginate(10),
        ]);
    }

    public function edit(CricketMatch $match)
    {
        return view('admin.scoreboards.form', ['match' => $match]);
    }

    public function update(Request $request, CricketMatch $match)
    {
        $data = $request->validate([
            'status' => ['required', 'in:completed,upcoming'],
            'rcb_score' => ['nullable', 'string', 'max:50'],
            'rcb_overs' => ['nullable', 'string', 'max:50'],
            'opponent_score' => ['nullable', 'string', 'max:50'],
            'opponent_overs' => ['nullable', 'string', 'max:50'],
            'result_text' => ['nullable', 'string', 'max:255'],
            'won' => ['nullable', 'boolean'],
        ]) + [
            'won' => $request->boolean('won'),
        ];

        $match->update($data);

        return redirect()->route('admin.scoreboards.index')->with('success', __('site.admin.saved'));
    }
}

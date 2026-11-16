<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CricketMatch;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index()
    {
        return view('admin.matches.index', [
            'matches' => CricketMatch::orderByDesc('match_date')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.matches.form', ['match' => new CricketMatch()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['opponent_logo'] = $this->upload($request);

        CricketMatch::create($data);

        return redirect()->route('admin.matches.index')->with('success', __('site.admin.saved'));
    }

    public function edit(CricketMatch $match)
    {
        return view('admin.matches.form', ['match' => $match]);
    }

    public function update(Request $request, CricketMatch $match)
    {
        $data = $this->validated($request);
        $data['opponent_logo'] = $this->upload($request) ?? $match->opponent_logo;

        $match->update($data);

        return redirect()->route('admin.matches.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(CricketMatch $match)
    {
        $match->delete();

        return redirect()->route('admin.matches.index')->with('success', __('site.admin.deleted'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'match_number' => ['required', 'integer'],
            'opponent_name' => ['required', 'string', 'max:255'],
            'opponent_name_bn' => ['nullable', 'string', 'max:255'],
            'opponent_short_name' => ['nullable', 'string', 'max:10'],
            'venue' => ['nullable', 'string', 'max:255'],
            'match_date' => ['required', 'date'],
            'status' => ['required', 'in:completed,upcoming'],
            'format' => ['nullable', 'string', 'max:255'],
            'rcb_score' => ['nullable', 'string', 'max:50'],
            'rcb_overs' => ['nullable', 'string', 'max:50'],
            'opponent_score' => ['nullable', 'string', 'max:50'],
            'opponent_overs' => ['nullable', 'string', 'max:50'],
            'result_text' => ['nullable', 'string', 'max:255'],
            'won' => ['nullable', 'boolean'],
        ]) + [
            'won' => $request->boolean('won'),
        ];
    }

    private function upload(Request $request): ?string
    {
        if (! $request->hasFile('opponent_logo') || ! $request->file('opponent_logo')->isValid()) {
            return null;
        }

        $file = $request->file('opponent_logo');
        $filename = 'team_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/teams'), $filename);

        return 'uploads/teams/' . $filename;
    }
}

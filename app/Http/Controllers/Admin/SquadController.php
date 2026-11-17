<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CricketMatch;
use App\Models\Player;
use Illuminate\Http\Request;

class SquadController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = (string) $request->get('status', '');
        $limit = in_array((int) $request->get('limit', 10), [10, 25, 50, 100]) ? (int) $request->get('limit', 10) : 10;

        $query = CricketMatch::withCount('squad');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('opponent_name', 'like', "%{$search}%")
                    ->orWhere('opponent_name_bn', 'like', "%{$search}%");
            });
        }

        if (in_array($status, ['upcoming', 'completed', 'live', 'abandoned'])) {
            $query->where('status', $status);
        }

        $matches = $query->orderByDesc('match_date')
            ->paginate($limit)
            ->appends($request->query());

        return view('admin.squads.index', [
            'matches' => $matches,
            'filterSearch' => $search,
            'filterStatus' => $status,
            'filterLimit' => $limit,
        ]);
    }

    public function edit(CricketMatch $match)
    {
        return view('admin.squads.form', [
            'match' => $match,
            'players' => Player::whereNotNull('team_id')
                ->with('team')
                ->orderByDesc('is_captain')
                ->orderByDesc('is_vice_captain')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function update(Request $request, CricketMatch $match)
    {
        $ids = collect($request->input('squad', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->all();

        $match->squad()->sync($ids);

        return redirect()->route('admin.squads.index')->with('success', __('site.admin.saved'));
    }
}

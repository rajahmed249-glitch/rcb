<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = (string) $request->get('status', '');
        $limit = in_array((int) $request->get('limit', 10), [10, 25, 50, 100]) ? (int) $request->get('limit', 10) : 10;

        $query = Tournament::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_bn', 'like', "%{$search}%")
                    ->orWhere('season', 'like', "%{$search}%");
            });
        }

        if ($status !== '') {
            $query->where('is_active', $status === '1' ? 1 : 0);
        }

        $tournaments = $query->orderBy('sort_order')
            ->orderBy('id')
            ->paginate($limit)
            ->appends($request->query());

        return view('admin.tournaments.index', [
            'tournaments' => $tournaments,
            'filterSearch' => $search,
            'filterStatus' => $status,
            'filterLimit' => $limit,
        ]);
    }

    public function create()
    {
        return view('admin.tournaments.form', ['tournament' => new Tournament()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Tournament::uniqueSlug($request->input('slug') ?: $request->input('name'));

        Tournament::create($data);

        return redirect()->route('admin.tournaments.index')->with('success', __('site.admin.saved'));
    }

    public function edit(Tournament $tournament)
    {
        return view('admin.tournaments.form', ['tournament' => $tournament]);
    }

    public function update(Request $request, Tournament $tournament)
    {
        $data = $this->validated($request);

        if ($request->filled('slug') && $request->input('slug') !== $tournament->slug) {
            $data['slug'] = Tournament::uniqueSlug($request->input('slug'), $tournament->id);
        }

        $tournament->update($data);

        return redirect()->route('admin.tournaments.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();

        return redirect()->route('admin.tournaments.index')->with('success', __('site.admin.deleted'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_bn' => ['nullable', 'string', 'max:255'],
            'season' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]) + [
            'is_active' => $request->boolean('is_active'),
        ];
    }
}

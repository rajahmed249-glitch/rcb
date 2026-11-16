@extends('layouts.app')

@section('title', 'Records')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>Records</h1>
            <p>Team and individual records of REAL CHALLENGER BOYS.</p>
        </div>

        @php
            $highestTotal = $completed->sortByDesc(fn ($m) => $m->rcb_score ?? 0)->first();
            $biggestWin = $completed->where('won', true)->first();
        @endphp

        <section class="section">
            <div class="section-head"><h2>Team Records</h2></div>
            <div class="stats-grid">
                <div class="card stat-card">
                    <span class="stat-number">{{ $highestTotal?->rcb_score ?? '-' }}{{ $highestTotal ? '-'.$highestTotal->rcb_overs : '' }}</span>
                    <span class="stat-label">Highest Total</span>
                </div>
                <div class="card stat-card">
                    <span class="stat-number">{{ $completed->where('won', true)->count() }}</span>
                    <span class="stat-label">Total Wins</span>
                </div>
                <div class="card stat-card">
                    <span class="stat-number">{{ $completed->count() }}</span>
                    <span class="stat-label">Matches Played</span>
                </div>
                <div class="card stat-card">
                    <span class="stat-number">{{ $completed->where('won', true)->count() > 0 ? 'Yes' : '-' }}</span>
                    <span class="stat-label">Big Match Winners</span>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="section-head"><h2>Batting — Most Runs</h2></div>
            <div class="card table-card">
                <table class="record-table">
                    <thead><tr><th>#</th><th>Player</th><th>Role</th><th>Runs</th><th>Matches</th></tr></thead>
                    <tbody>
                        @forelse ($topRuns as $i => $player)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><a href="{{ route('players.show', $player) }}" class="text-link">{{ $player->name }}</a>@if ($player->badge) ({{ $player->badge }})@endif</td>
                                <td>{{ $player->role }}</td>
                                <td><strong>{{ $player->runs }}</strong></td>
                                <td>{{ $player->matches_played }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="muted">No records yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="section">
            <div class="section-head"><h2>Bowling — Most Wickets</h2></div>
            <div class="card table-card">
                <table class="record-table">
                    <thead><tr><th>#</th><th>Player</th><th>Role</th><th>Wickets</th><th>Matches</th></tr></thead>
                    <tbody>
                        @forelse ($topWickets as $i => $player)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><a href="{{ route('players.show', $player) }}" class="text-link">{{ $player->name }}</a>@if ($player->badge) ({{ $player->badge }})@endif</td>
                                <td>{{ $player->role }}</td>
                                <td><strong>{{ $player->wickets }}</strong></td>
                                <td>{{ $player->matches_played }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="muted">No records yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="section">
            <div class="section-head"><h2>Most Appearances</h2></div>
            <div class="card table-card">
                <table class="record-table">
                    <thead><tr><th>#</th><th>Player</th><th>Role</th><th>Matches</th><th>Runs</th></tr></thead>
                    <tbody>
                        @forelse ($topMatches as $i => $player)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><a href="{{ route('players.show', $player) }}" class="text-link">{{ $player->name }}</a>@if ($player->badge) ({{ $player->badge }})@endif</td>
                                <td>{{ $player->role }}</td>
                                <td><strong>{{ $player->matches_played }}</strong></td>
                                <td>{{ $player->runs }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="muted">No records yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

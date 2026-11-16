@extends('layouts.app')

@section('title', 'Records')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>{{ __('site.records.title') }}</h1>
            <p>{{ __('site.records.subtitle') }}</p>
        </div>

        @php
            $highestTotal = $completed->sortByDesc(fn ($m) => $m->rcb_score ?? 0)->first();
            $biggestWin = $completed->where('won', true)->first();
        @endphp

        <section class="section">
            <div class="section-head"><h2>{{ __('site.records.team_records') }}</h2></div>
            <div class="stats-grid">
                <div class="card stat-card">
                    <span class="stat-number">{{ $highestTotal?->rcb_score ?? '-' }}{{ $highestTotal ? '-'.$highestTotal->rcb_overs : '' }}</span>
                    <span class="stat-label">{{ __('site.records.highest_total') }}</span>
                </div>
                <div class="card stat-card">
                    <span class="stat-number">{{ $completed->where('won', true)->count() }}</span>
                    <span class="stat-label">{{ __('site.records.total_wins') }}</span>
                </div>
                <div class="card stat-card">
                    <span class="stat-number">{{ $completed->count() }}</span>
                    <span class="stat-label">{{ __('site.records.matches_played') }}</span>
                </div>
                <div class="card stat-card">
                    <span class="stat-number">{{ $completed->where('won', true)->count() > 0 ? 'Yes' : '-' }}</span>
                    <span class="stat-label">{{ __('site.records.big_match_winners') }}</span>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="section-head"><h2>{{ __('site.records.batting_most_runs') }}</h2></div>
            <div class="card table-card">
                <table class="record-table">
                    <thead><tr><th>#</th><th>{{ __('site.records.player') }}</th><th>{{ __('site.role') }}</th><th>{{ __('site.runs') }}</th><th>{{ __('site.matches') }}</th></tr></thead>
                    <tbody>
                        @forelse ($topRuns as $i => $player)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><a href="{{ route('players.show', $player) }}" class="text-link">{{ $player->display_name }}</a>@if ($player->badge) ({{ $player->badge }})@endif</td>
                                <td>{{ $player->display_role }}</td>
                                <td><strong>{{ $player->runs }}</strong></td>
                                <td>{{ $player->matches_played }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="muted">{{ __('site.records.no_records') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="section">
            <div class="section-head"><h2>{{ __('site.records.bowling_most_wickets') }}</h2></div>
            <div class="card table-card">
                <table class="record-table">
                    <thead><tr><th>#</th><th>{{ __('site.records.player') }}</th><th>{{ __('site.role') }}</th><th>{{ __('site.wickets') }}</th><th>{{ __('site.matches') }}</th></tr></thead>
                    <tbody>
                        @forelse ($topWickets as $i => $player)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><a href="{{ route('players.show', $player) }}" class="text-link">{{ $player->display_name }}</a>@if ($player->badge) ({{ $player->badge }})@endif</td>
                                <td>{{ $player->display_role }}</td>
                                <td><strong>{{ $player->wickets }}</strong></td>
                                <td>{{ $player->matches_played }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="muted">{{ __('site.records.no_records') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="section">
            <div class="section-head"><h2>{{ __('site.records.most_appearances') }}</h2></div>
            <div class="card table-card">
                <table class="record-table">
                    <thead><tr><th>#</th><th>{{ __('site.records.player') }}</th><th>{{ __('site.role') }}</th><th>{{ __('site.matches') }}</th><th>{{ __('site.runs') }}</th></tr></thead>
                    <tbody>
                        @forelse ($topMatches as $i => $player)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><a href="{{ route('players.show', $player) }}" class="text-link">{{ $player->display_name }}</a>@if ($player->badge) ({{ $player->badge }})@endif</td>
                                <td>{{ $player->display_role }}</td>
                                <td><strong>{{ $player->matches_played }}</strong></td>
                                <td>{{ $player->runs }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="muted">{{ __('site.records.no_records') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

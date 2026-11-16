@extends('layouts.app')

@section('title', $player->name . ' — Player Profile')

@section('content')
    <div class="container">
        <div class="card" style="max-width: 720px;">
            <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">
                <div class="player-avatar" style="width:110px;height:110px;font-size:2rem;">
                    {{ $player->initials }}
                    @if ($player->badge)
                        <span class="badge-role">{{ $player->badge }}</span>
                    @endif
                </div>
                <div>
                    <h1>{{ $player->name }}@if ($player->badge) ({{ $player->badge }})@endif</h1>
                    <p class="muted">{{ $player->role }} @if ($player->jersey_number) · #{{ $player->jersey_number }} @endif</p>
                </div>
            </div>

            <hr style="border-color: var(--border); margin: 1.5rem 0;">

            <h2 style="margin-bottom: 1rem;">Player Stats</h2>
            <div class="player-stats" style="justify-content:flex-start; gap:2rem; font-size:1rem;">
                <span>Matches: <strong>{{ $player->matches_played }}</strong></span>
                <span>Runs: <strong>{{ $player->runs }}</strong></span>
                <span>Wickets: <strong>{{ $player->wickets }}</strong></span>
            </div>

            <ul class="footer-links" style="margin-top: 1.25rem;">
                @if ($player->batting_style)<li>🏏 Batting: {{ $player->batting_style }}</li>@endif
                @if ($player->bowling_style)<li>🎯 Bowling: {{ $player->bowling_style }}</li>@endif
            </ul>

            @if ($player->bio)
                <p class="article-content muted" style="margin-top: 1.25rem;">{{ $player->bio }}</p>
            @endif
        </div>

        <p style="margin-top: 2rem;"><a href="{{ route('players.index') }}" class="btn btn-outline">← Back to Players</a></p>
    </div>
@endsection

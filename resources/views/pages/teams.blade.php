@extends('layouts.app')

@section('title', 'Team')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>Team</h1>
            <p>The REAL CHALLENGER BOYS squad — every player, every role.</p>
        </div>

        @php
            $groups = $squad->groupBy('role');
        @endphp

        @foreach (['Batsman', 'Bowler', 'All-rounder', 'Wicketkeeper'] as $role)
            @if ($groups->has($role))
                <section class="section">
                    <div class="section-head"><h2>{{ $role }}s</h2></div>
                    <div class="player-grid">
                        @foreach ($groups[$role] as $player)
                            <div class="card player-card">
                                <div class="player-avatar">
                                    {{ $player->initials }}
                                    @if ($player->badge)
                                        <span class="badge-role">{{ $player->badge }}</span>
                                    @endif
                                </div>
                                <h3>{{ $player->name }}@if ($player->badge) ({{ $player->badge }})@endif</h3>
                                <p class="muted" style="font-size:.85rem;">{{ $player->role }}</p>
                                <div class="player-stats">
                                    <span>M: <strong>{{ $player->matches_played }}</strong></span>
                                    <span>R: <strong>{{ $player->runs }}</strong></span>
                                    <span>W: <strong>{{ $player->wickets }}</strong></span>
                                </div>
                                <a href="{{ route('players.show', $player) }}" class="btn btn-outline">View Profile</a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endforeach

        @foreach ($groups->except(['Batsman', 'Bowler', 'All-rounder', 'Wicketkeeper']) as $role => $group)
            <section class="section">
                <div class="section-head"><h2>{{ $role }}</h2></div>
                <div class="player-grid">
                    @foreach ($group as $player)
                        <div class="card player-card">
                            <div class="player-avatar">{{ $player->initials }}</div>
                            <h3>{{ $player->name }}</h3>
                            <p class="muted" style="font-size:.85rem;">{{ $player->role }}</p>
                            <a href="{{ route('players.show', $player) }}" class="btn btn-outline">View Profile</a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
@endsection

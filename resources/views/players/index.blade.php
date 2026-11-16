@extends('layouts.app')

@section('title', 'Players')

@section('content')
    <div class="container">
        <div class="players-head">
            <h1 class="players-title">Players</h1>
            <div class="player-filters">
                <a href="{{ route('players.index') }}" class="filter-btn {{ $role ? '' : 'active' }}">All</a>
                <a href="{{ route('players.index', ['role' => 'Batsman']) }}" class="filter-btn {{ $role === 'Batsman' ? 'active' : '' }}">&#127951; Batsman</a>
                <a href="{{ route('players.index', ['role' => 'Bowler']) }}" class="filter-btn {{ $role === 'Bowler' ? 'active' : '' }}">&#127919; Bowler</a>
                <a href="{{ route('players.index', ['role' => 'All-rounder']) }}" class="filter-btn {{ $role === 'All-rounder' ? 'active' : '' }}">&#9889; All-rounder</a>
                <a href="{{ route('players.index', ['role' => 'Wicketkeeper']) }}" class="filter-btn {{ $role === 'Wicketkeeper' ? 'active' : '' }}">&#129524; Wicketkeeper</a>
                @if ($role)
                    <a href="{{ route('players.index') }}" class="filter-btn clear-btn">&#10005; Clear</a>
                @endif
            </div>
        </div>

        <div class="pcard-grid">
            @forelse ($players as $player)
                <div class="pcard">
                    <div class="pcard-img-wrap">
                        @if ($player->badge)
                            <span class="pcard-tag">{{ $player->badge === 'c' ? 'Captain' : 'Vice Captain' }}</span>
                        @endif
                        @if ($player->photo_url)
                            <img src="{{ $player->photo_url }}" alt="{{ $player->name }}" loading="lazy">
                        @else
                            <div class="pcard-initials">{{ $player->initials }}</div>
                        @endif
                        <div class="pcard-overlay">
                            <h4>Player Stats</h4>
                            <div class="pcard-stats">
                                <div class="pcard-stat"><span class="pcard-statnum">{{ $player->matches_played }}</span><span class="pcard-statlabel">Matches</span></div>
                                <div class="pcard-stat"><span class="pcard-statnum">{{ $player->runs }}</span><span class="pcard-statlabel">Runs</span></div>
                                <div class="pcard-stat"><span class="pcard-statnum">{{ $player->wickets }}</span><span class="pcard-statlabel">Wickets</span></div>
                            </div>
                            <a href="{{ route('players.show', $player) }}" class="pcard-viewbtn">View Profile &rarr;</a>
                        </div>
                    </div>
                    <div class="pcard-bottom">
                        <div class="pcard-roleicon">{{ $player->role === 'Bowler' ? '&#127919;' : ($player->role === 'Wicketkeeper' ? '&#129524;' : ($player->role === 'All-rounder' ? '&#9889;' : '&#127951;')) }}</div>
                        <div class="pcard-name">{{ $player->name }}@if ($player->badge === 'c') <span class="pcard-star">&#11088;</span>@endif</div>
                        <span class="pcard-rolepill">{{ $player->role }}</span>
                    </div>
                </div>
            @empty
                <div class="card"><p class="muted">No players found.</p></div>
            @endforelse
        </div>

        {{ $players->links() }}
    </div>
@endsection

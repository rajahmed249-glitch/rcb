@extends('layouts.app')

@section('title', 'Players')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>Players</h1>
            <p>The squad of REAL CHALLENGER BOYS.</p>
        </div>

        <div class="player-grid">
            @forelse ($players as $player)
                <div class="card player-card">
                    <div class="player-avatar">
                        @if ($player->photo_url)
                            <img src="{{ $player->photo_url }}" alt="{{ $player->name }}" loading="lazy">
                        @else
                            {{ $player->initials }}
                        @endif
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
            @empty
                <div class="card"><p class="muted">No players added yet.</p></div>
            @endforelse
        </div>

        {{ $players->links() }}
    </div>
@endsection

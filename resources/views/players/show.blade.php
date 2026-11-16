@extends('layouts.app')

@section('title', $player->name . ' — Player Profile')

@section('content')
    <div class="container">
        <div class="pprofile">
            <div class="pprofile-grid">
                <div class="pimg-wrap">
                    <div class="pimg-glow"></div>
                    <div class="pimg-box">
                        @if ($player->photo_url)
                            <img src="{{ $player->photo_url }}" alt="{{ $player->name }}">
                        @else
                            <div class="pimg-initials">{{ $player->initials }}</div>
                        @endif
                    </div>
                </div>

                <div class="pinfo-wrap">
                    <div class="pname-wrap">
                        <h1>{{ $player->name }}</h1>
                        <div class="pname-underline"></div>
                    </div>

                    <div class="prole-badge"><span>{{ $player->role }}@if ($player->badge) ({{ strtoupper($player->badge) }})@endif</span></div>

                    <div class="pstyle-badges">
                        @if ($player->batting_style)<span class="pbadge pbadge-bat">&#127951; {{ $player->batting_style }}</span>@endif
                        @if ($player->bowling_style)<span class="pbadge pbadge-bowl">&#127919; {{ $player->bowling_style }}</span>@endif
                        @if ($player->jersey_number)<span class="pbadge pbadge-jersey">#{{ $player->jersey_number }}</span>@endif
                    </div>

                    <div class="pdetails-grid">
                        <div class="pdetail-card">
                            <div class="pdetail-label">Full Name</div>
                            <div class="pdetail-value">{{ $player->name }}</div>
                        </div>
                        <div class="pdetail-card">
                            <div class="pdetail-label">Team</div>
                            <div class="pdetail-value">Real Challenger Boys</div>
                        </div>
                        <div class="pdetail-card">
                            <div class="pdetail-label">Role</div>
                            <div class="pdetail-value">{{ $player->role }}</div>
                        </div>
                    </div>

                    <div class="pstats-grid">
                        <div class="pstat-item">
                            <div class="pstat-iconbox teal">&#128197;</div>
                            <div class="pstat-number teal">{{ $player->matches_played }}</div>
                            <div class="pstat-label">Matches</div>
                        </div>
                        <div class="pstat-item">
                            <div class="pstat-iconbox red">&#128200;</div>
                            <div class="pstat-number red">{{ $player->runs }}</div>
                            <div class="pstat-label">Runs</div>
                        </div>
                        <div class="pstat-item">
                            <div class="pstat-iconbox amber">&#11088;</div>
                            <div class="pstat-number amber">{{ $player->wickets }}</div>
                            <div class="pstat-label">Wickets</div>
                        </div>
                    </div>

                    @if ($player->bio)
                        <p class="pbio">{{ $player->bio }}</p>
                    @endif
                </div>
            </div>
        </div>

        <p style="margin-top: 2rem; text-align: center;">
            <a href="{{ route('players.index') }}" class="btn btn-outline">&larr; Back to Players</a>
        </p>
    </div>
@endsection

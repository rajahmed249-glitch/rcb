@extends('layouts.app')

@section('title', 'Players')

@section('content')
    <div class="container players-page">
        <div class="players-head">
            <h1 class="players-title">{{ __('site.players') }}</h1>
            <div class="player-filters">
                <a href="{{ route('players.index') }}" class="filter-btn {{ $role ? '' : 'active' }}">{{ __('site.all') }}</a>
                <a href="{{ route('players.index', ['role' => 'Batsman']) }}" class="filter-btn {{ $role === 'Batsman' ? 'active' : '' }}">&#127951; {{ __('site.roles.batsman') }}</a>
                <a href="{{ route('players.index', ['role' => 'Bowler']) }}" class="filter-btn {{ $role === 'Bowler' ? 'active' : '' }}">&#127919; {{ __('site.roles.bowler') }}</a>
                <a href="{{ route('players.index', ['role' => 'All-rounder']) }}" class="filter-btn {{ $role === 'All-rounder' ? 'active' : '' }}">&#9889; {{ __('site.roles.all_rounder') }}</a>
                <a href="{{ route('players.index', ['role' => 'Wicketkeeper']) }}" class="filter-btn {{ $role === 'Wicketkeeper' ? 'active' : '' }}">&#129524; {{ __('site.roles.wicketkeeper') }}</a>
                @if ($role)
                    <a href="{{ route('players.index') }}" class="filter-btn clear-btn">&#10005; {{ __('site.clear') }}</a>
                @endif
            </div>
        </div>

        <div class="pcard-grid">
            @forelse ($players as $player)
                <div class="pcard">
                    <div class="pcard-img-wrap">

                        @if ($player->photo_url)
                            <img src="{{ $player->photo_url }}" alt="{{ $player->name }}" loading="lazy">
                        @else
                            <div class="pcard-initials">{{ $player->initials }}</div>
                        @endif
                        <div class="pcard-overlay">
                            <h4>{{ __('site.player_stats') }}</h4>
                            <div class="pcard-stats">
                                <div class="pcard-stat"><span class="pcard-statnum">{{ $player->matches_played }}</span><span class="pcard-statlabel">{{ __('site.matches') }}</span></div>
                                <div class="pcard-stat"><span class="pcard-statnum">{{ $player->runs }}</span><span class="pcard-statlabel">{{ __('site.runs') }}</span></div>
                                <div class="pcard-stat"><span class="pcard-statnum">{{ $player->wickets }}</span><span class="pcard-statlabel">{{ __('site.wickets') }}</span></div>
                            </div>
                            <a href="{{ route('players.show', $player) }}" class="pcard-viewbtn">{{ __('site.view_profile') }} &rarr;</a>
                        </div>
                    </div>
                    <div class="pcard-bottom">
                        <div class="pcard-roleicon">
                            @if ($player->role_icon_path)
                                <img src="{{ $player->role_icon_path }}" alt="" class="pcard-icon-img">
                            @else
                                {{ $player->role === 'Bowler' ? '&#127919;' : ($player->role === 'Wicketkeeper' ? '&#129524;' : ($player->role === 'All-rounder' ? '&#9889;' : '&#127951;')) }}
                            @endif
                        </div>
                        <div class="pcard-name">{{ $player->display_name }}@if ($player->badge === 'c') <span class="pcard-star">&#11088;</span>@endif</div>
                        <span class="pcard-rolepill">{{ $player->display_role }}</span>
                    </div>
                </div>
            @empty
                <div class="card"><p class="muted">{{ __('site.no_players') }}</p></div>
            @endforelse
        </div>

        {{ $players->links() }}
    </div>
@endsection

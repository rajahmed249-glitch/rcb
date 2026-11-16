@extends('layouts.app')

@section('title', 'Team')

@section('content')
    <div class="container">
        <div class="teams-head">
            <span class="teams-headbar"></span>
            <div>
                <h1 class="teams-title">{{ __('site.teams_page.title') }}</h1>
                <p class="teams-sub">{{ __('site.teams_page.subtitle') }}</p>
            </div>
        </div>

        <div class="tcard-grid">
            @foreach ($teams as $team)
                <a href="{{ route('pages.team-profile', $team['slug']) }}" class="tcard">
                    <div class="tcard-logo-row">
                        <div class="tcard-logo-ring">
                            @if ($team['logo'])
                                <img src="{{ asset($team['logo']) }}" alt="{{ $team['name'] }}" class="tcard-logo">
                            @else
                                <div class="tcard-logo-fallback">{{ mb_strtoupper(mb_substr($team['name'], 0, 2)) }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="tcard-body">
                        <h3 class="tcard-name">{{ $team['display_name'] }}</h3>
                        @if ($team['short_name'])
                            <p class="tcard-short">{{ $team['short_name'] }}</p>
                        @endif
                        <div class="tcard-divider"></div>
                        <div class="tcard-pills">
                            <span class="tpill tpill-gray">{{ $team['played'] }} {{ __('site.matches') }}</span>
                            <span class="tpill tpill-win">{{ $team['won'] }} {{ __('site.won') }}</span>
                            <span class="tpill tpill-loss">{{ $team['lost'] }} {{ __('site.lost') }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection

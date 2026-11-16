@extends('layouts.app')

@section('title', 'Team')

@section('content')
    <div class="container">
        <div class="teams-head">
            <span class="teams-headbar"></span>
            <div>
                <h1 class="teams-title">All Teams</h1>
                <p class="teams-sub">Squad overview &amp; team profiles</p>
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
                        <h3 class="tcard-name">{{ $team['name'] }}</h3>
                        @if ($team['short_name'])
                            <p class="tcard-short">{{ $team['short_name'] }}</p>
                        @endif
                        <div class="tcard-divider"></div>
                        <div class="tcard-pills">
                            <span class="tpill tpill-gray">{{ $team['played'] }} Matches</span>
                            <span class="tpill tpill-win">{{ $team['won'] }} Won</span>
                            <span class="tpill tpill-loss">{{ $team['lost'] }} Lost</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection

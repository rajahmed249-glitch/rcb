@extends('layouts.app')

@section('title', $team['name'] . ' — Team Profile')

@section('content')
    <div class="container">
        <div class="tprof-header">
            <div class="tprof-header-inner">
                <div class="tprof-logo-ring">
                    @if ($team['logo'])
                        <img src="{{ asset($team['logo']) }}" alt="{{ $team['name'] }}" class="tprof-logo">
                    @else
                        <div class="tprof-logo-fallback">{{ mb_strtoupper(mb_substr($team['name'], 0, 2)) }}</div>
                    @endif
                </div>
                <div class="tprof-info">
                    <h1 class="tprof-name">{{ $team['name'] }}
                        @if ($team['short_name'])
                            <span class="tprof-short">({{ $team['short_name'] }})</span>
                        @endif
                    </h1>
                    @if ($team['location'])
                        <p class="tprof-location">&#128205; {{ $team['location'] }}</p>
                    @endif
                </div>
            </div>
        </div>

        @php
            $noResult = max(0, $team['played'] - $team['won'] - $team['lost']);
            $winRatio = $team['played'] > 0 ? round($team['won'] / $team['played'] * 100) : 0;
        @endphp

        <div class="tprof-stats">
            <div class="tstat tstat-gray"><h3>Matches</h3><p>{{ $team['played'] }}</p></div>
            <div class="tstat tstat-emerald"><h3>Wins</h3><p>{{ $team['won'] }}</p></div>
            <div class="tstat tstat-rose"><h3>Losses</h3><p>{{ $team['lost'] }}</p></div>
            <div class="tstat tstat-amber"><h3>No Result</h3><p>{{ $noResult }}</p></div>
            <div class="tstat tstat-lime"><h3>Win Ratio</h3><p>{{ $winRatio }}%</p></div>
        </div>

        <div class="tprof-tabs">
            <button class="tprof-tab active" data-tab="overview">Overview</button>
            @if ($team['is_rcb'])
                <button class="tprof-tab" data-tab="players">Players</button>
                <button class="tprof-tab" data-tab="stats">Statistics</button>
            @endif
            <button class="tprof-tab" data-tab="matches">Matches</button>
        </div>

        <div class="tprof-panel active" id="tp-tab-overview">
            <h2>About the Team</h2>
            @if ($team['is_rcb'])
                <p>Real Challenger Boys (RCB) is a cricket team from Patuakhali, Bangladesh. Play with passion, win with honor &mdash; follow every match, every player and every moment of the RCB journey.</p>
            @else
                <p>{{ $team['name'] }} has played {{ $team['played'] }} completed match(es) against Real Challenger Boys.</p>
            @endif
        </div>

        @if ($team['is_rcb'])
            <div class="tprof-panel" id="tp-tab-players">
                <div class="pcard-grid">
                    @foreach ($players as $player)
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
                                <a href="{{ route('players.show', $player) }}" class="pcard-name">{{ $player->name }}@if ($player->badge === 'c') <span class="pcard-star">&#11088;</span>@endif</a>
                                <span class="pcard-rolepill">{{ $player->role }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="tprof-panel" id="tp-tab-stats">
                <div class="tprof-stats-cols">
                    <div class="tprof-statcol">
                        <h3>Most Runs</h3>
                        <ul class="tprof-statlist">
                            @forelse ($topRuns as $p)
                                <li><a href="{{ route('players.show', $p) }}">{{ $p->name }}</a><span>{{ $p->runs }}</span></li>
                            @empty
                                <li class="muted">No data yet.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tprof-statcol">
                        <h3>Most Wickets</h3>
                        <ul class="tprof-statlist">
                            @forelse ($topWickets as $p)
                                <li><a href="{{ route('players.show', $p) }}">{{ $p->name }}</a><span>{{ $p->wickets }}</span></li>
                            @empty
                                <li class="muted">No data yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="tprof-panel" id="tp-tab-matches">
            <div class="tprof-matchlist">
                @forelse ($teamMatches as $m)
                    <a href="{{ route('matches.show', $m) }}" class="tprof-matchrow">
                        <span class="tmr-matchno">Match {{ $m->match_number }}</span>
                        <span class="tmr-teams">RCB vs {{ $m->opponent_name }}</span>
                        <span class="tmr-date">{{ $m->match_date->format('d M Y') }}</span>
                        <span class="tmr-result {{ $m->status === 'completed' ? ($m->won ? 'win' : 'loss') : '' }}">
                            {{ $m->status === 'completed' ? $m->result_text : ucfirst($m->status) }}
                        </span>
                    </a>
                @empty
                    <p class="muted">No matches found.</p>
                @endforelse
            </div>
        </div>

        <p style="margin-top: 2rem; text-align: center;">
            <a href="{{ route('pages.teams') }}" class="btn btn-outline">&larr; Back to All Teams</a>
        </p>
    </div>
@endsection

@extends('layouts.app')

@section('title', $team['name'] . ' — Team Profile')

@section('content')
    <div class="container">
        <div class="tprof-header">
            <div class="tprof-header-inner">
                <div class="tprof-logo-ring">
                    @if ($team['logo'])
                        <img src="{{ asset($team['logo']) }}" alt="{{ $team['display_name'] }}" class="tprof-logo">
                    @else
                        <div class="tprof-logo-fallback">{{ mb_strtoupper(mb_substr($team['name'], 0, 2)) }}</div>
                    @endif
                </div>
                <div class="tprof-info">
                    <h1 class="tprof-name">{{ $team['display_name'] }}
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
            <div class="tstat tstat-gray"><h3>{{ __('site.matches') }}</h3><p>{{ $team['played'] }}</p></div>
            <div class="tstat tstat-emerald"><h3>{{ __('site.team_profile.wins') }}</h3><p>{{ $team['won'] }}</p></div>
            <div class="tstat tstat-rose"><h3>{{ __('site.team_profile.losses') }}</h3><p>{{ $team['lost'] }}</p></div>
            <div class="tstat tstat-amber"><h3>{{ __('site.team_profile.no_result') }}</h3><p>{{ $noResult }}</p></div>
            <div class="tstat tstat-lime"><h3>{{ __('site.team_profile.win_ratio') }}</h3><p>{{ $winRatio }}%</p></div>
        </div>

        <div class="tprof-tabs">
            <button class="tprof-tab active" data-tab="overview">{{ __('site.team_profile.overview') }}</button>
            @if ($team['is_rcb'])
                <button class="tprof-tab" data-tab="players">{{ __('site.players') }}</button>
                <button class="tprof-tab" data-tab="stats">{{ __('site.team_profile.statistics') }}</button>
            @endif
            <button class="tprof-tab" data-tab="matches">{{ __('site.matches') }}</button>
        </div>

        <div class="tprof-panel active" id="tp-tab-overview">
            <h2>{{ __('site.team_profile.about_the_team') }}</h2>
            @if ($team['is_rcb'])
                <p>{{ __('site.team_profile.about_rcb') }}</p>
            @else
                <p>{{ __('site.team_profile.about_other', ['name' => $team['display_name'], 'count' => $team['played']]) }}</p>
            @endif
        </div>

        @if ($team['is_rcb'])
            <div class="tprof-panel" id="tp-tab-players">
                <div class="pcard-grid tp-player-grid" id="tpPlayerGrid">
                    @foreach ($players as $player)
                        <div class="pcard">
                            <div class="pcard-img-wrap">
                                @if ($player->badge)
                                    <span class="pcard-tag">{{ $player->badge === 'c' ? __('site.captain') : __('site.vice_captain') }}</span>
                                @endif
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
                                <a href="{{ route('players.show', $player) }}" class="pcard-name">{{ $player->display_name }}@if ($player->badge === 'c') <span class="pcard-star">&#11088;</span>@endif</a>
                                <span class="pcard-rolepill">{{ $player->display_role }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="tp-pager" id="tpPlayerPager"></div>
            </div>

            <div class="tprof-panel" id="tp-tab-stats">
                <div class="tprof-stats-cols">
                    <div class="tprof-statcol">
                        <h3>{{ __('site.team_profile.most_runs') }}</h3>
                        <ul class="tprof-statlist">
                            @forelse ($topRuns as $p)
                                <li data-rank="{{ $loop->iteration }}"><a href="{{ route('players.show', $p) }}">{{ $p->display_name }}</a><span>{{ $p->runs }}</span></li>
                            @empty
                                <li class="muted">{{ __('site.no_data') }}</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tprof-statcol">
                        <h3>{{ __('site.team_profile.most_wickets') }}</h3>
                        <ul class="tprof-statlist">
                            @forelse ($topWickets as $p)
                                <li data-rank="{{ $loop->iteration }}"><a href="{{ route('players.show', $p) }}">{{ $p->display_name }}</a><span>{{ $p->wickets }}</span></li>
                            @empty
                                <li class="muted">{{ __('site.no_data') }}</li>
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
                        <span class="tmr-teams">RCB {{ __('site.vs') }} {{ $m->display_opponent_name }}</span>
                        <span class="tmr-date">{{ $m->match_date->translatedFormat('d M Y') }}</span>
                        <span class="tmr-result {{ $m->status === 'completed' ? ($m->won ? 'win' : 'loss') : '' }}">
                            {{ $m->status === 'completed' ? $m->result_text : ucfirst($m->status) }}
                        </span>
                    </a>
                @empty
                    <p class="muted">{{ __('site.no_matches') }}</p>
                @endforelse
            </div>
        </div>

        <p style="margin-top: 2rem; text-align: center;">
            <a href="{{ route('pages.teams') }}" class="btn btn-outline">{{ __('site.back_to_teams') }}</a>
        </p>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var grid = document.getElementById('tpPlayerGrid');
        var pager = document.getElementById('tpPlayerPager');
        if (!grid || !pager) return;

        var cards = Array.prototype.slice.call(grid.querySelectorAll('.pcard'));
        var perPage = 12;
        var totalPages = Math.max(1, Math.ceil(cards.length / perPage));
        var current = 1;

        if (cards.length <= perPage) { pager.style.display = 'none'; }

        function render() {
            cards.forEach(function (card, i) {
                var page = Math.floor(i / perPage) + 1;
                card.style.display = page === current ? '' : 'none';
            });
            var html = '';
            for (var p = 1; p <= totalPages; p++) {
                html += '<button type="button" class="tp-page-btn' + (p === current ? ' active' : '') + '" data-page="' + p + '">' + p + '</button>';
            }
            pager.innerHTML = html;
        }

        pager.addEventListener('click', function (e) {
            var btn = e.target.closest('.tp-page-btn');
            if (!btn) return;
            current = parseInt(btn.getAttribute('data-page'), 10);
            render();
            grid.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });

        render();
    });
    </script>
@endsection

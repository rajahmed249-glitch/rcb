@extends('admin.layout')

@section('title', __('site.admin.dashboard'))

@section('content')
<div class="dash-head">
    <div>
        <h1>{{ __('site.admin.dashboard') }}</h1>
        <p>{{ __('site.admin.welcome_text') }}</p>
    </div>
    <div class="dash-date">
        <span class="admin-ico"><i class="fas fa-calendar-alt"></i></span>
        <span>{{ now()->translatedFormat('l, d M Y') }}</span>
    </div>
</div>

<div class="stat-cards">
    <a href="{{ route('admin.players.index') }}" class="stat-card teal">
        <div class="sc-top">
            <div>
                <p class="sc-label">{{ __('site.admin.total_players') }}</p>
                <p class="sc-num">{{ number_format($playersCount) }}</p>
            </div>
            <div class="icon-bg"><span class="admin-ico"><i class="fas fa-users"></i></span></div>
        </div>
        <div class="glow"></div>
    </a>
    <a href="{{ route('admin.teams.index') }}" class="stat-card blue">
        <div class="sc-top">
            <div>
                <p class="sc-label">{{ __('site.admin.total_teams') }}</p>
                <p class="sc-num">{{ number_format($teamsCount) }}</p>
            </div>
            <div class="icon-bg"><span class="admin-ico"><i class="fas fa-shield-alt"></i></span></div>
        </div>
        <div class="glow"></div>
    </a>
    <a href="{{ route('admin.matches.index') }}" class="stat-card purple">
        <div class="sc-top">
            <div>
                <p class="sc-label">{{ __('site.admin.total_matches') }}</p>
                <p class="sc-num">{{ number_format($matchesCount) }}</p>
            </div>
            <div class="icon-bg"><span class="admin-ico"><i class="fas fa-calendar-alt"></i></span></div>
        </div>
        <div class="glow"></div>
    </a>
    <a href="{{ route('admin.news.index') }}" class="stat-card orange">
        <div class="sc-top">
            <div>
                <p class="sc-label">{{ __('site.admin.total_news') }}</p>
                <p class="sc-num">{{ number_format($newsCount) }}</p>
            </div>
            <div class="icon-bg"><span class="admin-ico"><i class="fas fa-newspaper"></i></span></div>
        </div>
        <div class="glow"></div>
    </a>
</div>

<div class="dash-mid">
    <div class="dash-card dash-card-pad">
        <h3 class="dash-card-title"><span class="admin-ico"><i class="fas fa-chart-pie"></i></span>{{ __('site.admin.match_statistics') }}</h3>
        <div class="ms-grid">
            <div class="ms-box green">
                <span class="num">{{ $stats['completed'] }}</span>
                <span class="lbl">{{ __('site.admin.completed') }}</span>
            </div>
            <div class="ms-box yellow">
                <span class="num">{{ $stats['upcoming'] }}</span>
                <span class="lbl">{{ __('site.admin.upcoming') }}</span>
            </div>
            <div class="ms-box red">
                <span class="num">{{ $stats['live'] }}</span>
                <span class="lbl">{{ __('site.admin.live') }}</span>
            </div>
            <div class="ms-box gray">
                <span class="num">{{ $stats['abandoned'] }}</span>
                <span class="lbl">{{ __('site.admin.abandoned') }}</span>
            </div>
            <div class="ms-box blue">
                <span class="num">{{ $stats['total'] }}</span>
                <span class="lbl">{{ __('site.admin.total') }}</span>
            </div>
        </div>
    </div>

    <div class="dash-card dash-card-pad">
        <h3 class="dash-card-title"><span class="admin-ico"><i class="fas fa-bolt"></i></span>{{ __('site.admin.quick_actions') }}</h3>
        <div class="qa-grid">
            <a href="{{ route('admin.players.create') }}" class="qa-tile teal">
                <span class="admin-ico"><i class="fas fa-user-plus"></i></span>
                <span class="lbl">{{ __('site.admin.add_player') }}</span>
            </a>
            <a href="{{ route('admin.teams.create') }}" class="qa-tile blue">
                <span class="admin-ico"><i class="fas fa-plus-circle"></i></span>
                <span class="lbl">{{ __('site.admin.add_team') }}</span>
            </a>
            <a href="{{ route('admin.matches.create') }}" class="qa-tile green">
                <span class="admin-ico"><i class="fas fa-calendar-plus"></i></span>
                <span class="lbl">{{ __('site.admin.schedule_match') }}</span>
            </a>
            <a href="{{ route('admin.news.create') }}" class="qa-tile purple">
                <span class="admin-ico"><i class="fas fa-edit"></i></span>
                <span class="lbl">{{ __('site.admin.add_news') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="dash-card" style="margin-bottom: 1.5rem;">
    <div class="rm-head">
        <h3>
            <span class="admin-ico"><i class="fas fa-history"></i></span>
            {{ __('site.admin.recent_matches') }}
            <span class="count-chip">{{ $recentMatches->count() }} {{ __('site.admin.matches') }}</span>
        </h3>
        <a href="{{ route('admin.matches.index') }}">{{ __('site.view_all') }} <i class="fas fa-arrow-right" style="font-size:.6rem;"></i></a>
    </div>
    @if ($recentMatches->isEmpty())
        <div class="rm-empty">
            <div class="rm-empty-ico"><i class="fas fa-calendar-alt"></i></div>
            <p>{{ __('site.no_matches') }}</p>
            <a href="{{ route('admin.matches.create') }}" style="color:#0d9488;font-size:.85rem;text-decoration:none;font-weight:500;">{{ __('site.admin.add_match') }}</a>
        </div>
    @else
        <div class="rm-list">
            @foreach ($recentMatches as $match)
                <div class="rm-row">
                    <div style="display:flex;flex-direction:column;gap:.5rem;">
                        <div class="rm-meta">
                            <span class="rm-id">#{{ $match->match_number ?? $match->id }}</span>
                            <span class="rm-date"><i class="far fa-calendar-alt"></i> {{ $match->match_date?->translatedFormat('d M Y') ?? 'TBD' }}</span>
                            @if ($match->venue)
                                <span class="rm-venue"><i class="fas fa-map-pin"></i> {{ Str::limit($match->venue, 20) }}</span>
                            @endif
                        </div>
                        <div class="rm-teams">
                            <div class="rm-team">
                                <img src="{{ asset('images/teams/real-challenger-boys.webp') }}" alt="RCB">
                                <a href="{{ route('pages.teams') }}" target="_blank">REAL CHALLENGER BOYS</a>
                            </div>
                            <span class="rm-vs">VS</span>
                            <div class="rm-team">
                                @if ($match->opponent_logo)
                                    <img src="{{ asset($match->opponent_logo) }}" alt="{{ $match->display_opponent_name }}">
                                @else
                                    <span class="rm-fallback">{{ Str::upper(Str::substr($match->display_opponent_name, 0, 2)) }}</span>
                                @endif
                                <a href="{{ $match->opponent_team_id ? route('pages.team-profile', $match->opponentTeam->slug) : '#' }}" @unless($match->opponent_team_id) onclick="return false;" @endunless title="{{ $match->display_opponent_name }}">{{ $match->display_opponent_name }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="rm-side">
                        <div class="rm-score">
                            <div class="val">{{ $match->rcb_score ?: '-' }} <small>-</small> {{ $match->opponent_score ?: '-' }}</div>
                            <div class="ov">{{ $match->rcb_overs ? '(' . $match->rcb_overs . ' ov)' : '' }} {{ $match->opponent_overs ? '(' . $match->opponent_overs . ' ov)' : '' }}</div>
                        </div>
                        <div class="rm-status">
                            <span class="rm-badge {{ $match->status }}">
                                <span class="status-dot {{ $match->status }}"></span>
                                {{ __('site.' . $match->status) }}
                            </span>
                            @if ($match->result_text)
                                <span class="rm-result" title="{{ $match->result_text }}">{{ Str::limit($match->result_text, 25) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="dash-bottom">
    <div class="dash-card">
        <div class="rm-head" style="background:linear-gradient(90deg, rgba(249,250,251,.8), #fff);">
            <h3>
                <span class="admin-ico" style="color:#f97316;"><i class="fas fa-newspaper"></i></span>
                {{ __('site.admin.recent_news') }}
            </h3>
            <a href="{{ route('admin.news.index') }}">{{ __('site.view_all') }} <i class="fas fa-arrow-right" style="font-size:.6rem;"></i></a>
        </div>
        <div class="rm-list">
            @if ($recentNews->isEmpty())
                <div class="rn-empty"><i class="fas fa-newspaper" style="font-size:1.4rem;display:block;margin-bottom:.5rem;color:#d1d5db;"></i>{{ __('site.admin.no_news') }}</div>
            @else
                @foreach ($recentNews as $news)
                    <div class="rn-row">
                        <div style="flex:1;min-width:0;">
                            <p class="t" title="{{ $news->display_title }}">{{ $news->display_title }}</p>
                            <p class="d"><i class="far fa-clock"></i> {{ $news->published_at?->translatedFormat('d M Y, h:i A') }}</p>
                        </div>
                        <span class="rn-chip">{{ __('site.admin.active') }}</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="dash-card">
        <div class="rm-head" style="background:linear-gradient(90deg, rgba(249,250,251,.8), #fff);">
            <h3>
                <span class="admin-ico"><i class="fas fa-user-plus"></i></span>
                {{ __('site.admin.recent_players') }}
            </h3>
            <a href="{{ route('admin.players.index') }}">{{ __('site.view_all') }} <i class="fas fa-arrow-right" style="font-size:.6rem;"></i></a>
        </div>
        <div class="rm-list">
            @if ($recentPlayers->isEmpty())
                <div class="rn-empty"><i class="fas fa-users" style="font-size:1.4rem;display:block;margin-bottom:.5rem;color:#d1d5db;"></i>{{ __('site.admin.no_players') }}</div>
            @else
                @foreach ($recentPlayers as $player)
                    <div class="rp-row">
                        <div class="rp-left">
                            <span class="rp-init">{{ Str::upper(Str::substr($player->name, 0, 1)) }}</span>
                            <div style="min-width:0;">
                                <p class="t" title="{{ $player->display_name }}">{{ $player->display_name }}</p>
                                <p class="d">{{ $player->display_role }}</p>
                            </div>
                        </div>
                        <span class="rp-chip"><i class="fas fa-check-circle" style="font-size:.55rem;margin-right:.2rem;"></i>{{ __('site.admin.active') }}</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection

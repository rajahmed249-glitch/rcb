@extends('admin.layout')

@section('title', __('site.admin.dashboard'))

@section('content')
<div class="admin-stats">
    <a href="{{ route('admin.players.index') }}" class="admin-stat green">
        <span class="admin-stat-ico">👥</span>
        <div>
            <span class="admin-stat-num">{{ $playersCount }}</span>
            <span class="admin-stat-label">{{ __('site.admin.total_players') }}</span>
        </div>
    </a>
    <a href="{{ route('pages.teams') }}" target="_blank" class="admin-stat blue">
        <span class="admin-stat-ico">🛡️</span>
        <div>
            <span class="admin-stat-num">{{ $teamsCount }}</span>
            <span class="admin-stat-label">{{ __('site.admin.total_teams') }}</span>
        </div>
    </a>
    <a href="{{ route('admin.matches.index') }}" class="admin-stat purple">
        <span class="admin-stat-ico">🗓️</span>
        <div>
            <span class="admin-stat-num">{{ $matchesCount }}</span>
            <span class="admin-stat-label">{{ __('site.admin.total_matches') }}</span>
        </div>
    </a>
    <a href="{{ route('admin.news.index') }}" class="admin-stat orange">
        <span class="admin-stat-ico">📰</span>
        <div>
            <span class="admin-stat-num">{{ $newsCount }}</span>
            <span class="admin-stat-label">{{ __('site.admin.total_news') }}</span>
        </div>
    </a>
</div>

<div class="admin-panel">
    <div class="admin-panel-head">
        <h2>📈 {{ __('site.admin.match_statistics') }}</h2>
    </div>
    <div class="admin-matchstats">
        <div class="admin-matchstat green">
            <span class="admin-matchstat-num">{{ $stats['completed'] }}</span>
            <span>{{ __('site.admin.completed') }}</span>
        </div>
        <div class="admin-matchstat yellow">
            <span class="admin-matchstat-num">{{ $stats['upcoming'] }}</span>
            <span>{{ __('site.admin.upcoming') }}</span>
        </div>
        <div class="admin-matchstat red">
            <span class="admin-matchstat-num">{{ $stats['live'] }}</span>
            <span>{{ __('site.admin.live') }}</span>
        </div>
        <div class="admin-matchstat grey">
            <span class="admin-matchstat-num">{{ $stats['abandoned'] }}</span>
            <span>{{ __('site.admin.abandoned') }}</span>
        </div>
        <div class="admin-matchstat blue">
            <span class="admin-matchstat-num">{{ $stats['total'] }}</span>
            <span>{{ __('site.admin.total') }}</span>
        </div>
    </div>
</div>

<div class="admin-panel">
    <div class="admin-panel-head">
        <h2>⚡ {{ __('site.admin.quick_actions') }}</h2>
    </div>
    <div class="admin-quick">
        <a href="{{ route('admin.players.create') }}" class="admin-quick-btn green">＋ {{ __('site.admin.add_player') }}</a>
        <a href="{{ route('admin.matches.create') }}" class="admin-quick-btn blue">＋ {{ __('site.admin.add_match') }}</a>
        <a href="{{ route('admin.news.create') }}" class="admin-quick-btn orange">＋ {{ __('site.admin.add_news') }}</a>
        <a href="{{ route('admin.sponsors.create') }}" class="admin-quick-btn purple">＋ {{ __('site.admin.add_sponsor') }}</a>
    </div>
</div>

<div class="admin-panel">
    <div class="admin-panel-head">
        <h2>🗓️ {{ __('site.admin.recent_matches') }}</h2>
        <a href="{{ route('admin.matches.index') }}" class="admin-btn small ghost">{{ __('site.view_all') }} →</a>
    </div>
    @if ($recentMatches->isEmpty())
        <p class="admin-empty">{{ __('site.no_matches') }}</p>
    @else
        <div class="admin-matchlist">
            @foreach ($recentMatches as $match)
                <a href="{{ route('admin.matches.edit', $match) }}" class="admin-matchrow">
                    <div class="admin-matchrow-main">
                        <strong>{{ __('site.match') }} {{ $match->match_number }}</strong>
                        <span>RCB {{ __('site.vs') }} {{ $match->display_opponent_name }}</span>
                        <small>📅 {{ $match->match_date?->translatedFormat('d M Y, h:i A') }}@if($match->venue) · 📍 {{ $match->venue }}@endif</small>
                    </div>
                    <div class="admin-matchrow-side">
                        <span class="admin-matchrow-score">{{ $match->rcb_score ?: '-' }} ({{ $match->rcb_overs ?: '0.0' }}) / {{ $match->opponent_score ?: '-' }} ({{ $match->opponent_overs ?: '0.0' }})</span>
                        <span class="admin-badge {{ $match->status }}">{{ __('site.' . $match->status) }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>

<div class="admin-panel">
    <div class="admin-panel-head">
        <h2>📝 {{ __('site.admin.recent_applications') }}</h2>
        <a href="{{ route('admin.applications.index') }}" class="admin-btn small ghost">{{ __('site.admin.applications') }} →</a>
    </div>
    @if ($recentApplications->isEmpty())
        <p class="admin-empty">{{ __('site.admin.no_applications') }}</p>
    @else
        <div class="admin-table-wrap flat">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>{{ __('site.admin.name') }}</th>
                        <th>{{ __('site.admin.phone') }}</th>
                        <th>{{ __('site.admin.preferred_role') }}</th>
                        <th>{{ __('site.admin.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentApplications as $application)
                        <tr>
                            <td>{{ $application->name }}</td>
                            <td>{{ $application->phone }}</td>
                            <td>{{ $application->preferred_role }}</td>
                            <td>{{ $application->created_at?->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

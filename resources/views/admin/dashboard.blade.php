@extends('admin.layout')

@section('title', __('site.admin.dashboard'))

@section('content')
<div class="admin-hero">
    <div>
        <h2>{{ __('site.admin.welcome') }} 👋</h2>
        <p>{{ __('site.admin.welcome_text') }}</p>
    </div>
</div>

<div class="admin-stats">
    <a href="{{ route('admin.players.index') }}" class="admin-stat">
        <span class="admin-stat-ico">🏏</span>
        <span class="admin-stat-num">{{ $playersCount }}</span>
        <span class="admin-stat-label">{{ __('site.admin.total_players') }}</span>
    </a>
    <a href="{{ route('admin.matches.index') }}" class="admin-stat">
        <span class="admin-stat-ico">🗓️</span>
        <span class="admin-stat-num">{{ $matchesCount }}</span>
        <span class="admin-stat-label">{{ __('site.admin.total_matches') }}</span>
    </a>
    <a href="{{ route('admin.news.index') }}" class="admin-stat">
        <span class="admin-stat-ico">📰</span>
        <span class="admin-stat-num">{{ $newsCount }}</span>
        <span class="admin-stat-label">{{ __('site.admin.total_news') }}</span>
    </a>
    <a href="{{ route('admin.sponsors.index') }}" class="admin-stat">
        <span class="admin-stat-ico">🤝</span>
        <span class="admin-stat-num">{{ $sponsorsCount }}</span>
        <span class="admin-stat-label">{{ __('site.admin.total_sponsors') }}</span>
    </a>
    <a href="{{ route('admin.applications.index') }}" class="admin-stat">
        <span class="admin-stat-ico">📝</span>
        <span class="admin-stat-num">{{ $applicationsCount }}</span>
        <span class="admin-stat-label">{{ __('site.admin.pending_applications') }}</span>
    </a>
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

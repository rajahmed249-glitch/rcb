@extends('layouts.app')

@section('title', 'Search')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>{{ __('site.search_page.title') }}</h1>
            @if ($query !== '')
                <p>{{ __('site.results_for', ['q' => $query]) }}</p>
            @else
                <p>{{ __('site.search_page.subtitle') }}</p>
            @endif
        </div>

        @if ($query === '')
            <div class="card"><p class="muted">{{ __('site.search_page.empty') }}</p></div>
        @endif

        @if ($players->isNotEmpty())
            <section class="section">
                <div class="section-head"><h2>{{ __('site.players') }}</h2></div>
                <div class="player-grid">
                    @foreach ($players as $player)
                        <div class="card player-card">
                            <div class="player-avatar">{{ $player->initials }}</div>
                            <h3><a href="{{ route('players.show', $player) }}">{{ $player->display_name }}</a></h3>
                            <a href="{{ route('players.show', $player) }}" class="btn btn-outline" style="margin-top:.5rem;">{{ __('site.view_profile') }}</a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($matches->isNotEmpty())
            <section class="section">
                <div class="section-head"><h2>{{ __('site.matches') }}</h2></div>
                <div class="match-grid">
                    @foreach ($matches as $match)
                        <div class="match-card" data-href="{{ route('matches.show', $match) }}">
                            <span class="badge badge-matchno">Match {{ $match->match_number }}</span>
                            <span class="badge badge-{{ $match->status }}">{{ $match->status }}</span>
                            <div class="match-date-pill">{{ $match->match_date->format('d M Y') }}</div>
                            <div class="match-teams">
                                <div class="match-team">
                                    <img class="team-logo" src="{{ asset('images/teams/real-challenger-boys.webp') }}" alt="Real Challenger Boys">
                                    <div class="match-score">-</div>
                                </div>
                                <span class="vs-badge">vs</span>
                                <div class="match-team right">
                                    <div class="match-score">-</div>
                                    @if ($match->opponent_logo)
                                        <img class="team-logo opponent" src="{{ asset($match->opponent_logo) }}" alt="{{ $match->display_opponent_name }}">
                                    @else
                                        <span class="team-logo opponent">{{ mb_strtoupper(mb_substr($match->opponent_name, 0, 2)) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="match-names">
                                <a href="{{ route('pages.team-profile', 'real-challenger-boys') }}">REAL CHALLENGER BOYS</a>
                                <a href="{{ route('pages.team-profile', \Illuminate\Support\Str::slug($match->opponent_name)) }}">{{ $match->display_opponent_name }}</a>
                            </div>
                            <div class="match-footer">📍 {{ $match->venue }}</div>
                            </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($news->isNotEmpty())
            <section class="section">
                <div class="section-head"><h2>{{ __('site.nav.news') }}</h2></div>
                <div class="news-grid">
                    @foreach ($news as $post)
                        <article class="card news-card">
                            <p class="news-date">📅 {{ $post->published_at?->format('d M Y') }}</p>
                            <h3>{{ $post->display_title }}</h3>
                            <a href="{{ route('news.show', $post) }}" class="read-more">{{ __('site.read_more') }} &rarr;</a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($query !== '' && $players->isEmpty() && $matches->isEmpty() && $news->isEmpty())
            <div class="card">
                <p class="muted">{{ __('site.no_results_for', ['q' => $query]) }}</p>
                <p class="muted">{{ __('site.search_page.no_results') }}</p>
            </div>
        @endif
    </div>
@endsection

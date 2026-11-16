@extends('layouts.app')

@section('title', 'Search')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>Search</h1>
            @if ($query !== '')
                <p>Results for “{{ $query }}”</p>
            @else
                <p>Search for players, teams, or matches.</p>
            @endif
        </div>

        @if ($query === '')
            <div class="card"><p class="muted">Type something in the search box above to get started.</p></div>
        @endif

        @if ($players->isNotEmpty())
            <section class="section">
                <div class="section-head"><h2>Players</h2></div>
                <div class="player-grid">
                    @foreach ($players as $player)
                        <div class="card player-card">
                            <div class="player-avatar">{{ $player->initials }}</div>
                            <h3>{{ $player->name }}</h3>
                            <a href="{{ route('players.show', $player) }}" class="btn btn-outline" style="margin-top:.5rem;">View Profile</a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($matches->isNotEmpty())
            <section class="section">
                <div class="section-head"><h2>Matches</h2></div>
                <div class="match-grid">
                    @foreach ($matches as $match)
                        <a href="{{ route('matches.show', $match) }}" class="match-card">
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
                                        <img class="team-logo opponent" src="{{ asset($match->opponent_logo) }}" alt="{{ $match->opponent_name }}">
                                    @else
                                        <span class="team-logo opponent">{{ mb_strtoupper(mb_substr($match->opponent_name, 0, 2)) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="match-names">
                                <span>REAL CHALLENGER BOYS</span>
                                <span>{{ $match->opponent_name }}</span>
                            </div>
                            <div class="match-footer">📍 {{ $match->venue }}</div>
                            </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($news->isNotEmpty())
            <section class="section">
                <div class="section-head"><h2>News</h2></div>
                <div class="news-grid">
                    @foreach ($news as $post)
                        <article class="card news-card">
                            <p class="news-date">📅 {{ $post->published_at?->format('d M Y') }}</p>
                            <h3>{{ $post->title }}</h3>
                            <a href="{{ route('news.show', $post) }}" class="read-more">Read More →</a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($query !== '' && $players->isEmpty() && $matches->isEmpty() && $news->isEmpty())
            <div class="card">
                <p class="muted">No results found “{{ $query }}”</p>
                <p class="muted">Try searching for players, teams, or matches</p>
            </div>
        @endif
    </div>
@endsection

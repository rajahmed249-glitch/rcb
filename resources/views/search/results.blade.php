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
                        <div class="card match-card">
                            <div class="match-meta">
                                <span>Match {{ $match->match_number }}</span>
                                <span class="badge badge-{{ $match->status }}">{{ $match->status }}</span>
                                <span>{{ $match->match_date->format('d M Y') }}</span>
                            </div>
                            <p><strong>RCB vs {{ $match->opponent_name }}</strong></p>
                            <a href="{{ route('matches.show', $match) }}" class="read-more">Match Details →</a>
                        </div>
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

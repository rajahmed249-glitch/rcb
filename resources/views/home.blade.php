@extends('layouts.app')

@section('title', 'Welcome to RCB')

@section('content')
    <section class="hero">
        <div class="container">
            <h1>🏏 REAL CHALLENGER BOYS</h1>
            <p>Play with passion, win with honor. Follow every match, every player and every moment of the RCB journey.</p>
            <a href="{{ route('matches.index') }}" class="btn btn-primary">VIEW MATCHES</a>
            <a href="{{ route('be-a-player.create') }}" class="btn btn-outline">BE A PLAYER</a>
        </div>
    </section>

    <div class="container">
        <section class="section">
            <div class="section-head">
                <h2>Latest Result</h2>
                <a href="{{ route('matches.index', ['status' => 'completed']) }}" class="btn btn-outline">View All</a>
            </div>

            @if ($latestResult)
                <div class="card match-card">
                    <div class="match-meta">
                        <span>Match {{ $latestResult->match_number }}</span>
                        <span class="badge badge-completed">completed</span>
                        <span>{{ $latestResult->match_date->format('d M Y') }}</span>
                    </div>
                    <div class="scoreboard">
                        <div class="team">
                            REAL CHALLENGER BOYS
                            <div class="score">{{ $latestResult->rcb_score ?? '-' }} <small>({{ $latestResult->rcb_overs ?? '-' }})</small></div>
                        </div>
                        <div class="vs">vs</div>
                        <div class="team">
                            {{ $latestResult->opponent_name }}
                            <div class="score">{{ $latestResult->opponent_score ?? '-' }} <small>({{ $latestResult->opponent_overs ?? '-' }})</small></div>
                        </div>
                    </div>
                    <p class="result-line {{ $latestResult->won ? '' : 'lost' }}">{{ $latestResult->result_label }}</p>
                    <a href="{{ route('matches.show', $latestResult) }}" class="read-more">Match Details →</a>
                </div>
            @else
                <div class="card"><p class="muted">No completed matches yet.</p></div>
            @endif
        </section>

        <section class="section">
            <div class="section-head">
                <h2>Upcoming Fixtures</h2>
                <a href="{{ route('matches.index', ['status' => 'upcoming']) }}" class="btn btn-outline">View All</a>
            </div>

            <div class="match-grid">
                @forelse ($upcomingMatches as $match)
                    <div class="card match-card">
                        <div class="match-meta">
                            <span>Match {{ $match->match_number }}</span>
                            <span class="badge badge-upcoming">upcoming</span>
                            <span>{{ $match->match_date->format('d M Y') }}</span>
                        </div>
                        <div class="scoreboard">
                            <div class="team">REAL CHALLENGER BOYS<div class="score">-</div></div>
                            <div class="vs">vs</div>
                            <div class="team">{{ $match->opponent_name }}<div class="score">-</div></div>
                        </div>
                        @if ($match->venue)
                            <p class="venue-line">📍 {{ $match->venue }}</p>
                        @endif
                        <a href="{{ route('matches.show', $match) }}" class="read-more">Match Details →</a>
                    </div>
                @empty
                    <div class="card"><p class="muted">No upcoming fixtures scheduled.</p></div>
                @endforelse
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <h2>Latest News</h2>
                <a href="{{ route('news.index') }}" class="btn btn-outline">View All</a>
            </div>

            <div class="news-grid">
                @forelse ($latestNews as $post)
                    <article class="card news-card">
                        <p class="news-date">📅 {{ $post->published_at?->format('d M Y') }}</p>
                        <h3>{{ $post->title }}</h3>
                        <p>{{ $post->excerpt }}</p>
                        <a href="{{ route('news.show', $post) }}" class="read-more">Read More →</a>
                    </article>
                @empty
                    <div class="card"><p class="muted">No news published yet.</p></div>
                @endforelse
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <h2>Players</h2>
                <a href="{{ route('players.index') }}" class="btn btn-outline">View All</a>
            </div>

            <div class="player-grid">
                @forelse ($players as $player)
                    <div class="card player-card">
                        <div class="player-avatar">
                            {{ $player->initials }}
                            @if ($player->badge)
                                <span class="badge-role">{{ $player->badge }}</span>
                            @endif
                        </div>
                        <h3>{{ $player->name }}@if ($player->badge) ({{ $player->badge }})@endif</h3>
                        <p class="muted" style="font-size:.85rem;">{{ $player->role }}</p>
                        <div class="player-stats">
                            <span>M: <strong>{{ $player->matches_played }}</strong></span>
                            <span>R: <strong>{{ $player->runs }}</strong></span>
                            <span>W: <strong>{{ $player->wickets }}</strong></span>
                        </div>
                        <a href="{{ route('players.show', $player) }}" class="btn btn-outline">View Profile</a>
                    </div>
                @empty
                    <div class="card"><p class="muted">No players added yet.</p></div>
                @endforelse
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <h2>Our Sponsors</h2>
                <a href="{{ route('sponsors.index') }}" class="btn btn-outline">View All</a>
            </div>

            <div class="sponsor-grid">
                @forelse ($sponsors as $sponsor)
                    <div class="card sponsor-card">{{ $sponsor->name }}</div>
                @empty
                    <div class="card"><p class="muted">No sponsors listed yet.</p></div>
                @endforelse
            </div>
        </section>
    </div>
@endsection

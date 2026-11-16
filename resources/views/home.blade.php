@extends('layouts.app')

@section('title', 'Welcome to RCB')

@section('content')
    <section class="hero-slider" id="heroSlider">
        <div class="hero-slide active"><img src="{{ asset('images/banner1.png') }}" alt="Real Challenger Boys team"></div>
        <div class="hero-slide"><img src="{{ asset('images/banner2.webp') }}" alt="Real Challenger Boys team photo" loading="lazy"></div>
        <div class="hero-slide"><img src="{{ asset('images/banner3.webp') }}" alt="Real Challenger Boys celebration" loading="lazy"></div>
        <div class="hero-dots">
            <button class="hero-dot active" data-slide="0" aria-label="Slide 1"></button>
            <button class="hero-dot" data-slide="1" aria-label="Slide 2"></button>
            <button class="hero-dot" data-slide="2" aria-label="Slide 3"></button>
        </div>
    </section>

    <div class="container">
        <section class="section">
            <div class="section-head">
                <h2>Latest Result</h2>
                <a href="{{ route('matches.index', ['status' => 'completed']) }}" class="btn-viewall">View All
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if ($latestResult)
                <div class="match-grid">
                    <a href="{{ route('matches.show', $latestResult) }}" class="match-card">
                        <span class="badge badge-matchno">Match {{ $latestResult->match_number }}</span>
                        <span class="badge badge-completed">completed</span>
                        <div class="match-date-pill">{{ $latestResult->match_date->format('d M Y') }}</div>
                        <div class="match-teams">
                            <div class="match-team">
                                <img class="team-logo" src="{{ asset('images/logo.png') }}" alt="Real Challenger Boys">
                                <div class="match-score">{{ $latestResult->rcb_score ?? '-' }} <small>({{ $latestResult->rcb_overs ?? '-' }})</small></div>
                            </div>
                            <span class="vs-badge">vs</span>
                            <div class="match-team right">
                                <div class="match-score">{{ $latestResult->opponent_score ?? '-' }} <small>({{ $latestResult->opponent_overs ?? '-' }})</small></div>
                                <span class="team-logo opponent">{{ mb_strtoupper(mb_substr($latestResult->opponent_name, 0, 2)) }}</span>
                            </div>
                        </div>
                        <div class="match-names">
                            <span>REAL CHALLENGER BOYS</span>
                            <span>{{ $latestResult->opponent_name }}</span>
                        </div>
                        <div class="match-footer {{ $latestResult->won ? '' : 'lost' }}">{{ $latestResult->result_label }}</div>
                        <span class="match-details-link">Match Details →</span>
                    </a>
                </div>
            @else
                <div class="card"><p class="muted">No completed matches yet.</p></div>
            @endif
        </section>

        <section class="section">
            <div class="section-head">
                <h2>Upcoming Fixtures</h2>
                <a href="{{ route('matches.index', ['status' => 'upcoming']) }}" class="btn-viewall">View All
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="match-grid">
                @forelse ($upcomingMatches as $match)
                    <a href="{{ route('matches.show', $match) }}" class="match-card">
                        <span class="badge badge-matchno">Match {{ $match->match_number }}</span>
                        <span class="badge badge-upcoming">upcoming</span>
                        <div class="match-date-pill">{{ $match->match_date->format('d M Y') }}</div>
                        <div class="match-teams">
                            <div class="match-team">
                                <img class="team-logo" src="{{ asset('images/logo.png') }}" alt="Real Challenger Boys">
                                <div class="match-score">-</div>
                            </div>
                            <span class="vs-badge">vs</span>
                            <div class="match-team right">
                                <div class="match-score">-</div>
                                <span class="team-logo opponent">{{ mb_strtoupper(mb_substr($match->opponent_name, 0, 2)) }}</span>
                            </div>
                        </div>
                        <div class="match-names">
                            <span>REAL CHALLENGER BOYS</span>
                            <span>{{ $match->opponent_name }}</span>
                        </div>
                        <div class="match-footer">📍 {{ $match->venue }}</div>
                        <span class="match-details-link">Match Details →</span>
                    </a>
                @empty
                    <div class="card"><p class="muted">No upcoming fixtures scheduled.</p></div>
                @endforelse
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <h2>Latest News</h2>
                <a href="{{ route('news.index') }}" class="btn-viewall">View All
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="news-grid">
                @forelse ($latestNews as $post)
                    <a href="{{ route('news.show', $post) }}" class="news-card">
                        <div class="news-thumb">
                            @if ($post->image)
                                <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" loading="lazy">
                            @else
                                <div class="news-thumb-placeholder">📰</div>
                            @endif
                            <span class="news-date-pill">📅 {{ $post->published_at?->format('d M Y') }}</span>
                        </div>
                        <div class="news-body">
                            <h3>{{ $post->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($post->excerpt, 120) }}</p>
                            <div class="news-foot">
                                <span>Read More</span>
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="card"><p class="muted">No news published yet.</p></div>
                @endforelse
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <h2>Players</h2>
                <a href="{{ route('players.index') }}" class="btn-viewall">View All
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="player-carousel">
                <div class="player-track" id="playerTrack">
                    @forelse ($players as $player)
                        <div class="player-slide">
                            <div class="player-photo-card">
                                <div class="player-photo">
                                    @if ($player->photo_url)
                                        <img src="{{ $player->photo_url }}" alt="{{ $player->name }}" loading="lazy">
                                    @else
                                        <div class="player-photo-fallback">{{ $player->initials }}</div>
                                    @endif
                                    @if ($player->badge)
                                        <span class="player-tag">{{ $player->badge === 'c' ? 'Captain' : 'Vice Captain' }}</span>
                                    @endif
                                    <div class="player-hover">
                                        <h3>Player Stats</h3>
                                        <ul>
                                            <li>Matches: {{ $player->matches_played }}</li>
                                            <li>Runs: {{ $player->runs }}</li>
                                            <li>Wickets: {{ $player->wickets }}</li>
                                        </ul>
                                        <a href="{{ route('players.show', $player) }}" class="btn-viewprofile">View Profile</a>
                                    </div>
                                </div>
                                <div class="player-bar">
                                    <span class="player-role-icon">{{ match(true) { $player->role === 'Bowler' => '🎯', $player->role === 'Wicketkeeper' => '🧤', $player->role === 'All-rounder' => '⚡', default => '🏏' } }}</span>
                                    <span class="player-bar-divider"></span>
                                    <span class="player-bar-name">{{ $player->name }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card"><p class="muted">No players added yet.</p></div>
                    @endforelse
                </div>
                <button class="carousel-btn carousel-prev" id="playerPrev" aria-label="Previous">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button class="carousel-btn carousel-next" id="playerNext" aria-label="Next">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <h2>Our Sponsors</h2>
                <a href="{{ route('sponsors.index') }}" class="btn-viewall">View All
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if ($sponsors->isNotEmpty())
                <div class="sponsors-scroll">
                    <div class="sponsors-track">
                        @foreach ([0, 1] as $loopCopy)
                            @foreach ($sponsors as $sponsor)
                                <div class="sponsor-item">
                                    <div class="sponsor-box">{{ $sponsor->name }}</div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @else
                <div class="card"><p class="muted">No sponsors listed yet.</p></div>
            @endif
        </section>
    </div>
@endsection

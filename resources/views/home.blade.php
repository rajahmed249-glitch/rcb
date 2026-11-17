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
                <h2>{{ __('site.latest_result') }}</h2>
                <a href="{{ route('matches.index', ['status' => 'completed']) }}" class="btn-viewall">{{ __('site.view_all') }}
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if ($latestResult)
                <div class="match-grid">
                    <div class="match-card" data-href="{{ route('matches.show', $latestResult) }}">
                        <span class="badge badge-matchno">Match {{ $latestResult->match_number }}</span>
                        <span class="badge badge-completed">{{ __('site.completed') }}</span>
                        <div class="match-date-pill">{{ $latestResult->match_date->translatedFormat('d M Y') }}</div>
                        <div class="match-teams">
                            <div class="match-team">
                                <img class="team-logo" src="{{ asset('images/teams/real-challenger-boys.webp') }}" alt="Real Challenger Boys">
                                <div class="match-score">{{ $latestResult->rcb_score ?? '-' }} <small>({{ $latestResult->rcb_overs ?? '-' }})</small></div>
                            </div>
                            <span class="vs-badge">{{ __('site.vs') }}</span>
                            <div class="match-team right">
                                <div class="match-score">{{ $latestResult->opponent_score ?? '-' }} <small>({{ $latestResult->opponent_overs ?? '-' }})</small></div>
                                @if ($latestResult->opponent_logo)
                                    <img class="team-logo opponent" src="{{ asset($latestResult->opponent_logo) }}" alt="{{ $latestResult->display_opponent_name }}">
                                @else
                                    <span class="team-logo opponent">{{ mb_strtoupper(mb_substr($latestResult->opponent_name, 0, 2)) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="match-names">
                            <a href="{{ route('pages.team-profile', 'real-challenger-boys') }}">REAL CHALLENGER BOYS</a>
                            <a href="{{ route('pages.team-profile', \Illuminate\Support\Str::slug($latestResult->opponent_name)) }}">{{ $latestResult->display_opponent_name }}</a>
                        </div>
                        <div class="match-footer {{ $latestResult->won ? '' : 'lost' }}">{{ $latestResult->result_label }}</div>
                    </div>
                </div>
            @else
                <div class="card"><p class="muted">{{ __('site.no_completed_matches') }}</p></div>
            @endif
        </section>

        <section class="section">
            <div class="section-head">
                <h2>{{ __('site.upcoming_fixtures') }}</h2>
                <a href="{{ route('matches.index', ['status' => 'upcoming']) }}" class="btn-viewall">{{ __('site.view_all') }}
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="match-grid">
                @forelse ($upcomingMatches as $match)
                    <div class="match-card" data-href="{{ route('matches.show', $match) }}">
                        <span class="badge badge-matchno">Match {{ $match->match_number }}</span>
                        <span class="badge badge-upcoming">{{ __('site.upcoming') }}</span>
                        <div class="match-date-pill">{{ $match->match_date->translatedFormat('d M Y') }}</div>
                        <div class="match-teams">
                            <div class="match-team">
                                <img class="team-logo" src="{{ asset('images/teams/real-challenger-boys.webp') }}" alt="Real Challenger Boys">
                                <div class="match-score">-</div>
                            </div>
                            <span class="vs-badge">{{ __('site.vs') }}</span>
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
                @empty
                    <div class="card"><p class="muted">{{ __('site.no_upcoming_fixtures') }}</p></div>
                @endforelse
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <h2>{{ __('site.latest_news') }}</h2>
                <a href="{{ route('news.index') }}" class="btn-viewall">{{ __('site.view_all') }}
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="news-grid">
                @forelse ($latestNews as $post)
                    <a href="{{ route('news.show', $post) }}" class="news-card">
                        <div class="news-thumb">
                            @if ($post->image)
                                <img src="{{ asset($post->image) }}" alt="{{ $post->display_title }}" loading="lazy">
                            @else
                                <div class="news-thumb-placeholder">📰</div>
                            @endif
                            <span class="news-date-pill">📅 {{ $post->published_at?->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="news-body">
                            <h3>{{ $post->display_title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($post->display_excerpt, 120) }}</p>
                            <div class="news-foot">
                                <span>{{ __('site.read_more') }}</span>
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="card"><p class="muted">{{ __('site.no_news') }}</p></div>
                @endforelse
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <h2>{{ __('site.players') }}</h2>
                <a href="{{ route('players.index') }}" class="btn-viewall">{{ __('site.view_all') }}
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
                                        <img src="{{ $player->photo_url }}" alt="{{ $player->display_name }}" loading="lazy">
                                    @else
                                        <div class="player-photo-fallback">{{ $player->initials }}</div>
                                    @endif
                                    @if ($player->badge)
                                        <span class="player-tag">{{ $player->badge === 'c' ? __('site.captain') : __('site.vice_captain') }}</span>
                                    @endif
                                    <div class="player-hover">
                                        <h3>{{ __('site.player_stats') }}</h3>
                                        <ul>
                                            <li>{{ __('site.matches') }}: {{ $player->matches_played }}</li>
                                            <li>{{ __('site.runs') }}: {{ $player->runs }}</li>
                                            <li>{{ __('site.wickets') }}: {{ $player->wickets }}</li>
                                        </ul>
                                        <a href="{{ route('players.show', $player) }}" class="btn-viewprofile">{{ __('site.view_profile') }}</a>
                                    </div>
                                </div>
                                <div class="player-bar">
                                    <span class="player-role-icon">{{ match(true) { $player->role === 'Bowler' => '🎯', $player->role === 'Wicketkeeper' => '🧤', $player->role === 'All-rounder' => '⚡', default => '🏏' } }}</span>
                                    <span class="player-bar-divider"></span>
                                    <a href="{{ route('players.show', $player) }}" class="player-bar-name">{{ $player->display_name }}</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card"><p class="muted">{{ __('site.no_players') }}</p></div>
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
                <h2>{{ __('site.our_sponsors') }}</h2>
                <a href="{{ route('sponsors.index') }}" class="btn-viewall">{{ __('site.view_all') }}
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if ($sponsors->isNotEmpty())
                <div class="sponsors-scroll">
                    <div class="sponsors-track">
                        @foreach ([0, 1] as $loopCopy)
                            @foreach ($sponsors as $sponsor)
                                <div class="sponsor-item">
                                    <div class="sponsor-box">
                                        @if ($sponsor->logo)
                                            <img src="{{ asset($sponsor->logo) }}" alt="{{ $sponsor->display_name }}" title="{{ $sponsor->display_name }}" loading="lazy">
                                        @else
                                            {{ $sponsor->display_name }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @else
                <div class="card"><p class="muted">{{ __('site.no_sponsors') }}</p></div>
            @endif
        </section>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Match ' . $match->match_number . ' vs ' . $match->opponent_name)

@php
    function sbSplitScore(?string $score): array
    {
        if (!$score || $score === '-') {
            return [null, null];
        }

        $parts = explode('-', $score, 2);

        return [$parts[0], $parts[1] ?? null];
    }

    [$rcbRuns, $rcbWickets] = sbSplitScore($match->rcb_score);
    [$oppRuns, $oppWickets] = sbSplitScore($match->opponent_score);
@endphp

@section('content')
    <div class="container">
        <div class="sb-card">
            <div class="sb-topline"><span class="sb-matchname">Match {{ $match->match_number }}</span></div>
            <div class="sb-topline"><span class="sb-date">{{ $match->match_date->format('M d, Y') }} &bull; {{ $match->match_date->format('h:i A') }}</span></div>
            <div class="sb-topline"><span class="sb-venue">{{ $match->venue ?: 'Venue Not Set Yet' }}</span></div>
            <div class="sb-topline"><span class="sb-toss">{{ $match->format }} &bull; {{ ucfirst($match->status) }}</span></div>

            <div class="sb-names">
                <div class="sb-name sb-name-right">REAL CHALLENGER BOYS</div>
                <div class="sb-vs"><span>VS</span></div>
                <div class="sb-name sb-name-left">{{ $match->opponent_name }}</div>
            </div>

            <div class="sb-columns">
                <div class="sb-team">
                    <div class="sb-logo-row">
                        <img src="{{ asset('images/teams/real-challenger-boys.webp') }}" alt="Real Challenger Boys" class="sb-logo">
                    </div>
                    <div class="sb-teamname">REAL CHALLENGER BOYS</div>
                    <div class="sb-score">
                        @if ($rcbRuns !== null)
                            {{ $rcbRuns }}@if ($rcbWickets !== null)<span class="sb-score-w">-{{ $rcbWickets }}</span>@endif
                        @else
                            -
                        @endif
                    </div>
                    <div class="sb-overs">{{ $match->rcb_overs ? $match->rcb_overs . ' overs' : '&nbsp;' }}</div>
                </div>

                <div class="sb-result">
                    <div class="sb-result-label">Result</div>
                    @if ($match->status === 'completed' && $match->result_text)
                        <div class="sb-result-text {{ $match->won ? '' : 'sb-result-lost' }}">{{ $match->result_text }}</div>
                    @else
                        <div class="sb-result-text sb-result-pending">-</div>
                    @endif
                    <div class="sb-divider"></div>
                    <div class="sb-extra">
                        <span class="sb-extra-icon">&#128203;</span>
                        <span class="sb-extra-text">{{ $match->format }} Match</span>
                    </div>
                </div>

                <div class="sb-team">
                    <div class="sb-logo-row">
                        @if ($match->opponent_logo)
                            <img src="{{ asset($match->opponent_logo) }}" alt="{{ $match->opponent_name }}" class="sb-logo">
                        @else
                            <div class="sb-logo-fallback">{{ strtoupper(substr($match->opponent_short_name ?: $match->opponent_name, 0, 2)) }}</div>
                        @endif
                    </div>
                    <div class="sb-teamname">{{ strtoupper($match->opponent_name) }}</div>
                    <div class="sb-score">
                        @if ($oppRuns !== null)
                            {{ $oppRuns }}@if ($oppWickets !== null)<span class="sb-score-w">-{{ $oppWickets }}</span>@endif
                        @else
                            -
                        @endif
                    </div>
                    <div class="sb-overs">{{ $match->opponent_overs ? $match->opponent_overs . ' overs' : '&nbsp;' }}</div>
                </div>
            </div>
        </div>

        <div class="sb-tabs-card">
            <div class="sb-tabs">
                <button class="sb-tab-btn active" data-tab="summary">&#128202; Summary</button>
                <button class="sb-tab-btn" data-tab="scorecard">&#128203; Scorecard</button>
                <button class="sb-tab-btn" data-tab="squad">&#128101; Squad</button>
                <button class="sb-tab-btn" data-tab="matchinfo">&#8505;&#65039; Match Info</button>
                <button class="sb-tab-btn" data-tab="commentary">&#127951; Commentary</button>
            </div>
        </div>

        <div class="sb-content-card">
            <div class="sb-tab-content active" id="sb-tab-summary">
                <div class="sb-coming">
                    <div class="sb-coming-icon">&#127951;</div>
                    <h3>Full Summary Coming Soon</h3>
                    <p>Ball-by-ball summary for this match will be published here soon.</p>
                </div>
            </div>

            <div class="sb-tab-content" id="sb-tab-scorecard">
                <div class="sb-coming">
                    <div class="sb-coming-icon">&#128203;</div>
                    <h3>Scorecard Coming Soon</h3>
                    <p>Per-player batting and bowling figures will be published here soon.</p>
                </div>
            </div>

            <div class="sb-tab-content" id="sb-tab-squad">
                <div class="sb-coming">
                    <div class="sb-coming-icon">&#128101;</div>
                    <h3>Squad Coming Soon</h3>
                    <p>The playing XI for this match will be announced here soon.</p>
                </div>
            </div>

            <div class="sb-tab-content" id="sb-tab-matchinfo">
                <div class="sb-info">
                    <div class="sb-info-row">
                        <div class="sb-info-label">Match</div>
                        <div class="sb-info-value">Match {{ $match->match_number }} &bull; {{ $match->format }}</div>
                    </div>
                    <div class="sb-info-row">
                        <div class="sb-info-label">Date</div>
                        <div class="sb-info-value">{{ $match->match_date->format('l, M d, Y') }} &bull; {{ $match->match_date->format('h:i A') }}</div>
                    </div>
                    <div class="sb-info-row">
                        <div class="sb-info-label">Venue</div>
                        <div class="sb-info-value">{{ $match->venue ?: 'Venue Not Set Yet' }}</div>
                    </div>
                    <div class="sb-info-row">
                        <div class="sb-info-label">Status</div>
                        <div class="sb-info-value">{{ ucfirst($match->status) }}</div>
                    </div>
                    @if ($match->result_text)
                        <div class="sb-info-row">
                            <div class="sb-info-label">Result</div>
                            <div class="sb-info-value">{{ $match->result_text }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="sb-tab-content" id="sb-tab-commentary">
                <div class="sb-coming">
                    <div class="sb-coming-icon">&#128483;&#65039;</div>
                    <h3>Commentary Coming Soon</h3>
                    <p>Over-by-over commentary for this match will be published here soon.</p>
                </div>
            </div>
        </div>

        <p style="margin-top: 2rem; text-align: center;">
            <a href="{{ route('matches.index') }}" class="btn btn-outline">&larr; Back to Matches</a>
        </p>
    </div>
@endsection

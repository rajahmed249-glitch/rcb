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

    $hasScorecard = $match->status === 'completed' && !empty($scorecard['rcb']);

    $summaryCards = [];
    if ($hasScorecard) {
        $summaryTeams = [
            ['key' => 'rcb', 'name' => 'Real Challenger Boys'],
            ['key' => 'opponent', 'name' => $match->opponent_name],
        ];

        foreach ($summaryTeams as $t) {
            $inn = $scorecard[$t['key']];

            $topBat = collect($inn['batting'])
                ->sortByDesc('runs')
                ->take(3)
                ->values()
                ->map(fn ($b) => $b + ['notOut' => str_starts_with(strtolower($b['dismissal']), 'not out')]);

            $topBowl = collect($inn['bowling'])
                ->sort(fn ($a, $b) => [$b['wickets'], $a['runs']] <=> [$a['wickets'], $b['runs']])
                ->take(3)
                ->values();

            $summaryCards[] = [
                'name' => $t['name'],
                'score' => $inn['total'],
                'wickets' => $inn['wickets'],
                'overs' => $inn['overs'],
                'batting' => $topBat,
                'bowling' => $topBowl,
            ];
        }
    }
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
                @if ($hasScorecard)
                    @foreach ($summaryCards as $card)
                        <div class="sum-card">
                            <div class="sum-head">
                                <span class="sum-team">{{ $card['name'] }}</span>
                                <span class="sum-score">{{ $card['score'] }}-{{ $card['wickets'] }} <small>({{ $card['overs'] }} overs)</small></span>
                            </div>
                            <div class="sum-body">
                                <div>
                                    @foreach ($card['batting'] as $b)
                                        <div class="sum-row">
                                            @if ($b['id'] ?? null)
                                                <a href="{{ route('players.show', $b['id']) }}" class="sum-name">{{ $b['name'] }}</a>
                                            @else
                                                <span class="sum-name">{{ $b['name'] }}</span>
                                            @endif
                                            <span class="sum-fig">{{ $b['runs'] }}{{ $b['notOut'] ? '*' : '' }} <small>({{ $b['balls'] }})</small></span>
                                        </div>
                                    @endforeach
                                </div>
                                <div>
                                    @foreach ($card['bowling'] as $bo)
                                        <div class="sum-row">
                                            <span class="sum-name">{{ $bo['name'] }}</span>
                                            <span class="sum-fig">{{ $bo['wickets'] }}-{{ $bo['runs'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="sb-summary">
                        <div class="sb-sum-block">
                            <div class="sb-sum-head">
                                <span>Real Challenger Boys</span>
                                <span class="sb-sum-score">{{ $match->rcb_score ?? '-' }}{{ $match->rcb_overs ? ' (' . $match->rcb_overs . ')' : '' }}</span>
                            </div>
                            <div class="sb-sum-row"><span>Overs</span><span>{{ $match->rcb_overs ?? '-' }}</span></div>
                            <div class="sb-sum-row"><span>Format</span><span>{{ $match->format }}</span></div>
                        </div>
                        <div class="sb-sum-block">
                            <div class="sb-sum-head">
                                <span>{{ $match->opponent_name }}</span>
                                <span class="sb-sum-score">{{ $match->opponent_score ?? '-' }}{{ $match->opponent_overs ? ' (' . $match->opponent_overs . ')' : '' }}</span>
                            </div>
                            <div class="sb-sum-row"><span>Overs</span><span>{{ $match->opponent_overs ?? '-' }}</span></div>
                            <div class="sb-sum-row"><span>Result</span><span>{{ $match->result_text ?? ucfirst($match->status) }}</span></div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="sb-tab-content" id="sb-tab-scorecard">
                @if ($hasScorecard)
                    <div class="score-subtabs">
                        <div class="subtab-pair">
                            <button class="score-subtab-btn active" data-innings="rcb">Real Challenger Boys</button>
                            <button class="score-subtab-btn" data-innings="opp">{{ $match->opponent_name }}</button>
                        </div>
                    </div>

                    <div class="scorecard-innings active" id="innings-rcb">
                        @include('matches.innings', [
                            'innings' => $scorecard['rcb'],
                            'teamName' => 'Real Challenger Boys',
                        ])
                    </div>

                    <div class="scorecard-innings" id="innings-opp">
                        @include('matches.innings', [
                            'innings' => $scorecard['opponent'],
                            'teamName' => $match->opponent_name,
                        ])
                    </div>
                @else
                    <div class="sb-coming">
                        <div class="sb-coming-icon">&#128203;</div>
                        <h3>Scorecard Coming Soon</h3>
                        <p>Per-player batting and bowling figures will be published here soon.</p>
                    </div>
                @endif
            </div>

            <div class="sb-tab-content" id="sb-tab-squad">
                @if ($hasScorecard)
                    <div class="squad-subtabs">
                        <div class="subtab-pair">
                            <button class="squad-subtab-btn active" data-squad="rcb">Real Challenger Boys</button>
                            <button class="squad-subtab-btn" data-squad="opp">{{ $match->opponent_name }}</button>
                        </div>
                    </div>

                    <div class="squad-content active" id="squad-rcb">
                        <div class="table-scroll">
                            <table class="squad-table">
                                <thead>
                                    <tr>
                                        <th>Player</th>
                                        <th>Matches</th>
                                        <th>Innings</th>
                                        <th>Runs</th>
                                        <th>Average</th>
                                        <th>Highest Score</th>
                                        <th>SR</th>
                                        <th>50s</th>
                                        <th>100s</th>
                                        <th>Wickets</th>
                                        <th>Economy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($scorecard['squads']['rcb'] as $p)
                                        <tr>
                                            <td>
                                                <div class="squad-player-cell">
                                                    @if ($p['photo'])
                                                        <img src="{{ $p['photo'] }}" alt="{{ $p['name'] }}" class="squad-photo">
                                                    @else
                                                        <span class="squad-photo squad-photo-fallback">{{ mb_strtoupper(mb_substr($p['name'], 0, 1)) }}</span>
                                                    @endif
                                                    @if ($p['id'])
                                                        <a href="{{ route('players.show', $p['id']) }}" class="player-name-link">{{ $p['name'] }}</a>
                                                    @else
                                                        <span class="player-name-link">{{ $p['name'] }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $p['matches'] }}</td>
                                            <td>{{ $p['innings'] }}</td>
                                            <td class="t-bold">{{ $p['runs'] }}</td>
                                            <td>{{ $p['avg'] }}</td>
                                            <td>{{ $p['hs'] }}</td>
                                            <td>{{ $p['bat_sr'] }}</td>
                                            <td>{{ $p['fifties'] }}</td>
                                            <td>{{ $p['hundreds'] }}</td>
                                            <td class="t-teal">{{ $p['wickets'] }}</td>
                                            <td>{{ $p['economy'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="squad-content" id="squad-opp">
                        <div class="table-scroll">
                            <table class="squad-table">
                                <thead>
                                    <tr>
                                        <th>Player</th>
                                        <th>Matches</th>
                                        <th>Innings</th>
                                        <th>Runs</th>
                                        <th>Average</th>
                                        <th>Highest Score</th>
                                        <th>SR</th>
                                        <th>50s</th>
                                        <th>100s</th>
                                        <th>Wickets</th>
                                        <th>Economy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($scorecard['squads']['opponent'] as $p)
                                        <tr>
                                            <td>
                                                <div class="squad-player-cell">
                                                    <span class="squad-photo squad-photo-fallback">{{ mb_strtoupper(mb_substr($p['name'], 0, 1)) }}</span>
                                                    <span class="player-name-link">{{ $p['name'] }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $p['matches'] }}</td>
                                            <td>{{ $p['innings'] }}</td>
                                            <td class="t-bold">{{ $p['runs'] }}</td>
                                            <td>{{ $p['avg'] }}</td>
                                            <td>{{ $p['hs'] }}</td>
                                            <td>{{ $p['bat_sr'] }}</td>
                                            <td>{{ $p['fifties'] }}</td>
                                            <td>{{ $p['hundreds'] }}</td>
                                            <td class="t-teal">{{ $p['wickets'] }}</td>
                                            <td>{{ $p['economy'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="sb-coming">
                        <div class="sb-coming-icon">&#128101;</div>
                        <h3>Squad Coming Soon</h3>
                        <p>The playing XI for this match will be announced here soon.</p>
                    </div>
                @endif
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

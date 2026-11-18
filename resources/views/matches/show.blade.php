@extends('layouts.app')

@section('title', __('site.match') . ' ' . $match->match_number . ' vs ' . $match->display_opponent_name)

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

    $flags = $scorecard['flags'];

    $matchFull = $match->match_name ?: ('Match ' . $match->match_number);

    $rcbInnings = !empty($scorecard['rcb']) ? $scorecard['rcb'] : null;
    $oppInnings = !empty($scorecard['opponent']) ? $scorecard['opponent'] : null;
    $hasSummaryCards = $rcbInnings !== null && $oppInnings !== null;

    $tabDefs = [
        ['summary', '&#128202;', __('site.tabs.summary'), $flags['result']],
        ['scorecard', '&#128203;', __('site.tabs.scorecard'), $rcbInnings !== null || $oppInnings !== null],
        ['squad', '&#128101;', __('site.tabs.squad'), $flags['squad'] || !empty($scorecard['squads']['rcb']) || !empty($scorecard['squads']['opponent'])],
        ['matchinfo', '&#8505;&#65039;', __('site.tabs.match_info'), $flags['info']],
        ['commentary', '&#127951;', __('site.tabs.overs'), $flags['commentary']],
    ];

    $tabs = array_values(array_filter($tabDefs, fn ($t) => $t[3]));

    $activeTab = $tabs[0][0] ?? null;

    $rcbTeamSlug = 'real-challenger-boys';
    $oppTeamSlug = \Illuminate\Support\Str::slug($match->opponent_name);

    $summaryCards = [];
    if ($hasSummaryCards) {
        $summaryTeams = [
            ['key' => 'rcb', 'name' => 'Real Challenger Boys'],
            ['key' => 'opponent', 'name' => $match->display_opponent_name],
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
                'slug' => $t['key'] === 'rcb' ? $rcbTeamSlug : $oppTeamSlug,
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
            <div class="sb-topline"><span class="sb-matchname">{{ $matchFull }}</span></div>
            <div class="sb-topline"><span class="sb-date">{{ $match->match_date->translatedFormat('M d, Y') }} &bull; {{ $match->match_date->translatedFormat('h:i A') }}</span></div>
            <div class="sb-topline"><span class="sb-venue">{{ $match->venue ?: __('site.match_info.venue_not_set') }}</span></div>
            @if ($match->status === 'completed' && $match->toss_winner_id)
                <div class="sb-topline"><span class="sb-toss">{{ __('site.match_info.toss_line', ['team' => $match->tossWinner?->display_name ?? '-', 'opted' => $match->toss_elected === 'bowl' ? __('site.match_info.bowl') : __('site.match_info.bat')]) }}</span></div>
            @endif

            <div class="sb-names">
                <a href="{{ route('pages.team-profile', $rcbTeamSlug) }}" class="sb-name sb-name-right">REAL CHALLENGER BOYS</a>
                <div class="sb-vs"><span>VS</span></div>
                <a href="{{ route('pages.team-profile', $oppTeamSlug) }}" class="sb-name sb-name-left">{{ $match->opponent_name }}</a>
            </div>

            <div class="sb-columns">
                <div class="sb-team">
                    <div class="sb-logo-row">
                        <img src="{{ asset('images/teams/real-challenger-boys.webp') }}" alt="Real Challenger Boys" class="sb-logo">
                    </div>
                    <a class="sb-teamname" href="{{ route('pages.team-profile', $rcbTeamSlug) }}">REAL CHALLENGER BOYS</a>
                    <div class="sb-score">
                        @if ($rcbRuns !== null)
                            {{ $rcbRuns }}@if ($rcbWickets !== null)<span class="sb-score-w">-{{ $rcbWickets }}</span>@endif
                        @else
                            -
                        @endif
                    </div>
                    <div class="sb-overs">{{ $match->rcb_overs ? $match->rcb_overs . ' ' . __('site.scorecard.overs_short') : '&nbsp;' }}</div>
                </div>

                <div class="sb-result">
                    <div class="sb-result-label">{{ __('site.match_info.result') }}</div>
                    @if ($match->status === 'completed' && $match->result_text)
                        <div class="sb-result-text {{ $match->won ? '' : 'sb-result-lost' }}">{{ $match->result_text }}</div>
                    @else
                        <div class="sb-result-text sb-result-pending">-</div>
                    @endif
                    <div class="sb-divider"></div>
                    @if ($match->status === 'completed' && $match->playerOfMatch)
                        <div class="sb-pom">
                            <span class="sb-pom-label">&#127942; {{ __('site.match_info.player_of_match') }}</span>
                            <a href="{{ route('players.show', $match->playerOfMatch->id) }}" class="sb-pom-link">{{ $match->playerOfMatch->display_name }}</a>
                        </div>
                    @endif
                </div>

                <div class="sb-team">
                    <div class="sb-logo-row">
                        @if ($match->opponent_logo)
                            <img src="{{ asset($match->opponent_logo) }}" alt="{{ $match->display_opponent_name }}" class="sb-logo">
                        @else
                            <div class="sb-logo-fallback">{{ strtoupper(substr($match->opponent_short_name ?: $match->opponent_name, 0, 2)) }}</div>
                        @endif
                    </div>
                    <a class="sb-teamname" href="{{ route('pages.team-profile', $oppTeamSlug) }}">{{ strtoupper($match->display_opponent_name) }}</a>
                    <div class="sb-score">
                        @if ($oppRuns !== null)
                            {{ $oppRuns }}@if ($oppWickets !== null)<span class="sb-score-w">-{{ $oppWickets }}</span>@endif
                        @else
                            -
                        @endif
                    </div>
                    <div class="sb-overs">{{ $match->opponent_overs ? $match->opponent_overs . ' ' . __('site.scorecard.overs_short') : '&nbsp;' }}</div>
                </div>
            </div>
        </div>

        <div class="sb-tabs-card">
            <div class="sb-tabs">
                @foreach ($tabs as $tab)
                    <button class="sb-tab-btn{{ $tab[0] === $activeTab ? ' active' : '' }}" data-tab="{{ $tab[0] }}">{!! $tab[1] !!} {{ $tab[2] }}</button>
                @endforeach
            </div>
        </div>

        <div class="sb-content-card">
            @if ($flags['result'])
            <div class="sb-tab-content{{ $activeTab === 'summary' ? ' active' : '' }}" id="sb-tab-summary">
                @if ($hasSummaryCards)
                    @foreach ($summaryCards as $card)
                        <div class="sum-card">
                            <div class="sum-head">
                                <a class="sum-team" href="{{ route('pages.team-profile', $card['slug']) }}">{{ $card['name'] }}</a>
                                <span class="sum-score">{{ $card['score'] }}-{{ $card['wickets'] }} <small>({{ $card['overs'] }} {{ __('site.scorecard.overs_short') }})</small></span>
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
                                            @if ($bo['id'])
                                                <a href="{{ route('players.show', $bo['id']) }}" class="sum-name">{{ $bo['name'] }}</a>
                                            @else
                                                <span class="sum-name">{{ $bo['name'] }}</span>
                                            @endif
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
                            <div class="sb-sum-row"><span>{{ __('site.scorecard.overs') }}</span><span>{{ $match->rcb_overs ?? '-' }}</span></div>
                            <div class="sb-sum-row"><span>{{ __('site.match_info.match') }}</span><span>{{ $match->format }}</span></div>
                            @if ($match->tournament)
                                <div class="sb-sum-row"><span>{{ __('site.match_info.tournament') }}</span><span>{{ $match->tournament->display_name }}</span></div>
                            @endif
                        </div>
                        <div class="sb-sum-block">
                            <div class="sb-sum-head">
                                <span>{{ $match->display_opponent_name }}</span>
                                <span class="sb-sum-score">{{ $match->opponent_score ?? '-' }}{{ $match->opponent_overs ? ' (' . $match->opponent_overs . ')' : '' }}</span>
                            </div>
                            <div class="sb-sum-row"><span>{{ __('site.scorecard.overs') }}</span><span>{{ $match->opponent_overs ?? '-' }}</span></div>
                            <div class="sb-sum-row"><span>{{ __('site.match_info.result') }}</span><span>{{ $match->result_text ?? ucfirst($match->status) }}</span></div>
                        </div>
                    </div>
                @endif
            </div>
            @endif

            @if ($tabDefs[1][3])
            <div class="sb-tab-content{{ $activeTab === 'scorecard' ? ' active' : '' }}" id="sb-tab-scorecard">
                @if ($rcbInnings || $oppInnings)
                    @if ($rcbInnings && $oppInnings)
                    <div class="score-subtabs">
                        <div class="subtab-pair">
                            <button class="score-subtab-btn active" data-innings="rcb">Real Challenger Boys</button>
                            <button class="score-subtab-btn" data-innings="opp">{{ $match->display_opponent_name }}</button>
                        </div>
                    </div>
                    @endif

                    @if ($rcbInnings)
                    <div class="scorecard-innings{{ $oppInnings ? ' active' : '' }}" id="innings-rcb">
                        @include('matches.innings', [
                            'innings' => $rcbInnings,
                            'teamName' => 'Real Challenger Boys',
                            'teamSlug' => $rcbTeamSlug,
                        ])
                    </div>
                    @endif

                    @if ($oppInnings)
                    <div class="scorecard-innings{{ $rcbInnings ? '' : ' active' }}" id="innings-opp">
                        @include('matches.innings', [
                            'innings' => $oppInnings,
                            'teamName' => $match->display_opponent_name,
                            'teamSlug' => $oppTeamSlug,
                        ])
                    </div>
                    @endif
                @endif
            </div>
            @endif

            @if ($tabDefs[2][3])
            <div class="sb-tab-content{{ $activeTab === 'squad' ? ' active' : '' }}" id="sb-tab-squad">
                @php
                    $rcbSquad = $scorecard['squads']['rcb'] ?? [];
                    $oppSquad = $scorecard['squads']['opponent'] ?? [];
                @endphp
                @if ($rcbSquad || $oppSquad)
                    @if ($rcbSquad && $oppSquad)
                    <div class="squad-subtabs">
                        <div class="subtab-pair">
                            <button class="squad-subtab-btn active" data-squad="rcb">Real Challenger Boys</button>
                            <button class="squad-subtab-btn" data-squad="opp">{{ $match->display_opponent_name }}</button>
                        </div>
                    </div>
                    @endif

                    @if ($rcbSquad)
                    <div class="squad-content{{ $oppSquad ? ' active' : '' }}" id="squad-rcb">
                        <div class="table-scroll">
                            <table class="squad-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('site.squad.player') }}</th>
                                        <th>{{ __('site.matches') }}</th>
                                        <th>{{ __('site.squad.innings') }}</th>
                                        <th>{{ __('site.runs') }}</th>
                                        <th>{{ __('site.squad.average') }}</th>
                                        <th>{{ __('site.squad.highest_score') }}</th>
                                        <th>{{ __('site.squad.sr') }}</th>
                                        <th>{{ __('site.squad.fifties') }}</th>
                                        <th>{{ __('site.squad.hundreds') }}</th>
                                        <th>{{ __('site.scorecard.wickets') }}</th>
                                        <th>{{ __('site.squad.economy') }}
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
                    @endif

                    @if ($oppSquad)
                    <div class="squad-content{{ $rcbSquad ? '' : ' active' }}" id="squad-opp">
                        <div class="table-scroll">
                            <table class="squad-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('site.squad.player') }}</th>
                                        <th>{{ __('site.matches') }}</th>
                                        <th>{{ __('site.squad.innings') }}</th>
                                        <th>{{ __('site.runs') }}</th>
                                        <th>{{ __('site.squad.average') }}</th>
                                        <th>{{ __('site.squad.highest_score') }}</th>
                                        <th>{{ __('site.squad.sr') }}</th>
                                        <th>{{ __('site.squad.fifties') }}</th>
                                        <th>{{ __('site.squad.hundreds') }}</th>
                                        <th>{{ __('site.scorecard.wickets') }}</th>
                                        <th>{{ __('site.squad.economy') }}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($oppSquad as $p)
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
                    @endif
                @endif
            </div>
            @endif

            @if ($tabDefs[3][3])
            <div class="sb-tab-content{{ $activeTab === 'matchinfo' ? ' active' : '' }}" id="sb-tab-matchinfo">
                <div class="sb-match-info-text">{!! $match->match_info !!}</div>
            </div>
            @endif

            @if ($tabDefs[4][3])
            <div class="sb-tab-content{{ $activeTab === 'commentary' ? ' active' : '' }}" id="sb-tab-commentary">
                @php
                    $cmRcb = array_values(array_filter($scorecard['commentary'], fn ($c) => $c['side'] === 'rcb'));
                    $cmOpp = array_values(array_filter($scorecard['commentary'], fn ($c) => $c['side'] === 'opponent'));
                @endphp
                @if ($cmRcb && $cmOpp)
                    <div class="score-subtabs">
                        <div class="subtab-pair">
                            <button class="overs-subtab-btn active" data-over="rcb">Real Challenger Boys</button>
                            <button class="overs-subtab-btn" data-over="opp">{{ $match->display_opponent_name }}</button>
                        </div>
                    </div>
                @endif

                @if ($cmRcb)
                    <div class="overs-content{{ $cmOpp ? ' active' : '' }}" id="overs-rcb">
                        <div class="table-scroll">
                            <table class="bowl-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('site.admin.end_of_over') }}</th>
                                        <th>{{ __('site.admin.score_at') }}</th>
                                        <th>{{ __('site.admin.ball_per_run') }}</th>
                                        <th>{{ __('site.admin.description') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cmRcb as $cm)
                                        <tr>
                                            <td>{{ $cm['over'] }}</td>
                                            <td>{{ $cm['score'] }}</td>
                                            <td>{{ $cm['runs'] }}</td>
                                            <td>{{ $cm['description'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($cmOpp)
                    <div class="overs-content{{ $cmRcb ? '' : ' active' }}" id="overs-opp">
                        <div class="table-scroll">
                            <table class="bowl-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('site.admin.end_of_over') }}</th>
                                        <th>{{ __('site.admin.score_at') }}</th>
                                        <th>{{ __('site.admin.ball_per_run') }}</th>
                                        <th>{{ __('site.admin.description') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cmOpp as $cm)
                                        <tr>
                                            <td>{{ $cm['over'] }}</td>
                                            <td>{{ $cm['score'] }}</td>
                                            <td>{{ $cm['runs'] }}</td>
                                            <td>{{ $cm['description'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            @endif
        </div>

        <p style="margin-top: 2rem; text-align: center;">
            <a href="{{ route('matches.index') }}" class="btn btn-outline">{{ __('site.back_to_matches') }}</a>
        </p>
    </div>
@endsection

@php
    $exB = $innings['extras'] > 4 ? 2 : 0;
    $exLb = $innings['extras'] > 6 ? 2 : 0;
    $exW = max(0, $innings['extras'] - $exB - $exLb);
    $exNb = 0;
@endphp

<div class="bat-card">
    <div class="bat-head">
        <a class="bat-team" href="{{ $teamSlug ? route('pages.team-profile', $teamSlug) : '#' }}">{{ $teamName }}</a>
        <span class="bat-score">{{ $innings['total'] }}-{{ $innings['wickets'] }} <span>({{ $innings['overs'] }} overs)</span></span>
    </div>

    <div class="bat-scroll">
        <div class="bat-cols">
            <div>{{ __('site.scorecard.batter') }}</div><div>{{ __('site.scorecard.r') }}</div><div>{{ __('site.scorecard.b') }}</div><div>{{ __('site.scorecard.fours') }}</div><div>{{ __('site.scorecard.sixes') }}</div><div>{{ __('site.scorecard.sr') }}</div>
        </div>

        @foreach ($innings['batting'] as $bat)
            <div class="bat-row">
                <div class="bat-player">
                    @if ($bat['photo'])
                        <img src="{{ $bat['photo'] }}" alt="{{ $bat['name'] }}" class="bat-photo">
                    @else
                        <span class="bat-photo bat-photo-fallback">{{ mb_strtoupper(mb_substr($bat['name'], 0, 1)) }}</span>
                    @endif
                    <div class="player-name-cell">
                        @if ($bat['id'])
                            <a href="{{ route('players.show', $bat['id']) }}" class="player-name-link">{{ $bat['name'] }}</a>
                        @else
                            <span class="player-name-link">{{ $bat['name'] }}</span>
                        @endif
                        <div class="dismissal-text">{{ $bat['dismissal'] }}</div>
                    </div>
                </div>
                <div class="bat-runs">{{ $bat['runs'] }}</div>
                <div>{{ $bat['balls'] }}</div>
                <div>{{ $bat['fours'] }}</div>
                <div>{{ $bat['sixes'] }}</div>
                <div>{{ $bat['sr'] }}</div>
            </div>
        @endforeach

        <div class="bat-totalrow">
            <span class="t-bold">{{ __('site.scorecard.extras') }}</span>
            <span class="t-muted"><strong>{{ $innings['extras'] }}</strong> (b {{ $exB }}, lb {{ $exLb }}, w {{ $exW }}, nb {{ $exNb }})</span>
        </div>

        <div class="bat-totalrow">
            <span class="t-bold">{{ __('site.scorecard.total') }}</span>
            <span class="t-muted"><strong>{{ $innings['total'] }}</strong> ({{ $innings['wickets'] }} {{ __('site.scorecard.wickets') }}, {{ $innings['overs'] }} {{ __('site.scorecard.overs_short') }})</span>
        </div>
    </div>

    @if (count($innings['fow']))
        <div class="fow">
            <div class="fow-cols"><div>{{ __('site.scorecard.fall_of_wickets') }}</div><div>{{ __('site.scorecard.score') }}</div><div>{{ __('site.scorecard.overs') }}</div></div>
            @foreach ($innings['fow'] as $f)
                <div class="fow-row">
                    <div>
                        @if ($f['id'])
                            <a href="{{ route('players.show', $f['id']) }}" class="player-name-link">{{ $f['name'] }}</a>
                        @else
                            <span class="player-name-link">{{ $f['name'] }}</span>
                        @endif
                    </div>
                    <div>{{ $f['score'] }}</div>
                    <div>{{ $f['over'] }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="bowl-block">
        <div class="bowl-scroll">
            <table class="bowl-table">
                <thead>
                    <tr>
                        <th>{{ __('site.scorecard.bowler') }}</th>
                        <th>{{ __('site.scorecard.overs') }}</th>
                        <th>{{ __('site.scorecard.maidens') }}</th>
                        <th>{{ __('site.runs') }}</th>
                        <th>{{ __('site.scorecard.wickets') }}</th>
                        <th>{{ __('site.scorecard.wides') }}</th>
                        <th>{{ __('site.scorecard.no_balls') }}</th>
                        <th>{{ __('site.scorecard.economy') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($innings['bowling'] as $bowl)
                        <tr>
                            <td>
                                <div class="bowler-name-cell">
                                    @if ($bowl['id'])
                                        <a href="{{ route('players.show', $bowl['id']) }}" class="player-name-link">{{ $bowl['name'] }}</a>
                                    @else
                                        <span class="player-name-link">{{ $bowl['name'] }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $bowl['overs'] }}</td>
                            <td>{{ $bowl['maidens'] }}</td>
                            <td>{{ $bowl['runs'] }}</td>
                            <td class="t-teal">{{ $bowl['wickets'] }}</td>
                            <td>{{ $bowl['wides'] }}</td>
                            <td>{{ $bowl['noballs'] }}</td>
                            <td>{{ $bowl['econ'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

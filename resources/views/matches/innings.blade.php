@php
    $exB = $innings['extras'] > 4 ? 2 : 0;
    $exLb = $innings['extras'] > 6 ? 2 : 0;
    $exW = max(0, $innings['extras'] - $exB - $exLb);
    $exNb = 0;
@endphp

<div class="bat-card">
    <div class="bat-head">
        <span class="bat-team">{{ $teamName }}</span>
        <span class="bat-score">{{ $innings['total'] }}-{{ $innings['wickets'] }} <span>({{ $innings['overs'] }} overs)</span></span>
    </div>

    <div class="bat-scroll">
        <div class="bat-cols">
            <div>Batter</div><div>R</div><div>B</div><div>4s</div><div>6s</div><div>SR</div>
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
                        <span class="player-name-link">{{ $bat['name'] }}</span>
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
            <span class="t-bold">Extras</span>
            <span class="t-muted"><strong>{{ $innings['extras'] }}</strong> (b {{ $exB }}, lb {{ $exLb }}, w {{ $exW }}, nb {{ $exNb }})</span>
        </div>

        <div class="bat-totalrow">
            <span class="t-bold">Total</span>
            <span class="t-muted"><strong>{{ $innings['total'] }}</strong> ({{ $innings['wickets'] }} wickets, {{ $innings['overs'] }} overs)</span>
        </div>
    </div>

    @if (count($innings['fow']))
        <div class="fow">
            <div class="fow-cols"><div>Fall of Wickets</div><div>Score</div><div>Overs</div></div>
            @foreach ($innings['fow'] as $f)
                <div class="fow-row">
                    <div><span class="player-name-link">{{ $f['name'] }}</span></div>
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
                        <th>Bowler</th>
                        <th>Overs</th>
                        <th>Maidens</th>
                        <th>Runs</th>
                        <th>Wickets</th>
                        <th>Wides</th>
                        <th>No Balls</th>
                        <th>Economy</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($innings['bowling'] as $bowl)
                        <tr>
                            <td>
                                <div class="bowler-name-cell">
                                    <span class="player-name-link">{{ $bowl['name'] }}</span>
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

<div class="bat-card">
    <div class="bat-head">
        <span>{{ $teamName }}</span>
        <span>{{ $scoreStr }} ({{ $innings['overs'] }} Ov)</span>
    </div>

    <div class="bat-scroll">
        <div class="bat-cols"><div>Batter</div><div>R</div><div>B</div><div>4s</div><div>6s</div><div>SR</div></div>

        @foreach ($innings['batting'] as $bat)
            <div class="bat-row">
                <div class="bat-player">
                    @if ($bat['photo'])
                        <img src="{{ $bat['photo'] }}" alt="{{ $bat['name'] }}" class="bat-photo">
                    @else
                        <span class="bat-photo bat-photo-fallback">{{ mb_strtoupper(mb_substr($bat['name'], 0, 1)) }}</span>
                    @endif
                    <div>
                        <span class="bat-name">{{ $bat['name'] }}</span>
                        <div class="bat-dismissal">{{ $bat['dismissal'] }}</div>
                    </div>
                </div>
                <div class="bat-runs">{{ $bat['runs'] }}</div>
                <div>{{ $bat['balls'] }}</div>
                <div>{{ $bat['fours'] }}</div>
                <div>{{ $bat['sixes'] }}</div>
                <div>{{ $bat['sr'] }}</div>
            </div>
        @endforeach

        <div class="bat-totalrow"><span>Extras</span><span>{{ $innings['extras'] }} (b 1, lb 2, w {{ max(0, $innings['extras'] - 3) }}, nb 0)</span></div>
        <div class="bat-totalrow"><span>Total</span><span><strong>{{ $innings['total'] }}-{{ $innings['wickets'] }}</strong> ({{ $innings['overs'] }} Ov)</span></div>

        @if (count($innings['fow']))
            <div class="fow">
                <div class="fow-title">Fall of Wickets</div>
                <div class="fow-cols"><div>Batter</div><div>Score</div><div>Overs</div></div>
                @foreach ($innings['fow'] as $f)
                    <div class="fow-row"><div>{{ $f['name'] }}</div><div>{{ $f['score'] }}</div><div>{{ $f['over'] }}</div></div>
                @endforeach
            </div>
        @endif

        <div class="bowl-block">
            <div class="bowl-cols"><div>Bowler</div><div>O</div><div>M</div><div>R</div><div>W</div><div>Wd</div><div>Nb</div><div>Econ</div></div>
            @foreach ($innings['bowling'] as $bowl)
                <div class="bowl-row">
                    <div class="bowl-name">{{ $bowl['name'] }}</div>
                    <div>{{ $bowl['overs'] }}</div>
                    <div>{{ $bowl['maidens'] }}</div>
                    <div>{{ $bowl['runs'] }}</div>
                    <div class="bowl-wickets">{{ $bowl['wickets'] }}</div>
                    <div>{{ $bowl['wides'] }}</div>
                    <div>{{ $bowl['noballs'] }}</div>
                    <div>{{ $bowl['econ'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

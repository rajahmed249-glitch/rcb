@extends('admin.layout')

@section('title', __('site.admin.add_squad'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-users-viewfinder apl-title-ico"></i> {{ __('site.admin.add_squad') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.squads.index') }}">{{ __('site.admin.squad') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.add_squad') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.squads.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_squads') }}
        </a>
        <a href="{{ route('admin.squads.create') }}" class="apl-tab active">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_squad') }}
        </a>
    </div>
    <a href="{{ route('admin.squads.create') }}" class="apl-addbtn">
        <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_squad') }}
    </a>
</div>

@php
    $sqdMatchesData = $tournaments->mapWithKeys(function ($tournament) {
        return [$tournament->id => $tournament->matches->map(function ($m) {
            $label = ($m->match_number ? str_pad($m->match_number, 4, '0', STR_PAD_LEFT) : '—')
                . ' — ' . ($m->match_name ?: ('RCB vs ' . $m->display_opponent_name))
                . ' (' . $m->match_date->format('d M Y') . ')';
            return [
                'id' => $m->id,
                'label' => $label,
                'opponent_id' => $m->opponent_team_id,
                'opponent_name' => $m->display_opponent_name,
            ];
        })->values()->all()];
    })->all();
    $sqdTexts = [
        'selectMatch' => __('site.admin.select_match'),
        'selectTeam' => __('site.admin.select_team'),
        'selectTeamFirst' => __('site.admin.select_team_first'),
        'selectTeamToLoad' => __('site.admin.select_team_to_load'),
        'noPlayers' => __('site.admin.no_players_for_team'),
        'playersFound' => __('site.admin.players_found'),
    ];
@endphp

<form method="POST" action="{{ route('admin.squads.store') }}" class="apf-card" id="sqdCreateForm">
    @csrf
    <div class="apf-card-title">{{ __('site.admin.squad_information') }}</div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.tournament') }} <span class="apf-required">*</span></label>
            <select id="sqdTournament" required>
                <option value="">{{ __('site.admin.select_tournament') }}</option>
                @foreach ($tournaments as $tournament)
                    <option value="{{ $tournament->id }}">{{ $tournament->display_name }}@if ($tournament->season) — {{ $tournament->season }}@endif</option>
                @endforeach
            </select>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.match') }} <span class="apf-required">*</span></label>
            <select id="sqdMatch" disabled>
                <option value="">{{ __('site.admin.select_match') }}</option>
            </select>
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.team') }} <span class="apf-required">*</span></label>
            <select id="sqdTeam" disabled>
                <option value="">{{ __('site.admin.select_team') }}</option>
            </select>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.players') }} <span class="apf-required">*</span></label>
            <select name="player_ids[]" id="sqdPlayers" multiple disabled size="8">
                <option value="">{{ __('site.admin.select_team_first') }}</option>
            </select>
            <p class="apf-hint" id="sqdPlayerCount">{{ __('site.admin.select_team_to_load') }}</p>
        </div>
    </div>

    <input type="hidden" name="match_id" id="sqdMatchId" value="">

    <div class="apf-actions">
        <a href="{{ route('admin.squads.index') }}" class="apf-cancel">
            <i class="fas fa-arrow-left"></i> {{ __('site.admin.cancel') }}
        </a>
        <button type="submit" class="apf-submit">
            <i class="fas fa-save"></i> {{ __('site.admin.save_squad') }}
        </button>
    </div>
</form>

<script type="application/json" id="sqdData">{
    "matches": @js($sqdMatchesData),
    "playersByTeam": @js($playersByTeam),
    "rcbTeam": @js($rcbTeam ? ['id' => (string) $rcbTeam->id, 'name' => $rcbTeam->name] : null),
    "texts": @js($sqdTexts)
}</script>
<script>
(function () {
    var data = JSON.parse(document.getElementById('sqdData').textContent);
    var sqdMatches = data.matches;
    var sqdPlayersByTeam = data.playersByTeam;
    var sqdRcbTeam = data.rcbTeam;
    var texts = data.texts;

    var tournamentSelect = document.getElementById('sqdTournament');
    var matchSelect = document.getElementById('sqdMatch');
    var teamSelect = document.getElementById('sqdTeam');
    var playerSelect = document.getElementById('sqdPlayers');
    var playerCount = document.getElementById('sqdPlayerCount');
    var matchIdInput = document.getElementById('sqdMatchId');

    function resetPlayers(message, cls) {
        playerSelect.innerHTML = '';
        var opt = document.createElement('option');
        opt.value = '';
        opt.textContent = message;
        playerSelect.appendChild(opt);
        playerSelect.disabled = true;
        playerCount.textContent = message;
        playerCount.className = cls || 'apf-hint';
    }

    function resetTeam() {
        teamSelect.innerHTML = '';
        var teamPlaceholder = document.createElement('option');
        teamPlaceholder.value = '';
        teamPlaceholder.textContent = texts.selectTeam;
        teamSelect.appendChild(teamPlaceholder);
        teamSelect.disabled = true;
    }

    tournamentSelect.addEventListener('change', function () {
        matchSelect.innerHTML = '';
        var placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = texts.selectMatch;
        matchSelect.appendChild(placeholder);
        matchSelect.disabled = true;
        matchIdInput.value = '';

        resetTeam();
        resetPlayers(texts.selectTeamFirst);

        var matches = sqdMatches[this.value] || [];
        if (!matches.length) return;

        matches.forEach(function (m) {
            var opt = document.createElement('option');
            opt.value = m.id;
            opt.textContent = m.label;
            opt.setAttribute('data-opponent-id', m.opponent_id || '');
            opt.setAttribute('data-opponent-name', m.opponent_name || '');
            matchSelect.appendChild(opt);
        });
        matchSelect.disabled = false;
    });

    matchSelect.addEventListener('change', function () {
        resetTeam();
        resetPlayers(texts.selectTeamFirst);
        matchIdInput.value = '';

        var opt = this.options[this.selectedIndex];
        if (!opt || !opt.value) return;

        matchIdInput.value = opt.value;

        if (sqdRcbTeam) {
            var rcbOpt = document.createElement('option');
            rcbOpt.value = sqdRcbTeam.id;
            rcbOpt.textContent = sqdRcbTeam.name;
            teamSelect.appendChild(rcbOpt);
        }
        var opponentId = opt.getAttribute('data-opponent-id');
        if (opponentId) {
            var oppOpt = document.createElement('option');
            oppOpt.value = opponentId;
            oppOpt.textContent = opt.getAttribute('data-opponent-name');
            teamSelect.appendChild(oppOpt);
        }
        teamSelect.disabled = teamSelect.options.length <= 1;
    });

    teamSelect.addEventListener('change', function () {
        var teamId = this.value;
        if (!teamId) {
            resetPlayers(texts.selectTeamFirst);
            return;
        }
        var players = sqdPlayersByTeam[teamId] || [];
        if (!players.length) {
            resetPlayers(texts.noPlayers, 'apf-hint apf-hint-error');
            return;
        }
        playerSelect.innerHTML = '';
        players.forEach(function (p) {
            var opt = document.createElement('option');
            opt.value = p.id;
            opt.textContent = p.name + (p.role ? ' (' + p.role + ')' : '');
            playerSelect.appendChild(opt);
        });
        playerSelect.disabled = false;
        playerCount.textContent = players.length + ' ' + texts.playersFound;
        playerCount.className = 'apf-hint apf-hint-success';
    });

    document.getElementById('sqdCreateForm').addEventListener('submit', function (e) {
        if (!matchIdInput.value || !teamSelect.value || playerSelect.disabled || playerSelect.selectedOptions.length === 0) {
            e.preventDefault();
        }
    });
})();
</script>
@endsection

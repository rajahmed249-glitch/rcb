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
        'selectPlayers' => __('site.admin.select_players'),
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
            <div class="apf-ms" id="sqdPlayerMs">
                <div class="apf-ms-control" id="sqdMsControl">
                    <div class="apf-ms-selected" id="sqdMsSelected"></div>
                    <span class="apf-ms-placeholder" id="sqdMsPlaceholder">{{ __('site.admin.select_team_first') }}</span>
                    <i class="fas fa-chevron-down apf-ms-arrow"></i>
                </div>
                <div class="apf-ms-dropdown" id="sqdMsDropdown">
                    <input type="text" class="apf-ms-search" id="sqdMsSearch" placeholder="{{ __('site.admin.search_players') }}..." autocomplete="off">
                    <div class="apf-ms-options" id="sqdMsOptions"></div>
                </div>
            </div>
            <div id="sqdMsHidden"></div>
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

<script>
    var sqdMatches = @js($sqdMatchesData);
    var sqdPlayersByTeam = @js($playersByTeam);
    var sqdRcbTeam = @js($rcbTeam ? ['id' => (string) $rcbTeam->id, 'name' => $rcbTeam->name] : null);
    var sqdTexts = @js($sqdTexts);
</script>
<script>
(function () {
    var tournamentSelect = document.getElementById('sqdTournament');
    var matchSelect = document.getElementById('sqdMatch');
    var teamSelect = document.getElementById('sqdTeam');
    var msControl = document.getElementById('sqdMsControl');
    var msDropdown = document.getElementById('sqdMsDropdown');
    var msSearch = document.getElementById('sqdMsSearch');
    var msPlaceholder = document.getElementById('sqdMsPlaceholder');
    var playerCount = document.getElementById('sqdPlayerCount');
    var matchIdInput = document.getElementById('sqdMatchId');

    var sqdSelectedPlayers = [];
    var sqdMsSearchQuery = '';

    function resetTeam() {
        teamSelect.innerHTML = '';
        var teamPlaceholder = document.createElement('option');
        teamPlaceholder.value = '';
        teamPlaceholder.textContent = sqdTexts.selectTeam;
        teamSelect.appendChild(teamPlaceholder);
        teamSelect.disabled = true;
    }

    function resetPlayers() {
        sqdSelectedPlayers = [];
        sqdMsSearchQuery = '';
        if (msSearch) msSearch.value = '';
        document.getElementById('sqdMsOptions').innerHTML = '';
        msDropdown.classList.remove('open');
        msPlaceholder.textContent = sqdTexts.selectTeamFirst;
        playerCount.textContent = sqdTexts.selectTeamToLoad;
        playerCount.className = 'apf-hint';
        sqdRenderChips();
    }

    function sqdRenderChips() {
        var wrap = document.getElementById('sqdMsSelected');
        var hidden = document.getElementById('sqdMsHidden');
        wrap.innerHTML = '';
        hidden.innerHTML = '';
        sqdSelectedPlayers.forEach(function (p, i) {
            var chip = document.createElement('span');
            chip.className = 'apf-ms-chip';
            chip.innerHTML = p.name + ' <button type="button" class="apf-ms-remove" data-index="' + i + '">&times;</button>';
            wrap.appendChild(chip);
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'player_ids[]';
            input.value = p.id;
            hidden.appendChild(input);
        });
        msPlaceholder.style.display = sqdSelectedPlayers.length ? 'none' : '';
        sqdApplyVisibility();
    }

    function sqdApplyVisibility() {
        document.querySelectorAll('#sqdMsOptions .apf-ms-option').forEach(function (opt) {
            var val = String(opt.getAttribute('data-value'));
            var isSelected = sqdSelectedPlayers.some(function (p) { return p.id === val; });
            opt.classList.toggle('selected', isSelected);
            var matchesSearch = opt.getAttribute('data-name').toLowerCase().indexOf(sqdMsSearchQuery) >= 0;
            opt.style.display = (isSelected || !matchesSearch) ? 'none' : '';
        });
    }

    function sqdToggleOption(opt) {
        var id = String(opt.getAttribute('data-value'));
        var name = opt.getAttribute('data-name');
        var idx = sqdSelectedPlayers.findIndex(function (p) { return p.id === id; });
        if (idx >= 0) { sqdSelectedPlayers.splice(idx, 1); }
        else { sqdSelectedPlayers.push({ id: id, name: name }); }
        sqdRenderChips();
    }

    tournamentSelect.addEventListener('change', function () {
        matchSelect.innerHTML = '';
        var placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = sqdTexts.selectMatch;
        matchSelect.appendChild(placeholder);
        matchSelect.disabled = true;
        matchIdInput.value = '';

        resetTeam();
        resetPlayers();

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
        resetPlayers();
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
        sqdSelectedPlayers = [];
        sqdMsSearchQuery = '';
        if (msSearch) msSearch.value = '';
        document.getElementById('sqdMsOptions').innerHTML = '';
        msDropdown.classList.remove('open');

        if (!teamId) {
            msPlaceholder.textContent = sqdTexts.selectTeamFirst;
            msPlaceholder.style.display = '';
            playerCount.textContent = sqdTexts.selectTeamToLoad;
            playerCount.className = 'apf-hint';
            sqdRenderChips();
            return;
        }

        var players = sqdPlayersByTeam[teamId] || [];
        msPlaceholder.textContent = sqdTexts.selectPlayers;
        if (!players.length) {
            playerCount.textContent = sqdTexts.noPlayers;
            playerCount.className = 'apf-hint apf-hint-error';
            sqdRenderChips();
            return;
        }
        var optionsBox = document.getElementById('sqdMsOptions');
        players.forEach(function (p) {
            var opt = document.createElement('div');
            opt.className = 'apf-ms-option';
            opt.setAttribute('data-value', p.id);
            opt.setAttribute('data-name', p.name);
            var nameSpan = document.createElement('span');
            nameSpan.className = 'apf-ms-option-name';
            nameSpan.textContent = p.name + (p.role ? ' (' + p.role + ')' : '');
            opt.appendChild(nameSpan);
            optionsBox.appendChild(opt);
        });
        playerCount.textContent = players.length + ' ' + sqdTexts.playersFound;
        playerCount.className = 'apf-hint apf-hint-success';
        sqdRenderChips();
    });

    msControl.addEventListener('click', function () {
        if (!teamSelect.value) return;
        msDropdown.classList.toggle('open');
        if (msDropdown.classList.contains('open') && msSearch) { msSearch.focus(); }
    });

    document.addEventListener('click', function (e) {
        var ms = document.getElementById('sqdPlayerMs');
        if (ms && !ms.contains(e.target)) { msDropdown.classList.remove('open'); }
    });

    document.getElementById('sqdMsOptions').addEventListener('click', function (e) {
        var opt = e.target.closest('.apf-ms-option');
        if (opt) sqdToggleOption(opt);
    });

    document.getElementById('sqdMsSelected').addEventListener('click', function (e) {
        var btn = e.target.closest('.apf-ms-remove');
        if (btn) {
            e.stopPropagation();
            sqdSelectedPlayers.splice(parseInt(btn.getAttribute('data-index'), 10), 1);
            sqdRenderChips();
        }
    });

    if (msSearch) {
        msSearch.addEventListener('input', function () {
            sqdMsSearchQuery = this.value.toLowerCase();
            sqdApplyVisibility();
        });
    }

    document.getElementById('sqdCreateForm').addEventListener('submit', function (e) {
        if (!matchIdInput.value || !teamSelect.value || sqdSelectedPlayers.length === 0) {
            e.preventDefault();
        }
    });
})();
</script>
@endsection

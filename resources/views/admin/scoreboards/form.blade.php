@extends('admin.layout')

@section('title', __('site.admin.edit_scoreboard'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-edit apl-title-ico"></i> {{ __('site.admin.edit_scoreboard') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.scoreboards.index') }}">{{ __('site.admin.scoreboard') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.edit') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.scoreboards.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_scoreboards') }}
        </a>
        <a href="{{ route('admin.scoreboards.create') }}" class="apl-tab">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_scoreboard') }}
        </a>
        <span class="apl-tab active">
            <i class="fas fa-edit"></i> {{ __('site.admin.edit_scoreboard') }}
        </span>
    </div>
</div>

<form method="POST" action="{{ route('admin.scoreboards.update', $match) }}" class="apf-card">
    @csrf
    @method('PUT')
    <input type="hidden" name="stats_side" value="{{ $side === 'summary' ? '' : $side }}">
    @if ($side === 'summary')
        @php
            $rcbScoreParts = array_pad(explode('-', (string) old('team_a_score', $match->rcb_score)), 2, '');
            $oppScoreParts = array_pad(explode('-', (string) old('team_b_score', $match->opponent_score)), 2, '');
            $rcbRuns = $rcbScoreParts[0] !== '' ? $rcbScoreParts[0] : '0';
            $rcbWkts = $rcbScoreParts[1] !== '' ? $rcbScoreParts[1] : '0';
            $oppRuns = $oppScoreParts[0] !== '' ? $oppScoreParts[0] : '0';
            $oppWkts = $oppScoreParts[1] !== '' ? $oppScoreParts[1] : '0';
            $teamAName = $rcbTeam?->display_name ?? 'Real Challenger Boys';
            $teamBName = $match->display_opponent_name;
        @endphp

        <div class="apf-heading"><i class="fas fa-info-circle"></i> {{ __('site.admin.match_result_details') }}</div>

        <div class="scb-info-box">
            <i class="fas fa-users"></i>
            <span class="scb-info-text">
                <strong>{{ $match->match_name ?: ('RCB vs ' . $teamBName) }}</strong> &bull;
                {{ $teamAName }} vs {{ $teamBName }}
                &bull; <strong>{{ __('site.admin.venue') }}:</strong> {{ $match->venue }}
            </span>
        </div>

        <div class="apf-row">
            <div class="apf-group">
                <label>{{ __('site.admin.toss_won_by') }} <span class="apf-required">*</span></label>
                <select name="toss_winner_id" required>
                    <option value="">{{ __('site.admin.select_team') }}</option>
                    @if ($rcbTeam)
                        <option value="{{ $rcbTeam->id }}" @selected((string) old('toss_winner_id', $match->toss_winner_id) === (string) $rcbTeam->id)>{{ $teamAName }}</option>
                    @endif
                    @if ($opponentTeam)
                        <option value="{{ $opponentTeam->id }}" @selected((string) old('toss_winner_id', $match->toss_winner_id) === (string) $opponentTeam->id)>{{ $teamBName }}</option>
                    @endif
                </select>
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.elected_to') }} <span class="apf-required">*</span></label>
                <select name="toss_elected" required>
                    <option value="">{{ __('site.admin.select_one') }}</option>
                    <option value="bat" @selected(old('toss_elected', $match->toss_elected) === 'bat')>{{ __('site.admin.bat') }}</option>
                    <option value="bowl" @selected(old('toss_elected', $match->toss_elected) === 'bowl')>{{ __('site.admin.bowl') }}</option>
                </select>
            </div>
        </div>

        <div class="apf-row apf-row-3">
            <div class="apf-group">
                <label>{{ $teamAName }} {{ __('site.admin.score') }} <span class="apf-required">*</span></label>
                <input type="number" name="team_a_score" id="team_a_score" min="0" max="999" required placeholder="{{ __('site.admin.score') }}" value="{{ $rcbRuns }}" oninput="validateScore(this, 'scoreErrorA')" onkeydown="preventInvalidInput(this, event)">
                <span class="scb-error-text" id="scoreErrorA">{{ __('site.admin.score_error') }}</span>
                <span class="scb-field-hint">{{ __('site.admin.score_hint') }}</span>
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.wickets') }} <span class="apf-required">*</span></label>
                <input type="number" name="team_a_wickets" id="team_a_wickets" min="0" max="10" required placeholder="{{ __('site.admin.wickets') }}" value="{{ $rcbWkts }}" oninput="validateWickets(this, 'wicketsErrorA')" onkeydown="preventInvalidInput(this, event)">
                <span class="scb-error-text" id="wicketsErrorA">{{ __('site.admin.wickets_error') }}</span>
                <span class="scb-field-hint">{{ __('site.admin.wickets_hint') }}</span>
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.overs') }} <span class="apf-required">*</span></label>
                <input type="text" name="team_a_overs" id="team_a_overs" required placeholder="e.g., 10.2" value="{{ old('team_a_overs', $match->rcb_overs ?: '0.0') }}" oninput="validateOvers(this, 'oversErrorA')" onkeydown="preventOversInput(this, event)" maxlength="4">
                <span class="scb-error-text" id="oversErrorA">{{ __('site.admin.overs_error') }}</span>
                <span class="scb-field-hint">{{ __('site.admin.overs_hint') }}</span>
            </div>
        </div>

        <div class="apf-row apf-row-3">
            <div class="apf-group">
                <label>{{ $teamBName }} {{ __('site.admin.score') }} <span class="apf-required">*</span></label>
                <input type="number" name="team_b_score" id="team_b_score" min="0" max="999" required placeholder="{{ __('site.admin.score') }}" value="{{ $oppRuns }}" oninput="validateScore(this, 'scoreErrorB')" onkeydown="preventInvalidInput(this, event)">
                <span class="scb-error-text" id="scoreErrorB">{{ __('site.admin.score_error') }}</span>
                <span class="scb-field-hint">{{ __('site.admin.score_hint') }}</span>
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.wickets') }} <span class="apf-required">*</span></label>
                <input type="number" name="team_b_wickets" id="team_b_wickets" min="0" max="10" required placeholder="{{ __('site.admin.wickets') }}" value="{{ $oppWkts }}" oninput="validateWickets(this, 'wicketsErrorB')" onkeydown="preventInvalidInput(this, event)">
                <span class="scb-error-text" id="wicketsErrorB">{{ __('site.admin.wickets_error') }}</span>
                <span class="scb-field-hint">{{ __('site.admin.wickets_hint') }}</span>
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.overs') }} <span class="apf-required">*</span></label>
                <input type="text" name="team_b_overs" id="team_b_overs" required placeholder="e.g., 10.2" value="{{ old('team_b_overs', $match->opponent_overs ?: '0.0') }}" oninput="validateOvers(this, 'oversErrorB')" onkeydown="preventOversInput(this, event)" maxlength="4">
                <span class="scb-error-text" id="oversErrorB">{{ __('site.admin.overs_error') }}</span>
                <span class="scb-field-hint">{{ __('site.admin.overs_hint') }}</span>
            </div>
        </div>

        <div class="apf-row apf-row-3">
            <div class="apf-group">
                <label>{{ __('site.admin.result_text') }}</label>
                <input type="text" name="result_text" value="{{ old('result_text', $match->result_text) }}" placeholder="{{ __('site.admin.result_placeholder') }}">
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.winner') }} <span class="apf-required" id="winnerRequired">*</span></label>
                <select name="winner_team_id" id="winner_team_id" required>
                    <option value="">{{ __('site.admin.select_winner') }}</option>
                    @if ($rcbTeam)
                        <option value="{{ $rcbTeam->id }}" @selected((string) old('winner_team_id', $match->winner_team_id) === (string) $rcbTeam->id)>{{ $teamAName }}</option>
                    @endif
                    @if ($opponentTeam)
                        <option value="{{ $opponentTeam->id }}" @selected((string) old('winner_team_id', $match->winner_team_id) === (string) $opponentTeam->id)>{{ $teamBName }}</option>
                    @endif
                </select>
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.player_of_match') }}</label>
                <select name="player_of_match_id">
                    <option value="">{{ __('site.admin.select_player') }}</option>
                    @foreach ($squadPlayers as $sp)
                        <option value="{{ $sp->id }}" @selected((string) old('player_of_match_id', $match->player_of_match_id) === (string) $sp->id)>{{ $sp->display_name }}</option>
                    @endforeach
                </select>
                <span class="scb-field-hint">{{ __('site.admin.only_squad_players_hint') }}</span>
            </div>
        </div>

        <div class="apf-row">
            <div class="apf-group">
                <label>{{ __('site.admin.status') }} <span class="apf-required">*</span></label>
                <select name="status" id="matchStatus" required onchange="toggleWinnerRequired(this.value)">
                    <option value="upcoming" @selected(old('status', $match->status) === 'upcoming')>{{ __('site.admin.upcoming') }}</option>
                    <option value="live" @selected(old('status', $match->status) === 'live')>{{ __('site.admin.live') }}</option>
                    <option value="completed" @selected(old('status', $match->status) === 'completed')>{{ __('site.admin.completed') }}</option>
                    <option value="abandoned" @selected(old('status', $match->status) === 'abandoned')>{{ __('site.admin.abandoned') }}</option>
                </select>
            </div>
        </div>

    @else
        <div class="apf-heading"><i class="fas fa-chart-bar"></i> {{ __('site.admin.score_information') }}</div>

        <div class="scb-score-tabs">
            <button type="button" class="scb-score-tab active" data-tab="batting"><i class="fas fa-baseball-ball"></i> {{ __('site.admin.batting_stats') }}</button>
            <button type="button" class="scb-score-tab" data-tab="bowling"><i class="fas fa-bowling-ball"></i> {{ __('site.admin.bowling_stats') }}</button>
            <button type="button" class="scb-score-tab" data-tab="extras"><i class="fas fa-plus-circle"></i> {{ __('site.admin.extras') }}</button>
            <button type="button" class="scb-score-tab" data-tab="fow"><i class="fas fa-chart-line"></i> {{ __('site.admin.fall_of_wickets') }}</button>
            <button type="button" class="scb-score-tab" data-tab="commentary"><i class="fas fa-comment"></i> {{ __('site.admin.commentary') }}</button>
        </div>

        <div id="scb-tab-batting" class="scb-tab-panel active">
            <div class="apf-stat-wrap">
                <table class="apf-stat-table">
                    <thead>
                        <tr>
                            <th class="apf-st-player">{{ __('site.admin.batter') }}</th>
                            <th>{{ __('site.admin.runs_short') }}</th>
                            <th>{{ __('site.admin.balls_short') }}</th>
                            <th>{{ __('site.admin.fours_short') }}</th>
                            <th>{{ __('site.admin.sixes_short') }}</th>
                            <th>{{ __('site.admin.dismissal') }}</th>
                            <th class="apf-st-player">{{ __('site.admin.fielder') }}</th>
                            <th class="apf-st-player">{{ __('site.admin.bowler') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="bat-{{ $side }}-body" data-side="{{ $side }}">
                        @foreach (old("stats.{$side}.batting", $statRows['batting']) as $i => $row)
                            @include('admin.scoreboards.partials.batting-row', ['side' => $side, 'i' => $i, 'row' => $row])
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="apf-stat-add" onclick="addStatRow('bat', '{{ $side }}')">
                    <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_batter') }}
                </button>
            </div>
        </div>

        <div id="scb-tab-bowling" class="scb-tab-panel">
            <div class="apf-stat-wrap">
                <table class="apf-stat-table">
                    <thead>
                        <tr>
                            <th class="apf-st-player">{{ __('site.admin.bowler') }}</th>
                            <th>{{ __('site.admin.overs_col') }}</th>
                            <th>{{ __('site.admin.balls_short') }}</th>
                            <th>{{ __('site.admin.maidens_col') }}</th>
                            <th>{{ __('site.admin.runs_short') }}</th>
                            <th>{{ __('site.admin.wickets_col') }}</th>
                            <th>{{ __('site.admin.wides_col') }}</th>
                            <th>{{ __('site.admin.noballs_col') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="bowl-{{ $side }}-body" data-side="{{ $side }}">
                        @foreach (old("stats.{$side}.bowling", $statRows['bowling']) as $i => $row)
                            @include('admin.scoreboards.partials.bowling-row', ['side' => $side, 'i' => $i, 'row' => $row])
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="apf-stat-add" onclick="addStatRow('bowl', '{{ $side }}')">
                    <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_bowler') }}
                </button>
            </div>
        </div>

        <div id="scb-tab-extras" class="scb-tab-panel">
            <div class="scb-extras-grid">
                <div class="apf-group">
                    <label>{{ __('site.admin.byes') }}</label>
                    <input type="number" id="scbByes" name="extras[byes]" min="0" placeholder="0" value="{{ old('extras.byes', $extrasRow->byes ?? 0) }}" oninput="calculateExtras()">
                </div>
                <div class="apf-group">
                    <label>{{ __('site.admin.leg_byes') }}</label>
                    <input type="number" id="scbLegByes" name="extras[leg_byes]" min="0" placeholder="0" value="{{ old('extras.leg_byes', $extrasRow->leg_byes ?? 0) }}" oninput="calculateExtras()">
                </div>
                <div class="apf-group">
                    <label>{{ __('site.admin.wides_col') }}</label>
                    <input type="number" id="scbWides" name="extras[wides]" min="0" placeholder="0" value="{{ old('extras.wides', $extrasRow->wides ?? 0) }}" oninput="calculateExtras()">
                </div>
                <div class="apf-group">
                    <label>{{ __('site.admin.noballs_col') }}</label>
                    <input type="number" id="scbNoBalls" name="extras[no_balls]" min="0" placeholder="0" value="{{ old('extras.no_balls', $extrasRow->no_balls ?? 0) }}" oninput="calculateExtras()">
                </div>
            </div>

            <div class="scb-extras-total">
                <label>{{ __('site.admin.total_extras') }}</label>
                <span class="scb-extras-total-value" id="scbTotalExtras">0</span>
                <input type="hidden" name="extras[total]" id="scbTotalExtrasInput" value="{{ old('extras.total', $extrasRow->total ?? ($side === 'rcb' ? $match->rcb_extras : $match->opponent_extras)) }}">
            </div>
        </div>

        <div id="scb-tab-fow" class="scb-tab-panel">
            <div class="apf-stat-wrap">
                <table class="apf-stat-table">
                    <thead>
                        <tr>
                            <th>{{ __('site.admin.score') }}</th>
                            <th>{{ __('site.admin.wicket_no') }}</th>
                            <th class="apf-st-player">{{ __('site.admin.player_out') }}</th>
                            <th>{{ __('site.admin.over') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="fow-{{ $side }}-body" data-side="{{ $side }}">
                        @foreach (old("fow.{$side}", $fowRows) as $i => $row)
                            @include('admin.scoreboards.partials.fow-row', ['side' => $side, 'i' => $i, 'row' => $row])
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="apf-stat-add" onclick="addStatRow('fow', '{{ $side }}')">
                    <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_wicket') }}
                </button>
            </div>
        </div>

        <div id="scb-tab-commentary" class="scb-tab-panel">
            <div class="apf-stat-wrap">
                <table class="apf-stat-table">
                    <thead>
                        <tr>
                            <th>{{ __('site.admin.end_of_over') }}</th>
                            <th>{{ __('site.admin.score_at') }}</th>
                            <th>{{ __('site.admin.ball_number') }}</th>
                            <th>{{ __('site.admin.ball_per_run') }}</th>
                            <th class="apf-st-wide">{{ __('site.admin.description') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="commentary-{{ $side }}-body" data-side="{{ $side }}">
                        @foreach (old("commentary.{$side}", $commentaryRows) as $i => $row)
                            @include('admin.scoreboards.partials.commentary-row', ['side' => $side, 'i' => $i, 'row' => $row])
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="apf-stat-add" onclick="addStatRow('commentary', '{{ $side }}')">
                    <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_commentary') }}
                </button>
            </div>
        </div>
    @endif

    <div class="apf-actions">
        <a href="{{ route('admin.scoreboards.index') }}" class="apf-cancel">
            <i class="fas fa-arrow-left"></i> {{ __('site.admin.cancel') }}
        </a>
        @if ($side !== 'summary')
            <button type="reset" class="apf-reset">
                <i class="fas fa-undo-alt"></i> {{ __('site.admin.reset') }}
            </button>
        @endif
        <button type="submit" class="apf-submit">
            <i class="fas fa-save"></i> {{ $side === 'summary' ? __('site.admin.update_result') : __('site.admin.save') }}
        </button>
    </div>
</form>

<template id="tpl-bat-rcb">@include('admin.scoreboards.partials.batting-row', ['side' => 'rcb', 'i' => '__IDX__', 'row' => null])</template>
<template id="tpl-bat-opponent">@include('admin.scoreboards.partials.batting-row', ['side' => 'opponent', 'i' => '__IDX__', 'row' => null])</template>
<template id="tpl-bowl-rcb">@include('admin.scoreboards.partials.bowling-row', ['side' => 'rcb', 'i' => '__IDX__', 'row' => null])</template>
<template id="tpl-bowl-opponent">@include('admin.scoreboards.partials.bowling-row', ['side' => 'opponent', 'i' => '__IDX__', 'row' => null])</template>
<template id="tpl-fow-rcb">@include('admin.scoreboards.partials.fow-row', ['side' => 'rcb', 'i' => '__IDX__', 'row' => null])</template>
<template id="tpl-fow-opponent">@include('admin.scoreboards.partials.fow-row', ['side' => 'opponent', 'i' => '__IDX__', 'row' => null])</template>
<template id="tpl-commentary-rcb">@include('admin.scoreboards.partials.commentary-row', ['side' => 'rcb', 'i' => '__IDX__', 'row' => null])</template>
<template id="tpl-commentary-opponent">@include('admin.scoreboards.partials.commentary-row', ['side' => 'opponent', 'i' => '__IDX__', 'row' => null])</template>

<script>
var SCB_SHOW_FIELDER = ['caught', 'runout', 'stumped', 'c&b', 'hitwicket'];
var SCB_SHOW_BOWLER = ['bowled', 'caught', 'lbw', 'stumped', 'c&b', 'hitwicket'];
function toggleWon(yes) {
    var y = document.getElementById('wonYesLabel');
    var n = document.getElementById('wonNoLabel');
    if (y) y.classList.toggle('active', yes);
    if (n) n.classList.toggle('active', !yes);
}

function switchScoreSide(side) {
    if (!side) return;
    var base = '{{ url('admin/scoreboards') }}/{{ $match->id }}/edit';
    window.location.href = base + '?team=' + side;
}

function preventInvalidInput(input, event) {
    var key = event.key;
    var allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'];
    if (allowedKeys.indexOf(key) !== -1) return true;
    if (/^[0-9]$/.test(key)) {
        if (input.id === 'team_a_score' || input.id === 'team_b_score') {
            if (input.value.replace(/[^0-9]/g, '').length >= 3) { event.preventDefault(); return false; }
        }
        if (input.id === 'team_a_wickets' || input.id === 'team_b_wickets') {
            if (input.value.replace(/[^0-9]/g, '').length >= 2) { event.preventDefault(); return false; }
        }
        return true;
    }
    event.preventDefault();
    return false;
}

function preventOversInput(input, event) {
    var key = event.key;
    var allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'];
    if (allowedKeys.indexOf(key) !== -1) return true;
    if (/^[0-9.]$/.test(key)) {
        if (key === '.' && input.value.indexOf('.') !== -1) { event.preventDefault(); return false; }
        if (input.value.replace(/[^0-9.]/g, '').length >= 4) { event.preventDefault(); return false; }
        return true;
    }
    event.preventDefault();
    return false;
}

function validateScore(input, errorId) {
    var errorEl = document.getElementById(errorId);
    var value = parseInt(input.value, 10);
    if (input.value === '' || isNaN(value)) {
        if (errorEl) errorEl.classList.remove('show');
        input.classList.remove('is-invalid');
        return true;
    }
    if (value < 0 || value > 999) {
        if (errorEl) errorEl.classList.add('show');
        input.classList.add('is-invalid');
        return false;
    }
    if (errorEl) errorEl.classList.remove('show');
    input.classList.remove('is-invalid');
    return true;
}

function validateWickets(input, errorId) {
    var errorEl = document.getElementById(errorId);
    var value = parseInt(input.value, 10);
    if (input.value === '' || isNaN(value)) {
        if (errorEl) errorEl.classList.remove('show');
        input.classList.remove('is-invalid');
        return true;
    }
    if (value < 0 || value > 10) {
        if (errorEl) errorEl.classList.add('show');
        input.classList.add('is-invalid');
        return false;
    }
    if (errorEl) errorEl.classList.remove('show');
    input.classList.remove('is-invalid');
    return true;
}

function validateOvers(input, errorId) {
    var errorEl = document.getElementById(errorId);
    var value = input.value;
    if (value === '') {
        if (errorEl) errorEl.classList.remove('show');
        input.classList.remove('is-invalid');
        return true;
    }
    var fullPattern = /^\d{1,2}\.\d{1}$/;
    if (!fullPattern.test(value) || parseFloat(value) < 1.0 || parseFloat(value) > 99.0) {
        if (errorEl) errorEl.classList.add('show');
        input.classList.add('is-invalid');
        return false;
    }
    if (errorEl) errorEl.classList.remove('show');
    input.classList.remove('is-invalid');
    return true;
}

function calculateExtras() {
    var ids = ['scbByes', 'scbLegByes', 'scbWides', 'scbNoBalls'];
    var total = 0;
    ids.forEach(function (id) {
        var el = document.getElementById(id);
        var v = parseInt(el && el.value, 10);
        if (!isNaN(v) && v > 0) total += v;
    });
    var span = document.getElementById('scbTotalExtras');
    var input = document.getElementById('scbTotalExtrasInput');
    if (span) span.textContent = String(total);
    if (input) input.value = String(total);
}

(function () {
    if (document.getElementById('scbTotalExtras')) calculateExtras();
})();

function toggleWinnerRequired(status) {
    var req = document.getElementById('winnerRequired');
    var sel = document.getElementById('winner_team_id');
    if (!req || !sel) return;
    var completed = status === 'completed';
    req.style.display = completed ? '' : 'none';
    if (completed) {
        sel.setAttribute('required', 'required');
    } else {
        sel.removeAttribute('required');
    }
}

(function () {
    var statusSel = document.getElementById('matchStatus');
    if (statusSel) toggleWinnerRequired(statusSel.value);
    refreshAllPlayerSelects();
    updateAllDismissalFields();
})();

function addStatRow(kind, side) {
    var tpl = document.getElementById('tpl-' + kind + '-' + side);
    var body = document.getElementById(kind + '-' + side + '-body');
    if (!tpl || !body) return;

    var next = body.querySelectorAll('tr').length;
    var html = tpl.innerHTML.replaceAll('__IDX__', String(next));
    var holder = document.createElement('tbody');
    holder.innerHTML = html;
    var tr = holder.firstElementChild;
    if (tr) body.appendChild(tr);
    refreshAllPlayerSelects();
    updateAllDismissalFields();
}

function removeStatRow(btn) {
    var tr = btn.closest('tr');
    if (tr) tr.remove();
    refreshAllPlayerSelects();
}

function refreshSelectGroup(cls) {
    var selects = Array.prototype.slice.call(document.querySelectorAll('select.' + cls));
    var chosen = {};
    selects.forEach(function (s) { if (s.value) chosen[s.value] = true; });
    selects.forEach(function (s) {
        Array.prototype.forEach.call(s.options, function (opt) {
            if (!opt.value) return;
            opt.style.display = (chosen[opt.value] && s.value !== opt.value) ? 'none' : '';
        });
    });
}

function refreshAllPlayerSelects() {
    refreshSelectGroup('scb-bat-select');
    refreshSelectGroup('scb-bowl-select');
}

function applyDismissalCell(td, show) {
    if (!td) return;
    td.classList.toggle('scb-cell-off', !show);
    Array.prototype.forEach.call(td.querySelectorAll('select, input'), function (el) {
        el.disabled = !show;
        if (!show && el.tagName === 'SELECT') el.value = '';
    });
}

function updateDismissalFields(sel) {
    var tr = sel.closest('tr');
    if (!tr) return;
    var v = sel.value;
    applyDismissalCell(tr.querySelector('.scb-td-fielder'), SCB_SHOW_FIELDER.indexOf(v) !== -1);
    applyDismissalCell(tr.querySelector('.scb-td-bowler'), SCB_SHOW_BOWLER.indexOf(v) !== -1);
}

function updateAllDismissalFields() {
    Array.prototype.forEach.call(document.querySelectorAll('select.scb-dismissal-select'), updateDismissalFields);
}

document.addEventListener('change', function (e) {
    var t = e.target;
    if (!t || !t.classList) return;
    if (t.classList.contains('scb-bat-select')) refreshSelectGroup('scb-bat-select');
    if (t.classList.contains('scb-bowl-select')) refreshSelectGroup('scb-bowl-select');
});

(function () {
    var tabBtns = document.querySelectorAll('.scb-score-tab');
    tabBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            tabBtns.forEach(function (b) { b.classList.remove('active'); });
            document.querySelectorAll('.scb-tab-panel').forEach(function (p) { p.classList.remove('active'); });
            btn.classList.add('active');
            var panel = document.getElementById('scb-tab-' + btn.dataset.tab);
            if (panel) panel.classList.add('active');
        });
    });

    var params = new URLSearchParams(window.location.search);
    var team = params.get('team');
    if (team === 'summary' || !team) return;
    var body = document.getElementById('bat-' + team + '-body');
    if (!body) return;
    var wrap = body.closest('.apf-stat-wrap');
    if (!wrap) return;
    wrap.classList.add('scb-side-focus');
    setTimeout(function () {
        wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 200);
})();
</script>
@endsection

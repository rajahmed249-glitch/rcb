@extends('admin.layout')

@section('title', __('site.admin.edit_score') . ' — Match ' . $match->match_number)

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-clipboard-list apl-title-ico"></i> {{ __('site.admin.edit_score') }} — Match {{ $match->match_number }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.scoreboards.index') }}">{{ __('site.admin.scoreboard') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.edit_score') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.scoreboards.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_scoreboards') }}
        </a>
        <a href="{{ route('matches.show', $match) }}" target="_blank" class="apl-tab">
            <i class="fas fa-eye"></i> {{ __('site.admin.view_page') }}
        </a>
    </div>
</div>

<form method="POST" action="{{ route('admin.scoreboards.update', $match) }}" class="apf-card">
    @csrf
    @method('PUT')
    <input type="hidden" name="stats_side" value="{{ $side === 'summary' ? '' : $side }}">
    @if ($side === 'summary')
        <input type="hidden" name="save_summary" value="1">
    @endif

    <div class="apf-heading"><i class="fas fa-info-circle"></i> {{ __('site.admin.match_information') }}</div>

    <div class="apf-row apf-row-3">
        <div class="apf-group">
            <label>{{ __('site.admin.tournament') }}</label>
            <select disabled>
                <option>{{ $match->tournament?->display_name ?? '—' }}</option>
            </select>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.match') }}</label>
            <select disabled>
                <option>{{ $match->match_name ?: ('RCB vs ' . $match->display_opponent_name) }} (M-{{ $match->match_number }})</option>
            </select>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.team') }}</label>
            <select id="scbTeamSwitch" onchange="switchScoreSide(this.value)">
                <option value="">{{ __('site.admin.select_one') }}</option>
                <option value="rcb" @selected($side === 'rcb')>{{ __('site.admin.rcb_side') }}</option>
                <option value="opponent" @selected($side === 'opponent')>{{ $match->display_opponent_name }}</option>
            </select>
        </div>
    </div>

    @if ($side === 'summary')
        <div class="apf-heading"><i class="fas fa-chart-bar"></i> {{ __('site.admin.match_result') }}</div>

        <div class="apf-row">
            <div class="apf-group">
                <label>{{ __('site.admin.status') }} <span class="apf-required">*</span></label>
                <select name="status" required>
                    <option value="upcoming" @selected(old('status', $match->status) === 'upcoming')>{{ __('site.admin.upcoming') }}</option>
                    <option value="completed" @selected(old('status', $match->status) === 'completed')>{{ __('site.admin.completed') }}</option>
                </select>
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.result_text') }}</label>
                <input type="text" name="result_text" value="{{ old('result_text', $match->result_text) }}" placeholder="Real Challenger Boys won by 18 runs">
            </div>
        </div>

        <div class="apf-row apf-row-3">
            <div class="apf-group">
                <label>RCB {{ __('site.admin.rcb_score') }}</label>
                <input type="text" name="rcb_score" value="{{ old('rcb_score', $match->rcb_score) }}" placeholder="202-8">
            </div>
            <div class="apf-group">
                <label>RCB {{ __('site.admin.rcb_overs') }}</label>
                <input type="text" name="rcb_overs" value="{{ old('rcb_overs', $match->rcb_overs) }}" placeholder="15.0">
            </div>
            <div class="apf-group"></div>
        </div>

        <div class="apf-row apf-row-3">
            <div class="apf-group">
                <label>{{ __('site.admin.opponent_score') }}</label>
                <input type="text" name="opponent_score" value="{{ old('opponent_score', $match->opponent_score) }}" placeholder="178-9">
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.opponent_overs') }}</label>
                <input type="text" name="opponent_overs" value="{{ old('opponent_overs', $match->opponent_overs) }}" placeholder="15.0">
            </div>
            <div class="apf-group"></div>
        </div>

        <div class="apf-row">
            <div class="apf-group">
                <label>{{ __('site.admin.won') }}</label>
                <div class="apf-toggle">
                    <label class="apf-toggle-option {{ old('won', $match->won) ? 'active' : '' }}" id="wonYesLabel">
                        <input type="radio" name="won" value="1" @checked(old('won', $match->won)) onchange="toggleWon(true)">
                        <span class="apf-toggle-icon">🏆</span>
                        <span>{{ __('site.admin.won') }}</span>
                    </label>
                    <label class="apf-toggle-option {{ old('won', $match->won) ? '' : 'active' }}" id="wonNoLabel">
                        <input type="radio" name="won" value="0" @checked(! old('won', $match->won)) onchange="toggleWon(false)">
                        <span class="apf-toggle-icon">➖</span>
                        <span>{{ __('site.admin.not_won') }}</span>
                    </label>
                </div>
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
            <div class="apf-row">
                <div class="apf-group">
                    <label>{{ __('site.admin.total_extras') }}</label>
                    <input type="number" name="extras[{{ $side }}][total]" value="{{ old("extras.{$side}.total", $side === 'rcb' ? $match->rcb_extras : $match->opponent_extras) }}" min="0" placeholder="0">
                </div>
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
        <button type="reset" class="apf-reset">
            <i class="fas fa-undo-alt"></i> {{ __('site.admin.reset') }}
        </button>
        <button type="submit" class="apf-submit">
            <i class="fas fa-save"></i> {{ __('site.admin.save') }}
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
}

function removeStatRow(btn) {
    var tr = btn.closest('tr');
    if (tr) tr.remove();
}

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

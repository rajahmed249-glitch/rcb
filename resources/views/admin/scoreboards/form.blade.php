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
            <i class="fas fa-list"></i> {{ __('site.admin.scoreboard') }}
        </a>
        <a href="{{ route('matches.show', $match) }}" target="_blank" class="apl-tab">
            <i class="fas fa-eye"></i> {{ __('site.admin.view_page') }}
        </a>
    </div>
</div>

<form method="POST" action="{{ route('admin.scoreboards.update', $match) }}" class="apf-card">
    @csrf
    @method('PUT')

    <div class="apf-heading">{{ __('site.admin.scoreboard_summary') }}</div>

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
        <div class="apf-group">
            <label>RCB {{ __('site.admin.extras') }}</label>
            <input type="number" name="stats[rcb][extras]" value="{{ old('stats.rcb.extras', $match->rcb_extras ?? 0) }}" min="0">
        </div>
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
        <div class="apf-group">
            <label>{{ __('site.admin.opponent_side') }} {{ __('site.admin.extras') }}</label>
            <input type="number" name="stats[opponent][extras]" value="{{ old('stats.opponent.extras', $match->opponent_extras ?? 0) }}" min="0">
        </div>
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

    @foreach (['rcb' => __('site.admin.rcb_side'), 'opponent' => __('site.admin.opponent_side')] as $side => $sideLabel)
        <div class="apf-heading">{{ $sideLabel }} — {{ __('site.admin.batting_stats') }}</div>

        <div class="apf-stat-wrap">
            <table class="apf-stat-table">
                <thead>
                    <tr>
                        <th class="apf-st-player">{{ __('site.admin.player_name') }}</th>
                        <th>{{ __('site.admin.runs_short') }}</th>
                        <th>{{ __('site.admin.balls_short') }}</th>
                        <th>{{ __('site.admin.fours_short') }}</th>
                        <th>{{ __('site.admin.sixes_short') }}</th>
                        <th class="apf-st-wide">{{ __('site.admin.dismissal') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="bat-{{ $side }}-body" data-side="{{ $side }}">
                    @foreach (old("stats.{$side}.batting", $statRows[$side]['batting']->values()) as $i => $row)
                        @include('admin.scoreboards.partials.batting-row', ['side' => $side, 'i' => $i, 'row' => $row])
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="apf-stat-add" onclick="addStatRow('bat', '{{ $side }}')">
                <i class="fas fa-plus"></i> {{ __('site.admin.add_batsman') }}
            </button>
        </div>

        <div class="apf-heading">{{ $sideLabel }} — {{ __('site.admin.bowling_stats') }}</div>

        <div class="apf-stat-wrap">
            <table class="apf-stat-table">
                <thead>
                    <tr>
                        <th class="apf-st-player">{{ __('site.admin.player_name') }}</th>
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
                    @foreach (old("stats.{$side}.bowling", $statRows[$side]['bowling']->values()) as $i => $row)
                        @include('admin.scoreboards.partials.bowling-row', ['side' => $side, 'i' => $i, 'row' => $row])
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="apf-stat-add" onclick="addStatRow('bowl', '{{ $side }}')">
                <i class="fas fa-plus"></i> {{ __('site.admin.add_bowler') }}
            </button>
        </div>
    @endforeach

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

<template id="tpl-bat-rcb">
    @include('admin.scoreboards.partials.batting-row', ['side' => 'rcb', 'i' => '__IDX__', 'row' => null])
</template>
<template id="tpl-bat-opponent">
    @include('admin.scoreboards.partials.batting-row', ['side' => 'opponent', 'i' => '__IDX__', 'row' => null])
</template>
<template id="tpl-bowl-rcb">
    @include('admin.scoreboards.partials.bowling-row', ['side' => 'rcb', 'i' => '__IDX__', 'row' => null])
</template>
<template id="tpl-bowl-opponent">
    @include('admin.scoreboards.partials.bowling-row', ['side' => 'opponent', 'i' => '__IDX__', 'row' => null])
</template>

<script>
function toggleWon(yes) {
    var y = document.getElementById('wonYesLabel');
    var n = document.getElementById('wonNoLabel');
    if (y) y.classList.toggle('active', yes);
    if (n) n.classList.toggle('active', !yes);
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
</script>
@endsection

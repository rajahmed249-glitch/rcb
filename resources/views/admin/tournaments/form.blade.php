@extends('admin.layout')

@section('title', $tournament->exists ? __('site.admin.edit') . ' — ' . $tournament->name : __('site.admin.add_tournament'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-trophy apl-title-ico"></i> {{ $tournament->exists ? __('site.admin.edit') : __('site.admin.add_tournament') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.tournaments.index') }}">{{ __('site.admin.tournaments') }} /</a></li>
            <li class="apl-crumb-current">{{ $tournament->exists ? __('site.admin.edit') : __('site.admin.add_tournament') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.tournaments.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_tournaments') }}
        </a>
        <a href="{{ route('admin.tournaments.create') }}" class="apl-tab {{ !$tournament->exists ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_tournament') }}
        </a>
    </div>
</div>

<form method="POST"
      action="{{ $tournament->exists ? route('admin.tournaments.update', $tournament) : route('admin.tournaments.store') }}"
      class="apf-card">
    @csrf
    @if ($tournament->exists)
        @method('PUT')
    @endif

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.name_en') }} <span class="apf-required">*</span></label>
            <input type="text" name="name" value="{{ old('name', $tournament->name) }}" required placeholder="Enter tournament name in English">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.name_bn') }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $tournament->name_bn) }}" placeholder="বাংলায় নাম লিখুন">
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $tournament->sort_order) }}">
        </div>
    </div>

    <div class="apf-group" style="margin-bottom:18px;">
        <label>{{ __('site.admin.teams') }}</label>
        <div class="apf-ms" id="trnMultiSelect">
            <div class="apf-ms-control" id="trnMsControl">
                <div class="apf-ms-selected" id="trnMsSelected"></div>
                <span class="apf-ms-placeholder" data-placeholder="{{ __('site.admin.select_teams') }}">{{ __('site.admin.select_teams') }}</span>
                <i class="fas fa-chevron-down apf-ms-arrow"></i>
            </div>
            <div class="apf-ms-dropdown" id="trnMsDropdown">
                <input type="text" class="apf-ms-search" id="trnMsSearch" placeholder="{{ __('site.admin.search_players') }}..." autocomplete="off">
                <div class="apf-ms-options" id="trnMsOptions">
                    @foreach ($teams as $team)
                        <div class="apf-ms-option" data-value="{{ $team->id }}" data-name="{{ $team->name }}">
                            <span class="apf-ms-option-name">{{ $team->name }}</span>
                            @if ($team->is_rcb)
                                <span class="apf-ms-option-team">RCB</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div id="trnMsHidden"></div>
        <p class="apf-hint">{{ __('site.admin.tournament_teams_hint') }}</p>
    </div>

    <div class="apf-group" style="margin-bottom:18px;">
        <label>{{ __('site.admin.status') }}</label>
        <div class="apf-toggle">
            <label class="apf-toggle-option {{ old('is_active', $tournament->is_active ?? true) ? 'active' : '' }}" id="trnActiveLabel">
                <input type="radio" name="is_active" value="1" @checked(old('is_active', $tournament->is_active ?? true)) onchange="toggleTrnStatus(true)">
                <span class="apf-toggle-icon">✅</span>
                <span>{{ __('site.admin.active') }}</span>
            </label>
            <label class="apf-toggle-option {{ old('is_active', $tournament->is_active ?? true) ? '' : 'active' }}" id="trnInactiveLabel">
                <input type="radio" name="is_active" value="0" @checked(! old('is_active', $tournament->is_active ?? true)) onchange="toggleTrnStatus(false)">
                <span class="apf-toggle-icon">⛔</span>
                <span>{{ __('site.admin.inactive') }}</span>
            </label>
        </div>
    </div>

    <div class="apf-actions">
        <a href="{{ route('admin.tournaments.index') }}" class="apf-cancel">
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

<script>
function toggleTrnStatus(active) {
    var a = document.getElementById('trnActiveLabel');
    var i = document.getElementById('trnInactiveLabel');
    if (a) a.classList.toggle('active', active);
    if (i) i.classList.toggle('active', !active);
}

var trnSelectedTeams = [];
var trnMsSearchQuery = '';

@php
    $trnPresetTeams = $teams->whereIn('id', old('team_ids', $selectedTeamIds));
@endphp
@foreach ($trnPresetTeams as $team)
    trnSelectedTeams.push({ id: '{{ $team->id }}', name: @js($team->name) });
@endforeach

function trnRenderChips() {
    var wrap = document.getElementById('trnMsSelected');
    var hidden = document.getElementById('trnMsHidden');
    var placeholder = document.querySelector('#trnMsControl .apf-ms-placeholder');
    wrap.innerHTML = '';
    hidden.innerHTML = '';
    trnSelectedTeams.forEach(function (t, i) {
        var chip = document.createElement('span');
        chip.className = 'apf-ms-chip';
        chip.innerHTML = t.name + ' <button type="button" class="apf-ms-remove" data-index="' + i + '">&times;</button>';
        wrap.appendChild(chip);
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'team_ids[]';
        input.value = t.id;
        hidden.appendChild(input);
    });
    if (placeholder) placeholder.style.display = trnSelectedTeams.length ? 'none' : '';
    trnApplyOptionVisibility();
}

function trnApplyOptionVisibility() {
    document.querySelectorAll('#trnMsOptions .apf-ms-option').forEach(function (opt) {
        var val = String(opt.getAttribute('data-value'));
        var isSelected = trnSelectedTeams.some(function (t) { return t.id === val; });
        opt.classList.toggle('selected', isSelected);
        var matchesSearch = opt.getAttribute('data-name').toLowerCase().indexOf(trnMsSearchQuery) >= 0;
        opt.style.display = (isSelected || !matchesSearch) ? 'none' : '';
    });
}

function trnToggleOption(opt) {
    var id = String(opt.getAttribute('data-value'));
    var name = opt.getAttribute('data-name');
    var idx = trnSelectedTeams.findIndex(function (t) { return t.id === id; });
    if (idx >= 0) { trnSelectedTeams.splice(idx, 1); }
    else { trnSelectedTeams.push({ id: id, name: name }); }
    trnRenderChips();
}

document.addEventListener('DOMContentLoaded', function () {
    trnRenderChips();

    var control = document.getElementById('trnMsControl');
    var dropdown = document.getElementById('trnMsDropdown');
    var search = document.getElementById('trnMsSearch');

    control.addEventListener('click', function () {
        dropdown.classList.toggle('open');
        if (dropdown.classList.contains('open') && search) { search.focus(); }
    });

    document.addEventListener('click', function (e) {
        var ms = document.getElementById('trnMultiSelect');
        if (ms && !ms.contains(e.target)) { dropdown.classList.remove('open'); }
    });

    document.getElementById('trnMsOptions').addEventListener('click', function (e) {
        var opt = e.target.closest('.apf-ms-option');
        if (opt) trnToggleOption(opt);
    });

    document.getElementById('trnMsSelected').addEventListener('click', function (e) {
        var btn = e.target.closest('.apf-ms-remove');
        if (btn) {
            e.stopPropagation();
            trnSelectedTeams.splice(parseInt(btn.getAttribute('data-index'), 10), 1);
            trnRenderChips();
        }
    });

    if (search) {
        search.addEventListener('input', function () {
            trnMsSearchQuery = this.value.toLowerCase();
            trnApplyOptionVisibility();
        });
    }
});
</script>
@endsection

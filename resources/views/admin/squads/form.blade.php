@extends('admin.layout')

@section('title', __('site.admin.edit_squad') . ' — Match ' . $match->match_number)

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-users-viewfinder apl-title-ico"></i> {{ __('site.admin.edit_squad') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.squads.index') }}">{{ __('site.admin.squad') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.edit_squad') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.squads.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_squads') }}
        </a>
        <a href="{{ route('admin.squads.edit', $match) }}" class="apl-tab active">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.edit_squad') }}
        </a>
    </div>
</div>

@php $firstPanelKey = $teamPanels[0]['key'] ?? null; @endphp

<form method="POST" action="{{ route('admin.squads.update', $match) }}" class="apf-card" id="sqdEditForm">
    @csrf
    @method('PUT')

    <div class="apf-heading">RCB vs {{ $match->display_opponent_name }} — {{ $match->match_date->format('d M Y') }}</div>

    <div class="scb-info-box">
        <i class="fas fa-info-circle"></i>
        <span class="scb-info-text">Editing squads for <strong>{{ $match->match_name ?: ('Match ' . $match->match_number) }}</strong> — click a team tab below to edit that team's squad separately.</span>
    </div>

    @if (count($teamPanels) > 1)
        <div class="sqd-team-tabs" id="sqdTeamTabs">
            @foreach ($teamPanels as $panel)
                <button type="button" class="sqd-team-tab{{ $panel['key'] === $firstPanelKey ? ' active' : '' }}" data-sqd-tab="{{ $panel['key'] }}">
                    <i class="fas fa-users"></i> {{ $panel['name'] }}
                </button>
            @endforeach
        </div>
    @endif

    @foreach ($teamPanels as $panel)
        <div class="sqd-tab-panel{{ $panel['key'] === $firstPanelKey ? ' active' : '' }}" id="sqdTeam-{{ $panel['key'] }}">
            <div class="sqd-select-grid">
                <div class="apf-group">
                    <label>{{ __('site.admin.tournament') }}</label>
                    <select disabled>
                        <option>{{ $match->tournament?->name ?? 'N/A' }}</option>
                    </select>
                </div>
                <div class="apf-group">
                    <label>{{ __('site.admin.match') }}</label>
                    <select disabled>
                        <option>{{ $match->match_name ?: ('Match ' . $match->match_number) }}</option>
                    </select>
                </div>
                <div class="apf-group">
                    <label>{{ __('site.admin.team') }}</label>
                    <select disabled>
                        <option>{{ $panel['name'] }}</option>
                    </select>
                </div>
            </div>

            <div class="apf-group" style="margin-top: 1rem;">
                <label>{{ __('site.admin.players') }} <span class="apf-required">*</span></label>
                <div class="apf-ms" data-ms="{{ $panel['key'] }}" data-selected="{{ implode(',', old('squad.' . $panel['key'], $panel['selected'])) }}">
                    <div class="apf-ms-control" data-ms-control="{{ $panel['key'] }}">
                        <div class="apf-ms-selected" data-ms-selected="{{ $panel['key'] }}"></div>
                        <span class="apf-ms-placeholder" data-ms-placeholder="{{ $panel['key'] }}" data-ph-text="{{ __('site.admin.select_players') }}">{{ __('site.admin.select_players') }}</span>
                        <i class="fas fa-chevron-down apf-ms-arrow"></i>
                    </div>
                    <div class="apf-ms-dropdown" data-ms-dropdown="{{ $panel['key'] }}">
                        <input type="text" class="apf-ms-search" data-ms-search="{{ $panel['key'] }}" placeholder="{{ __('site.admin.search_players') }}..." autocomplete="off">
                        <div class="apf-ms-options" data-ms-options="{{ $panel['key'] }}">
                            @foreach ($panel['players'] as $pl)
                                <div class="apf-ms-option" data-value="{{ $pl['id'] }}" data-name="{{ $pl['name'] }}">
                                    <span class="apf-ms-option-name">{{ $pl['name'] }}</span>
                                    @if ($pl['role'])
                                        <span class="apf-ms-option-role">({{ $pl['role'] }})</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="apf-ms-hidden" data-ms-hidden="{{ $panel['key'] }}"></div>
                </div>
                <div class="sqd-count-hint">
                    <span>Total players: <strong>{{ count($panel['players']) }}</strong></span>
                    <span>Selected: <strong data-ms-count="{{ $panel['key'] }}">{{ count($panel['selected']) }}</strong></span>
                </div>
            </div>
        </div>
    @endforeach

    <div class="apf-actions">
        <a href="{{ route('admin.squads.index') }}" class="apf-cancel">
            <i class="fas fa-arrow-left"></i> {{ __('site.admin.cancel') }}
        </a>
        <button type="submit" class="apf-submit">
            <i class="fas fa-save"></i> {{ __('site.admin.save') }}
        </button>
    </div>
</form>

<script>
window.__sqdState = {};

function sqdGetState(key) { return window.__sqdState[key]; }

function sqdRenderChips(state) {
    var wrap = state.el.querySelector('[data-ms-selected]');
    var hidden = state.el.querySelector('[data-ms-hidden]');
    var placeholder = state.el.querySelector('.apf-ms-placeholder');
    wrap.innerHTML = '';
    hidden.innerHTML = '';
    state.selected.forEach(function (p, i) {
        var chip = document.createElement('span');
        chip.className = 'apf-ms-chip';
        chip.innerHTML = p.name + ' <button type="button" class="apf-ms-remove" data-index="' + i + '">&times;</button>';
        wrap.appendChild(chip);
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'squad[' + state.key + '][]';
        input.value = p.id;
        hidden.appendChild(input);
    });
    placeholder.style.display = state.selected.length ? 'none' : '';
    placeholder.textContent = placeholder.getAttribute('data-ph-text') || '';
    var counter = document.querySelector('[data-ms-count="' + state.key + '"]');
    if (counter) counter.textContent = state.selected.length;
    sqdApplyVisibility(state);
}

function sqdApplyVisibility(state) {
    state.el.querySelectorAll('.apf-ms-option').forEach(function (opt) {
        var val = String(opt.getAttribute('data-value'));
        var isSelected = state.selected.some(function (p) { return p.id === val; });
        opt.classList.toggle('selected', isSelected);
        var matchesSearch = opt.getAttribute('data-name').toLowerCase().indexOf(state.search) >= 0;
        opt.style.display = (isSelected || !matchesSearch) ? 'none' : '';
    });
}

function sqdToggleOption(state, opt) {
    var id = String(opt.getAttribute('data-value'));
    var name = opt.getAttribute('data-name');
    var idx = state.selected.findIndex(function (p) { return p.id === id; });
    if (idx >= 0) { state.selected.splice(idx, 1); }
    else { state.selected.push({ id: id, name: name }); }
    sqdRenderChips(state);
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.apf-ms[data-ms]').forEach(function (ms) {
        var key = ms.getAttribute('data-ms');
        var state = {
            key: key,
            el: ms,
            search: '',
            selected: []
        };

        (ms.getAttribute('data-selected') || '').split(',').forEach(function (id) {
            id = id.trim();
            if (!id) return;
            var opt = ms.querySelector('.apf-ms-option[data-value="' + id + '"]');
            state.selected.push({ id: id, name: opt ? opt.getAttribute('data-name') : id });
        });

        window.__sqdState[key] = state;
        sqdRenderChips(state);

        var control = ms.querySelector('[data-ms-control]');
        var dropdown = ms.querySelector('[data-ms-dropdown]');
        var search = ms.querySelector('.apf-ms-search');

        control.addEventListener('click', function () {
            dropdown.classList.toggle('open');
            if (dropdown.classList.contains('open') && search) { search.focus(); }
        });

        document.addEventListener('click', function (e) {
            if (!ms.contains(e.target)) { dropdown.classList.remove('open'); }
        });

        ms.querySelector('[data-ms-options]').addEventListener('click', function (e) {
            var opt = e.target.closest('.apf-ms-option');
            if (opt) sqdToggleOption(state, opt);
        });

        ms.querySelector('[data-ms-selected]').addEventListener('click', function (e) {
            var btn = e.target.closest('.apf-ms-remove');
            if (btn) {
                e.stopPropagation();
                state.selected.splice(parseInt(btn.getAttribute('data-index'), 10), 1);
                sqdRenderChips(state);
            }
        });

        if (search) {
            search.addEventListener('input', function () {
                state.search = this.value.toLowerCase();
                sqdApplyVisibility(state);
            });
        }
    });

    document.querySelectorAll('.sqd-team-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.sqd-team-tab').forEach(function (t) { t.classList.remove('active'); });
            tab.classList.add('active');
            document.querySelectorAll('.sqd-tab-panel').forEach(function (p) { p.classList.remove('active'); });
            var panel = document.getElementById('sqdTeam-' + tab.getAttribute('data-sqd-tab'));
            if (panel) panel.classList.add('active');
        });
    });

    var form = document.getElementById('sqdEditForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            var total = 0;
            Object.keys(window.__sqdState).forEach(function (k) {
                total += window.__sqdState[k].selected.length;
            });
            if (total === 0) {
                e.preventDefault();
                alert('Please select at least one Player!');
            }
        });
    }
});
</script>
@endsection

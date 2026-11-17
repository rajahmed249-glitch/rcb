@extends('admin.layout')

@section('title', $team->exists ? __('site.admin.edit') . ' — ' . $team->name : __('site.admin.add_team'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-plus-circle apl-title-ico"></i> {{ $team->exists ? __('site.admin.edit') : __('site.admin.add_team') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.teams.index') }}">{{ __('site.admin.total_teams') }} /</a></li>
            <li class="apl-crumb-current">{{ $team->exists ? __('site.admin.edit') : __('site.admin.add_team') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.teams.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_teams') }}
        </a>
        <a href="{{ route('admin.teams.create') }}" class="apl-tab {{ !$team->exists ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_team') }}
        </a>
    </div>
    <a href="{{ route('admin.teams.create') }}" class="apf-add-btn">
        <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_team') }}
    </a>
</div>

<form method="POST" enctype="multipart/form-data" id="teamForm"
      action="{{ $team->exists ? route('admin.teams.update', $team) : route('admin.teams.store') }}"
      class="apf-card">
    @csrf
    @if ($team->exists)
        @method('PUT')
    @endif

    <h3 class="apf-card-title"><i class="fas fa-info-circle apf-title-ico"></i> {{ __('site.admin.team_information') }}</h3>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.name_en') }} <span class="apf-required">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $team->name) }}" placeholder="e.g., Dhaka Tigers" required>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.name_bn') }} <span class="apf-required">*</span></label>
            <input type="text" name="name_bn" id="name_bn" value="{{ old('name_bn', $team->name_bn) }}" placeholder="ঢাকা টাইগার্স" required>
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.short_name') }} <span class="apf-optional">({{ __('site.admin.optional') }})</span></label>
            <input type="text" name="short_name" maxlength="20" value="{{ old('short_name', $team->short_name) }}" placeholder="e.g., DT">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.short_name_bn') }} <span class="apf-optional">({{ __('site.admin.optional') }})</span></label>
            <input type="text" name="short_name_bn" maxlength="20" value="{{ old('short_name_bn', $team->short_name_bn) }}" placeholder="ডিটি">
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.players') }} <span class="apf-required">*</span></label>
            <div class="apf-ms" id="playerMultiSelect">
                <div class="apf-ms-control" id="apfMsControl">
                    <span class="apf-ms-placeholder" data-placeholder="{{ __('site.admin.select_players') }}">{{ __('site.admin.select_players') }}</span>
                    <i class="fas fa-chevron-down apf-ms-arrow"></i>
                </div>
                <div class="apf-ms-dropdown" id="apfMsDropdown">
                    <input type="text" class="apf-ms-search" id="apfMsSearch" placeholder="{{ __('site.admin.search_players') }}..." autocomplete="off">
                    <div class="apf-ms-options" id="apfMsOptions">
                        @foreach ($players as $player)
                            <div class="apf-ms-option {{ $player->team_id === $team->id ? 'selected' : '' }}" data-value="{{ $player->id }}" data-name="{{ $player->name }}" @if($player->team && $player->team_id !== $team->id) data-team="{{ $player->team->name }}" @endif>
                                <span class="apf-ms-option-name">{{ $player->name }}</span>
                                @if ($player->role)
                                    <span class="apf-ms-option-role">({{ $player->role }})</span>
                                @endif
                                @if ($player->team && $player->team_id !== $team->id)
                                    <span class="apf-ms-option-team">{{ $player->team->name }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="apf-ms-selected" id="apfMsSelected"></div>
            </div>
            <input type="hidden" name="player_ids_present" value="1">
            <div id="apfMsHidden"></div>
            <p class="apf-hint">{{ __('site.admin.select_players_hint') }}</p>
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.logo') }}</label>
            <div class="apf-upload">
                <div class="apf-upload-btn-wrap">
                    <div class="apf-upload-btn">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>{{ __('site.admin.choose_image') }}</span>
                    </div>
                    <input type="file" name="logo" id="logo" accept="image/*" onchange="previewLogo(event)">
                </div>
                <div class="apf-preview-wrap">
                    @if ($team->logo_url)
                        <img id="logoPreview" src="{{ $team->logo_url }}" alt="Preview" class="apf-preview">
                    @else
                        <img id="logoPreview" src="{{ asset('images/user_profile.webp') }}" alt="Preview" class="apf-preview">
                    @endif
                </div>
            </div>
            <div class="apf-file-name" id="logoFileName"></div>
            <p class="apf-hint">200x200px — JPG, PNG, WEBP, SVG, GIF</p>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.status') }}</label>
            <div class="apf-radios">
                <label class="apf-status-radio">
                    <input type="radio" name="status" value="1" {{ old('status', $team->exists ? (string) (int) $team->status : '1') !== '0' ? 'checked' : '' }}>
                    <span>{{ __('site.admin.active') }}</span>
                </label>
                <label class="apf-status-radio">
                    <input type="radio" name="status" value="0" {{ old('status') === '0' ? 'checked' : '' }}>
                    <span>{{ __('site.admin.inactive') }}</span>
                </label>
            </div>
        </div>
    </div>

    <div class="apf-actions">
        <a href="{{ route('admin.teams.index') }}" class="apf-cancel">
            <i class="fas fa-arrow-left"></i> {{ __('site.admin.cancel') }}
        </a>
        <button type="reset" class="apf-reset" onclick="return resetTeamForm()">
            <i class="fas fa-undo-alt"></i> {{ __('site.admin.reset') }}
        </button>
        <button type="submit" class="apf-submit">
            <i class="fas fa-save"></i> {{ __('site.admin.save_team') }}
        </button>
    </div>
</form>

<script>
var apfSelectedPlayers = [];

@if ($team->exists)
    @foreach ($players->where('team_id', $team->id) as $player)
        apfSelectedPlayers.push({ id: '{{ $player->id }}', name: @js($player->name) });
    @endforeach
@endif

function apfRenderChips() {
    var wrap = document.getElementById('apfMsSelected');
    var hidden = document.getElementById('apfMsHidden');
    var placeholder = document.querySelector('#apfMsControl .apf-ms-placeholder');
    wrap.innerHTML = '';
    hidden.innerHTML = '';
    apfSelectedPlayers.forEach(function (p, i) {
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
    if (placeholder) placeholder.style.display = apfSelectedPlayers.length ? 'none' : '';
    document.querySelectorAll('.apf-ms-option').forEach(function (opt) {
        var val = String(opt.getAttribute('data-value'));
        opt.classList.toggle('selected', apfSelectedPlayers.some(function (p) { return p.id === val; }));
    });
}

function apfToggleOption(opt) {
    var id = String(opt.getAttribute('data-value'));
    var name = opt.getAttribute('data-name');
    var idx = apfSelectedPlayers.findIndex(function (p) { return p.id === id; });
    if (idx >= 0) { apfSelectedPlayers.splice(idx, 1); }
    else { apfSelectedPlayers.push({ id: id, name: name }); }
    apfRenderChips();
}

document.addEventListener('DOMContentLoaded', function () {
    apfRenderChips();

    var control = document.getElementById('apfMsControl');
    var dropdown = document.getElementById('apfMsDropdown');
    var search = document.getElementById('apfMsSearch');

    control.addEventListener('click', function () {
        dropdown.classList.toggle('open');
        if (dropdown.classList.contains('open') && search) { search.focus(); }
    });

    document.addEventListener('click', function (e) {
        var ms = document.getElementById('playerMultiSelect');
        if (ms && !ms.contains(e.target)) { dropdown.classList.remove('open'); }
    });

    document.getElementById('apfMsOptions').addEventListener('click', function (e) {
        var opt = e.target.closest('.apf-ms-option');
        if (opt) apfToggleOption(opt);
    });

    document.getElementById('apfMsSelected').addEventListener('click', function (e) {
        var btn = e.target.closest('.apf-ms-remove');
        if (btn) {
            apfSelectedPlayers.splice(parseInt(btn.getAttribute('data-index'), 10), 1);
            apfRenderChips();
        }
    });

    if (search) {
        search.addEventListener('input', function () {
            var q = this.value.toLowerCase();
            document.querySelectorAll('.apf-ms-option').forEach(function (opt) {
                opt.style.display = opt.getAttribute('data-name').toLowerCase().indexOf(q) >= 0 ? '' : 'none';
            });
        });
    }
});

function previewLogo(event) {
    var input = event.target;
    var preview = document.getElementById('logoPreview');
    var name = document.getElementById('logoFileName');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if (preview) preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
        if (name) name.textContent = 'Selected: ' + input.files[0].name + ' (' + (input.files[0].size / 1024).toFixed(1) + ' KB)';
    } else {
        if (preview) preview.src = '{{ asset('images/user_profile.webp') }}';
        if (name) name.textContent = '';
    }
}

function resetTeamForm() {
    if (!confirm('Are you sure you want to reset all fields?')) return false;
    apfSelectedPlayers = [];
    apfRenderChips();
    var preview = document.getElementById('logoPreview');
    if (preview) preview.src = '{{ asset('images/user_profile.webp') }}';
    var name = document.getElementById('logoFileName');
    if (name) name.textContent = '';
    var active = document.querySelector('input[name="status"][value="1"]');
    if (active) active.checked = true;
    return true;
}

document.getElementById('teamForm').addEventListener('submit', function (e) {
    var name = document.getElementById('name').value.trim();
    var nameBn = document.getElementById('name_bn').value.trim();
    if (!name || !nameBn) {
        alert('Please enter both English and Bangla team names.');
        e.preventDefault();
        return;
    }
    if (apfSelectedPlayers.length === 0) {
        alert('Please select at least one player for this team.');
        e.preventDefault();
    }
});
</script>
@endsection

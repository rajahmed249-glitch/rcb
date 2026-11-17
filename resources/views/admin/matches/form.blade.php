@extends('admin.layout')

@section('title', $match->exists ? __('site.admin.edit') . ' — ' . ($match->match_name ?: 'Match ' . $match->match_number) : __('site.admin.add_match'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-calendar-alt apl-title-ico"></i> {{ $match->exists ? __('site.admin.edit') : __('site.admin.add_match') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.matches.index') }}">{{ __('site.admin.matches') }} /</a></li>
            <li class="apl-crumb-current">{{ $match->exists ? __('site.admin.edit') : __('site.admin.add_match') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.matches.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_matches') }}
        </a>
        <a href="{{ route('admin.matches.create') }}" class="apl-tab {{ !$match->exists ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_match') }}
        </a>
    </div>
    <a href="{{ route('admin.matches.create') }}" class="apl-addbtn">
        <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_match') }}
    </a>
</div>

<form method="POST" id="matchForm"
      action="{{ $match->exists ? route('admin.matches.update', $match) : route('admin.matches.store') }}"
      class="apf-card">
    @csrf
    @if ($match->exists)
        @method('PUT')
    @endif

    <h3 class="apf-card-title"><i class="fas fa-info-circle apf-title-ico"></i> {{ __('site.admin.match_information') }}</h3>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.tournament') }}</label>
            <select name="tournament_id" id="tournament_id">
                <option value="">{{ __('site.admin.select_tournament') }}</option>
                @foreach ($tournaments as $tournament)
                    <option value="{{ $tournament->id }}" @selected(old('tournament_id', $match->tournament_id) == $tournament->id)>{{ $tournament->display_name }}@if($tournament->season) ({{ $tournament->season }})@endif</option>
                @endforeach
            </select>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.match_name') }} <span class="apf-required">*</span></label>
            <input type="text" name="match_name" id="match_name" value="{{ old('match_name', $match->match_name) }}" placeholder="e.g: RCB Champions Trophy - Final" required>
        </div>
    </div>

    <div class="apf-row apf-row-3">
        <div class="apf-group">
            <label>{{ __('site.admin.date') }} <span class="apf-required">*</span></label>
            <input type="date" name="match_date" id="match_date" value="{{ old('match_date', $match->match_date?->format('Y-m-d')) }}" required>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.time') }} <span class="apf-required">*</span></label>
            <input type="time" name="match_time" id="match_time" value="{{ old('match_time', $match->match_time ?: $match->match_date?->format('H:i')) }}" required>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.venue') }} <span class="apf-required">*</span></label>
            <input type="text" name="venue" id="venue" value="{{ old('venue', $match->venue) }}" placeholder="e.g: Shyamoli Shishu Park Maath, Shamoli, Dhaka" required>
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.teams') }} <span class="apf-required">*</span></label>
            <div class="apf-team-select">
                <select name="team_a_id" id="team_a_id" disabled>
                    @if ($rcbTeam)
                        <option value="{{ $rcbTeam->id }}" selected>{{ $rcbTeam->name }}</option>
                    @endif
                </select>
                <span class="apf-vs-divider">VS</span>
                <select name="opponent_team_id" id="team_b_id" required disabled>
                    <option value="">{{ __('site.admin.select_team_2') }}</option>
                </select>
            </div>
            <p class="apf-hint">{{ __('site.admin.match_teams_hint') }}</p>
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.match_info') }} / {{ __('site.admin.description') }}</label>
            <div class="apf-quill-container">
                <div id="quillEditor"></div>
                <textarea id="quillSource" class="apf-quill-source" style="display:none;" spellcheck="false"></textarea>
            </div>
            <textarea name="match_info" id="match_info" style="display:none;">{{ old('match_info', $match->match_info) }}</textarea>
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.status') }}</label>
            <div class="apf-radios">
                <label class="apf-status-radio">
                    <input type="radio" name="status" value="upcoming" {{ old('status', $match->status ?: 'upcoming') === 'upcoming' ? 'checked' : '' }}>
                    <span>⚡ {{ __('site.admin.upcoming') }}</span>
                </label>
                <label class="apf-status-radio">
                    <input type="radio" name="status" value="live" {{ old('status', $match->status) === 'live' ? 'checked' : '' }}>
                    <span>🔥 {{ __('site.admin.live') }}</span>
                </label>
                <label class="apf-status-radio">
                    <input type="radio" name="status" value="completed" {{ old('status', $match->status) === 'completed' ? 'checked' : '' }}>
                    <span>🏆 {{ __('site.admin.completed') }}</span>
                </label>
            </div>
        </div>
    </div>

    <div class="apf-actions">
        <a href="{{ route('admin.matches.index') }}" class="apf-cancel">
            <i class="fas fa-arrow-left"></i> {{ __('site.admin.cancel') }}
        </a>
        <button type="reset" class="apf-reset" onclick="return resetMatchForm()">
            <i class="fas fa-undo-alt"></i> {{ __('site.admin.reset') }}
        </button>
        <button type="submit" class="apf-submit">
            <i class="fas fa-save"></i> {{ __('site.admin.save_match') }}
        </button>
    </div>
</form>

<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.min.js"></script>
<script>
var quill;
var quillSourceMode = false;
var quillSourceBox = null;

@php
    $matchTeamsData = $tournamentTeams;
    $rcbData = $rcbTeam ? ['id' => $rcbTeam->id, 'name' => $rcbTeam->name] : null;
@endphp

var tournamentTeams = @js($matchTeamsData);
var rcbTeam = @js($rcbData);

function apfPopulateTeams(tournamentId) {
    var selectB = document.getElementById('team_b_id');
    var teams = tournamentTeams[tournamentId] || [];

    selectB.innerHTML = '';

    var placeholderB = document.createElement('option');
    placeholderB.value = '';
    placeholderB.textContent = @js(__('site.admin.select_team_2'));
    selectB.appendChild(placeholderB);

    teams.forEach(function (team) {
        if (rcbTeam && String(team.id) === String(rcbTeam.id)) return;
        var opt = document.createElement('option');
        opt.value = team.id;
        opt.textContent = team.name;
        selectB.appendChild(opt);
    });

    selectB.disabled = !tournamentId;
}

document.getElementById('tournament_id').addEventListener('change', function () {
    apfPopulateTeams(this.value);
});

document.addEventListener('DOMContentLoaded', function () {
    var initialTournament = document.getElementById('tournament_id').value;
    if (initialTournament) {
        apfPopulateTeams(initialTournament);
        var savedB = '{{ old('opponent_team_id', $match->opponent_team_id) }}';
        if (savedB) document.getElementById('team_b_id').value = savedB;
    }

    var infoInput = document.getElementById('match_info');
    quill = new Quill('#quillEditor', {
        theme: 'snow',
        placeholder: 'Enter match details, team news, pitch report, weather forecast, player statistics, etc...',
        modules: {
            toolbar: [
                [{ 'size': ['small', false, 'large', 'huge'] }, { 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'indent': '-1' }, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['link', 'image', 'video'],
                [{ 'color': [] }, { 'background': [] }],
                ['clean']
            ]
        }
    });

    if (infoInput && infoInput.value) {
        quill.root.innerHTML = infoInput.value;
    }

    quillSourceBox = document.getElementById('quillSource');
    var toolbarEl = document.querySelector('.apf-quill-container .ql-toolbar');
    if (toolbarEl && quillSourceBox) {
        var fmt = document.createElement('span');
        fmt.className = 'ql-formats';
        var srcBtn = document.createElement('button');
        srcBtn.type = 'button';
        srcBtn.className = 'ql-custom-source';
        srcBtn.title = 'Code View';
        srcBtn.innerHTML = '&lt;/&gt;';
        srcBtn.addEventListener('click', function () {
            quillSourceMode = !quillSourceMode;
            srcBtn.classList.toggle('ql-active', quillSourceMode);
            if (quillSourceMode) {
                quillSourceBox.value = quill.root.innerHTML;
                quill.container.style.display = 'none';
                quillSourceBox.style.display = 'block';
            } else {
                quill.root.innerHTML = quillSourceBox.value;
                quill.container.style.display = '';
                quillSourceBox.style.display = 'none';
            }
        });
        fmt.appendChild(srcBtn);
        toolbarEl.appendChild(fmt);
    }

    document.getElementById('matchForm').addEventListener('submit', function () {
        infoInput.value = quillSourceMode && quillSourceBox ? quillSourceBox.value : quill.root.innerHTML;
    });
});

function resetMatchForm() {
    if (!confirm('Are you sure you want to reset all fields?')) return false;
    var time = document.getElementById('match_time');
    if (time) time.value = '07:00';
    var upcoming = document.querySelector('input[name="status"][value="upcoming"]');
    if (upcoming) upcoming.checked = true;
    if (quill) {
        if (quillSourceMode) {
            quillSourceMode = false;
            var btn = document.querySelector('.ql-custom-source');
            if (btn) btn.classList.remove('ql-active');
            quill.container.style.display = '';
            if (quillSourceBox) quillSourceBox.style.display = 'none';
        }
        quill.deleteText(0, quill.getLength());
    }
    if (quillSourceBox) quillSourceBox.value = '';
    return true;
}
</script>
@endsection

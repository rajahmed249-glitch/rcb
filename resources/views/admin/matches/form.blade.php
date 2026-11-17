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

@if (session('success'))
    <div class="apl-alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
        <button type="button" onclick="this.closest('.apl-alert-success').remove()" class="apl-alert-close">&times;</button>
    </div>
@endif

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
                <select name="rcb_side" id="team_a_id" disabled>
                    @foreach ($teams as $team)
                        @if ($team->is_rcb)
                            <option value="{{ $team->id }}" selected>{{ $team->name }}</option>
                        @endif
                    @endforeach
                </select>
                <span class="apf-vs-divider">VS</span>
                <select name="opponent_team_id" id="team_b_id" required>
                    <option value="">{{ __('site.admin.select_team_2') }}</option>
                    @foreach ($teams as $team)
                        @unless ($team->is_rcb)
                            <option value="{{ $team->id }}" @selected(old('opponent_team_id', $match->opponent_team_id) == $team->id)>{{ $team->name }}@if($team->name_bn) / {{ $team->name_bn }}@endif</option>
                        @endunless
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.match_info') }} / {{ __('site.admin.description') }}</label>
            <textarea name="match_info" id="match_info" rows="6" placeholder="{{ __('site.admin.match_info') }}...">{{ old('match_info', $match->match_info) }}</textarea>
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

<script>
function resetMatchForm() {
    if (!confirm('Are you sure you want to reset all fields?')) return false;
    var time = document.getElementById('match_time');
    if (time) time.value = '07:00';
    var upcoming = document.querySelector('input[name="status"][value="upcoming"]');
    if (upcoming) upcoming.checked = true;
    return true;
}
</script>
@endsection

@extends('admin.layout')

@section('title', $match->exists ? __('site.admin.edit') . ' — Match ' . $match->match_number : __('site.admin.add_match'))

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
</div>

<form method="POST" enctype="multipart/form-data"
      action="{{ $match->exists ? route('admin.matches.update', $match) : route('admin.matches.store') }}"
      class="apf-card">
    @csrf
    @if ($match->exists)
        @method('PUT')
    @endif

    <div class="apf-heading">{{ __('site.admin.match') }}</div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.match_number') }} <span class="apf-required">*</span></label>
            <input type="number" name="match_number" value="{{ old('match_number', $match->match_number) }}" required>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.tournaments') }}</label>
            <select name="tournament_id">
                <option value="">--</option>
                @foreach ($tournaments as $tournament)
                    <option value="{{ $tournament->id }}" @selected(old('tournament_id', $match->tournament_id) == $tournament->id)>{{ $tournament->display_name }}@if($tournament->season) ({{ $tournament->season }})@endif</option>
                @endforeach
            </select>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.format') }}</label>
            <input type="text" name="format" value="{{ old('format', $match->format) }}" placeholder="T10">
        </div>
    </div>

    <div class="apf-heading">{{ __('site.admin.opponent_team') }}</div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.opponent_team') }}</label>
            <select name="opponent_team_id">
                <option value="">--</option>
                @foreach ($teams as $team)
                    @unless ($team->is_rcb)
                        <option value="{{ $team->id }}" @selected(old('opponent_team_id', $match->opponent_team_id) == $team->id)>{{ $team->name }}@if($team->name_bn) / {{ $team->name_bn }}@endif</option>
                    @endunless
                @endforeach
            </select>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.opponent_name') }} <span class="apf-required">*</span></label>
            <input type="text" name="opponent_name" value="{{ old('opponent_name', $match->opponent_name) }}" required>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.opponent_name_bn') }}</label>
            <input type="text" name="opponent_name_bn" value="{{ old('opponent_name_bn', $match->opponent_name_bn) }}">
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.opponent_short_name') }}</label>
            <input type="text" name="opponent_short_name" value="{{ old('opponent_short_name', $match->opponent_short_name) }}">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.venue') }}</label>
            <input type="text" name="venue" value="{{ old('venue', $match->venue) }}">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.opponent_logo') }}</label>
            <div class="apf-upload">
                <div class="apf-upload-btn-wrap">
                    <div class="apf-upload-btn">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>{{ __('site.admin.choose_image') }}</span>
                    </div>
                    <input type="file" name="opponent_logo" id="opponentLogo" accept="image/*" onchange="previewOpponentLogo(event)">
                </div>
                <div class="apf-preview-wrap">
                    @if ($match->opponent_logo)
                        <img id="opponentLogoPreview" src="{{ asset($match->opponent_logo) }}" alt="Preview" class="apf-preview">
                    @else
                        <img id="opponentLogoPreview" src="{{ asset('images/favicon.png') }}" alt="Preview" class="apf-preview">
                    @endif
                </div>
            </div>
            <div class="apf-file-name" id="opponentLogoFileName"></div>
        </div>
    </div>

    <div class="apf-heading">{{ __('site.admin.match_date') }} &amp; {{ __('site.admin.result_text') }}</div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.match_date') }} <span class="apf-required">*</span></label>
            <input type="date" name="match_date" value="{{ old('match_date', $match->match_date?->format('Y-m-d')) }}" required>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.status') }} <span class="apf-required">*</span></label>
            <select name="status" required>
                <option value="upcoming" @selected(old('status', $match->status) === 'upcoming')>{{ __('site.admin.upcoming') }}</option>
                <option value="completed" @selected(old('status', $match->status) === 'completed')>{{ __('site.admin.completed') }}</option>
            </select>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.won') }}</label>
            <div class="admin-checks" style="margin-top:10px;">
                <label>
                    <input type="checkbox" name="won" value="1" {{ old('won', $match->won) ? 'checked' : '' }}>
                    {{ __('site.admin.won') }}
                </label>
            </div>
        </div>
    </div>

    <div class="apf-row apf-row-4">
        <div class="apf-group">
            <label>{{ __('site.admin.rcb_score') }}</label>
            <input type="text" name="rcb_score" value="{{ old('rcb_score', $match->rcb_score) }}" placeholder="202-8">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.rcb_overs') }}</label>
            <input type="text" name="rcb_overs" value="{{ old('rcb_overs', $match->rcb_overs) }}" placeholder="15.0">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.opponent_score') }}</label>
            <input type="text" name="opponent_score" value="{{ old('opponent_score', $match->opponent_score) }}" placeholder="178-9">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.opponent_overs') }}</label>
            <input type="text" name="opponent_overs" value="{{ old('opponent_overs', $match->opponent_overs) }}" placeholder="15.0">
        </div>
    </div>

    <div class="apf-group" style="margin-bottom:18px;">
        <label>{{ __('site.admin.result_text') }}</label>
        <input type="text" name="result_text" value="{{ old('result_text', $match->result_text) }}">
    </div>

    <div class="apf-heading">{{ __('site.admin.squad_select') }}</div>

    <div class="apf-check-grid">
        @foreach ($squadPlayers as $player)
            <label class="apf-perm-item">
                <input type="checkbox" name="squad[]" value="{{ $player->id }}" {{ in_array($player->id, old('squad', $match->squad->pluck('id')->all())) ? 'checked' : '' }}>
                <span class="apf-perm-name">{{ $player->name }}</span>
                @if ($player->team)
                    <span class="apf-perm-slug">{{ $player->team->name }}</span>
                @endif
            </label>
        @endforeach
    </div>

    <div class="apf-actions">
        <a href="{{ route('admin.matches.index') }}" class="apf-cancel">
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
function previewOpponentLogo(event) {
    var input = event.target;
    var preview = document.getElementById('opponentLogoPreview');
    var name = document.getElementById('opponentLogoFileName');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if (preview) preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
        if (name) name.textContent = input.files[0].name;
    }
}
</script>
@endsection

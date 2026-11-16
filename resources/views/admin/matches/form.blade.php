@extends('admin.layout')

@section('title', $match->exists ? __('site.admin.edit') . ' — Match ' . $match->match_number : __('site.admin.add'))

@section('content')
<form method="POST" enctype="multipart/form-data" class="admin-form"
      action="{{ $match->exists ? route('admin.matches.update', $match) : route('admin.matches.store') }}">
    @csrf
    @if ($match->exists)
        @method('PUT')
    @endif

    <div class="admin-grid3">
        <div>
            <label>{{ __('site.admin.match_number') }} *</label>
            <input type="number" name="match_number" value="{{ old('match_number', $match->match_number) }}" required>
        </div>
        <div>
            <label>{{ __('site.admin.tournaments') }}</label>
            <select name="tournament_id">
                <option value="">--</option>
                @foreach ($tournaments as $tournament)
                    <option value="{{ $tournament->id }}" {{ old('tournament_id', $match->tournament_id) == $tournament->id ? 'selected' : '' }}>{{ $tournament->display_name }}@if($tournament->season) ({{ $tournament->season }})@endif</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>{{ __('site.admin.format') }}</label>
            <input type="text" name="format" value="{{ old('format', $match->format) }}" placeholder="T10">
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.opponent_team') }}</label>
            <select name="opponent_team_id">
                <option value="">--</option>
                @foreach ($teams as $team)
                    @unless ($team->is_rcb)
                        <option value="{{ $team->id }}" {{ old('opponent_team_id', $match->opponent_team_id) == $team->id ? 'selected' : '' }}>{{ $team->name }}@if($team->name_bn) / {{ $team->name_bn }}@endif</option>
                    @endunless
                @endforeach
            </select>
        </div>
        <div>
            <label>{{ __('site.admin.opponent_name') }} *</label>
            <input type="text" name="opponent_name" value="{{ old('opponent_name', $match->opponent_name) }}" required>
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.opponent_name_bn') }}</label>
            <input type="text" name="opponent_name_bn" value="{{ old('opponent_name_bn', $match->opponent_name_bn) }}">
        </div>
        <div>
            <label>{{ __('site.admin.opponent_short_name') }}</label>
            <input type="text" name="opponent_short_name" value="{{ old('opponent_short_name', $match->opponent_short_name) }}">
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.venue') }}</label>
            <input type="text" name="venue" value="{{ old('venue', $match->venue) }}">
        </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.match_date') }} *</label>
            <input type="date" name="match_date" value="{{ old('match_date', $match->match_date?->format('Y-m-d')) }}" required>
        </div>
        <div>
            <label>{{ __('site.admin.status') }} *</label>
            <select name="status" required>
                <option value="upcoming" {{ old('status', $match->status) === 'upcoming' ? 'selected' : '' }}>{{ __('site.admin.upcoming') }}</option>
                <option value="completed" {{ old('status', $match->status) === 'completed' ? 'selected' : '' }}>{{ __('site.admin.completed') }}</option>
            </select>
        </div>
        <div>
            <label>{{ __('site.admin.opponent_logo') }}</label>
            <input type="file" name="opponent_logo" accept="image/*">
            @if ($match->opponent_logo)
                <img src="{{ asset($match->opponent_logo) }}" alt="" class="admin-thumb">
            @endif
        </div>
    </div>

    <div class="admin-grid4">
        <div>
            <label>{{ __('site.admin.rcb_score') }}</label>
            <input type="text" name="rcb_score" value="{{ old('rcb_score', $match->rcb_score) }}" placeholder="202-8">
        </div>
        <div>
            <label>{{ __('site.admin.rcb_overs') }}</label>
            <input type="text" name="rcb_overs" value="{{ old('rcb_overs', $match->rcb_overs) }}" placeholder="15.0">
        </div>
        <div>
            <label>{{ __('site.admin.opponent_score') }}</label>
            <input type="text" name="opponent_score" value="{{ old('opponent_score', $match->opponent_score) }}" placeholder="178-9">
        </div>
        <div>
            <label>{{ __('site.admin.opponent_overs') }}</label>
            <input type="text" name="opponent_overs" value="{{ old('opponent_overs', $match->opponent_overs) }}" placeholder="15.0">
        </div>
    </div>

    <div>
        <label>{{ __('site.admin.result_text') }}</label>
        <input type="text" name="result_text" value="{{ old('result_text', $match->result_text) }}">
    </div>

    <div class="admin-checks">
        <label><input type="checkbox" name="won" value="1" {{ old('won', $match->won) ? 'checked' : '' }}> {{ __('site.admin.won') }}</label>
    </div>

    <div>
        <label>{{ __('site.admin.squad_select') }}</label>
        <div class="admin-checks">
            @foreach ($squadPlayers as $player)
                <label>
                    <input type="checkbox" name="squad[]" value="{{ $player->id }}" {{ in_array($player->id, old('squad', $match->squad->pluck('id')->all())) ? 'checked' : '' }}>
                    {{ $player->name }}@if($player->team) <small class="admin-bn">({{ $player->team->name }})</small>@endif
                </label>
            @endforeach
        </div>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.matches.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

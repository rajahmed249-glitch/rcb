@extends('admin.layout')

@section('title', $player->exists ? __('site.admin.edit') . ' — ' . $player->name : __('site.admin.add'))

@section('content')
<form method="POST" enctype="multipart/form-data" class="admin-form"
      action="{{ $player->exists ? route('admin.players.update', $player) : route('admin.players.store') }}">
    @csrf
    @if ($player->exists)
        @method('PUT')
    @endif

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.team') }} *</label>
            <select name="team_id" required>
                <option value="">--</option>
                @foreach ($teams as $team)
                    <option value="{{ $team->id }}" {{ old('team_id', $player->team_id) == $team->id ? 'selected' : '' }}>{{ $team->name }}@if($team->name_bn) / {{ $team->name_bn }}@endif</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>{{ __('site.admin.name') }} *</label>
            <input type="text" name="name" value="{{ old('name', $player->name) }}" required>
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.name_bn') }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $player->name_bn) }}">
        </div>
        <div>
            <label>{{ __('site.admin.short_name') }}</label>
            <input type="text" name="short_name" value="{{ old('short_name', $player->short_name) }}">
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.role') }} *</label>
            <select name="role" required>
                @foreach (['Batsman', 'Bowler', 'All-rounder', 'Wicketkeeper'] as $role)
                    <option value="{{ $role }}" {{ old('role', $player->role) === $role ? 'selected' : '' }}>{{ __('site.roles.' . str_replace(['-', ' '], '_', strtolower($role))) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.jersey_number') }}</label>
            <input type="number" name="jersey_number" value="{{ old('jersey_number', $player->jersey_number) }}">
        </div>
        <div>
            <label>{{ __('site.admin.photo') }}</label>
            <input type="file" name="photo" accept="image/*">
            @if ($player->photo)
                <img src="{{ asset($player->photo) }}" alt="" class="admin-thumb">
            @endif
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.matches_played') }}</label>
            <input type="number" name="matches_played" value="{{ old('matches_played', $player->matches_played) }}">
        </div>
        <div>
        </div>
    </div>

    <div class="admin-grid3">
        <div>
            <label>{{ __('site.admin.runs') }}</label>
            <input type="number" name="runs" value="{{ old('runs', $player->runs) }}">
        </div>
        <div>
            <label>{{ __('site.admin.wickets') }}</label>
            <input type="number" name="wickets" value="{{ old('wickets', $player->wickets) }}">
        </div>
        <div>
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.batting_style') }}</label>
            <input type="text" name="batting_style" value="{{ old('batting_style', $player->batting_style) }}">
        </div>
        <div>
            <label>{{ __('site.admin.bowling_style') }}</label>
            <input type="text" name="bowling_style" value="{{ old('bowling_style', $player->bowling_style) }}">
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.bio') }}</label>
            <textarea name="bio" rows="4">{{ old('bio', $player->bio) }}</textarea>
        </div>
        <div>
            <label>{{ __('site.admin.bio_bn') }}</label>
            <textarea name="bio_bn" rows="4">{{ old('bio_bn', $player->bio_bn) }}</textarea>
        </div>
    </div>

    <div class="admin-checks">
        <label><input type="checkbox" name="is_captain" value="1" {{ old('is_captain', $player->is_captain) ? 'checked' : '' }}> {{ __('site.admin.is_captain') }}</label>
        <label><input type="checkbox" name="is_vice_captain" value="1" {{ old('is_vice_captain', $player->is_vice_captain) ? 'checked' : '' }}> {{ __('site.admin.is_vice_captain') }}</label>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.players.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

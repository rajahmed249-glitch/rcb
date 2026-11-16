@extends('admin.layout')

@section('title', __('site.admin.scoreboard') . ' — Match ' . $match->match_number)

@section('content')
<form method="POST" class="admin-form" action="{{ route('admin.scoreboards.update', $match) }}">
    @csrf
    @method('PUT')

    <h2 class="admin-section-title">RCB vs {{ $match->display_opponent_name }} — {{ $match->match_date->format('d M Y') }}</h2>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.status') }} *</label>
            <select name="status" required>
                <option value="upcoming" {{ old('status', $match->status) === 'upcoming' ? 'selected' : '' }}>{{ __('site.admin.upcoming') }}</option>
                <option value="completed" {{ old('status', $match->status) === 'completed' ? 'selected' : '' }}>{{ __('site.admin.completed') }}</option>
            </select>
        </div>
        <div>
            <label>{{ __('site.admin.result_text') }}</label>
            <input type="text" name="result_text" value="{{ old('result_text', $match->result_text) }}">
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

    <div class="admin-checks">
        <label><input type="checkbox" name="won" value="1" {{ old('won', $match->won) ? 'checked' : '' }}> {{ __('site.admin.won') }}</label>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.scoreboards.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

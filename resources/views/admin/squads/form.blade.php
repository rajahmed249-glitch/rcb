@extends('admin.layout')

@section('title', __('site.admin.squad') . ' — Match ' . $match->match_number)

@section('content')
<form method="POST" class="admin-form" action="{{ route('admin.squads.update', $match) }}">
    @csrf
    @method('PUT')

    <h2 class="admin-section-title">RCB vs {{ $match->display_opponent_name }} — {{ $match->match_date->format('d M Y') }}</h2>

    <div>
        <label>{{ __('site.admin.squad_select') }}</label>
        <div class="admin-checks">
            @foreach ($players as $player)
                <label>
                    <input type="checkbox" name="squad[]" value="{{ $player->id }}" {{ in_array($player->id, old('squad', $match->squad->pluck('id')->all())) ? 'checked' : '' }}>
                    {{ $player->name }}@if($player->team) <small class="admin-bn">({{ $player->team->name }})</small>@endif
                </label>
            @endforeach
        </div>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.squads.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

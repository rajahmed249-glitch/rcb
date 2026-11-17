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

<form method="POST" action="{{ route('admin.squads.update', $match) }}" class="apf-card">
    @csrf
    @method('PUT')

    <div class="apf-heading">RCB vs {{ $match->display_opponent_name }} — {{ $match->match_date->format('d M Y') }}</div>

    <div class="apf-hint" style="margin: -10px 0 12px;">{{ __('site.admin.squad_select') }}</div>

    <div class="apf-check-grid">
        @foreach ($players as $player)
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
        <a href="{{ route('admin.squads.index') }}" class="apf-cancel">
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
@endsection

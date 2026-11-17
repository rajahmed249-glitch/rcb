@extends('admin.layout')

@section('title', $tournament->exists ? __('site.admin.edit') . ' — ' . $tournament->name : __('site.admin.add_tournament'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-trophy apl-title-ico"></i> {{ $tournament->exists ? __('site.admin.edit') : __('site.admin.add_tournament') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.tournaments.index') }}">{{ __('site.admin.tournaments') }} /</a></li>
            <li class="apl-crumb-current">{{ $tournament->exists ? __('site.admin.edit') : __('site.admin.add_tournament') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.tournaments.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_tournaments') }}
        </a>
        <a href="{{ route('admin.tournaments.create') }}" class="apl-tab {{ !$tournament->exists ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_tournament') }}
        </a>
    </div>
</div>

<form method="POST"
      action="{{ $tournament->exists ? route('admin.tournaments.update', $tournament) : route('admin.tournaments.store') }}"
      class="apf-card">
    @csrf
    @if ($tournament->exists)
        @method('PUT')
    @endif

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.name_en') }} <span class="apf-required">*</span></label>
            <input type="text" name="name" value="{{ old('name', $tournament->name) }}" required placeholder="Enter tournament name in English">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.name_bn') }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $tournament->name_bn) }}" placeholder="বাংলায় নাম লিখুন">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.season') }}</label>
            <input type="text" name="season" maxlength="50" value="{{ old('season', $tournament->season) }}" placeholder="2025-26">
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.slug') }}</label>
            <input type="text" name="slug" value="{{ old('slug', $tournament->slug) }}" placeholder="rcb-premier-league">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $tournament->sort_order) }}">
        </div>
    </div>

    <div class="apf-group" style="margin-bottom:18px;">
        <label>{{ __('site.admin.description') }}</label>
        <textarea name="description" rows="3" placeholder="Enter description">{{ old('description', $tournament->description) }}</textarea>
    </div>

    <div class="apf-group" style="margin-bottom:18px;">
        <label>{{ __('site.admin.status') }}</label>
        <div class="apf-toggle">
            <label class="apf-toggle-option {{ old('is_active', $tournament->is_active ?? true) ? 'active' : '' }}" id="trnActiveLabel">
                <input type="radio" name="is_active" value="1" @checked(old('is_active', $tournament->is_active ?? true)) onchange="toggleTrnStatus(true)">
                <span class="apf-toggle-icon">✅</span>
                <span>{{ __('site.admin.active') }}</span>
            </label>
            <label class="apf-toggle-option {{ old('is_active', $tournament->is_active ?? true) ? '' : 'active' }}" id="trnInactiveLabel">
                <input type="radio" name="is_active" value="0" @checked(! old('is_active', $tournament->is_active ?? true)) onchange="toggleTrnStatus(false)">
                <span class="apf-toggle-icon">⛔</span>
                <span>{{ __('site.admin.inactive') }}</span>
            </label>
        </div>
    </div>

    <div class="apf-actions">
        <a href="{{ route('admin.tournaments.index') }}" class="apf-cancel">
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
function toggleTrnStatus(active) {
    var a = document.getElementById('trnActiveLabel');
    var i = document.getElementById('trnInactiveLabel');
    if (a) a.classList.toggle('active', active);
    if (i) i.classList.toggle('active', !active);
}
</script>
@endsection

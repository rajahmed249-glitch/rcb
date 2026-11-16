@extends('admin.layout')

@section('title', $tournament->exists ? __('site.admin.edit') . ' — ' . $tournament->name : __('site.admin.tournaments'))

@section('content')
<form method="POST" class="admin-form"
      action="{{ $tournament->exists ? route('admin.tournaments.update', $tournament) : route('admin.tournaments.store') }}">
    @csrf
    @if ($tournament->exists)
        @method('PUT')
    @endif

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.name') }} *</label>
            <input type="text" name="name" value="{{ old('name', $tournament->name) }}" required>
        </div>
        <div>
            <label>{{ __('site.admin.name_bn') }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $tournament->name_bn) }}">
        </div>
    </div>

    <div class="admin-grid3">
        <div>
            <label>{{ __('site.admin.season') }}</label>
            <input type="text" name="season" maxlength="50" value="{{ old('season', $tournament->season) }}">
        </div>
        <div>
            <label>{{ __('site.admin.slug') }}</label>
            <input type="text" name="slug" value="{{ old('slug', $tournament->slug) }}">
        </div>
        <div>
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $tournament->sort_order) }}">
        </div>
    </div>

    <div>
        <label>{{ __('site.admin.description') }}</label>
        <textarea name="description" rows="3">{{ old('description', $tournament->description) }}</textarea>
    </div>

    <div class="admin-checks">
        <label>
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $tournament->is_active) ? 'checked' : '' }}>
            {{ __('site.admin.active') }}
        </label>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.tournaments.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

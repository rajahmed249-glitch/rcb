@extends('admin.layout')

@section('title', $team->exists ? __('site.admin.edit') . ' — ' . $team->name : __('site.admin.total_teams'))

@section('content')
<form method="POST" enctype="multipart/form-data" class="admin-form"
      action="{{ $team->exists ? route('admin.teams.update', $team) : route('admin.teams.store') }}">
    @csrf
    @if ($team->exists)
        @method('PUT')
    @endif

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.name') }} *</label>
            <input type="text" name="name" value="{{ old('name', $team->name) }}" required>
        </div>
        <div>
            <label>{{ __('site.admin.name_bn') }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $team->name_bn) }}">
        </div>
    </div>

    <div class="admin-grid3">
        <div>
            <label>{{ __('site.admin.short_name') }}</label>
            <input type="text" name="short_name" maxlength="20" value="{{ old('short_name', $team->short_name) }}">
        </div>
        <div>
            <label>{{ __('site.admin.location') }}</label>
            <input type="text" name="location" value="{{ old('location', $team->location) }}">
        </div>
        <div>
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $team->sort_order) }}">
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.logo') }}</label>
            <input type="file" name="logo" accept="image/*">
            @if ($team->logo_url)
                <img src="{{ $team->logo_url }}" alt="" class="admin-thumb">
            @endif
        </div>
        <div>
            <label>{{ __('site.admin.slug') }}</label>
            <input type="text" name="slug" value="{{ old('slug', $team->slug) }}">
            <div class="admin-checks">
                <label>
                    <input type="checkbox" name="is_rcb" value="1" {{ old('is_rcb', $team->is_rcb) ? 'checked' : '' }}>
                    {{ __('site.admin.is_rcb_team') }}
                </label>
            </div>
        </div>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.teams.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

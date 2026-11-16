@extends('admin.layout')

@section('title', $icon->exists ? __('site.admin.edit') . ' — ' . $icon->name : __('site.admin.role_icon'))

@section('content')
<form method="POST" class="admin-form"
      action="{{ $icon->exists ? route('admin.role_icons.update', $icon) : route('admin.role_icons.store') }}">
    @csrf
    @if ($icon->exists)
        @method('PUT')
    @endif

    <div class="admin-grid3">
        <div>
            <label>{{ __('site.admin.name') }} *</label>
            <input type="text" name="name" value="{{ old('name', $icon->name) }}" required>
        </div>
        <div>
            <label>{{ __('site.admin.name_bn') }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $icon->name_bn) }}">
        </div>
        <div>
            <label>Icon (Font Awesome class) *</label>
            <input type="text" name="icon" placeholder="fas fa-trophy" value="{{ old('icon', $icon->icon) }}" required>
        </div>
    </div>

    <div>
        <label>{{ __('site.admin.sort_order') }}</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $icon->sort_order) }}">
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.role_icons.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

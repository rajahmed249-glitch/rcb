@extends('admin.layout')

@section('title', $permission->exists ? __('site.admin.edit') . ' — ' . $permission->name : __('site.admin.permissions'))

@section('content')
<form method="POST" class="admin-form"
      action="{{ $permission->exists ? route('admin.permissions.update', $permission) : route('admin.permissions.store') }}">
    @csrf
    @if ($permission->exists)
        @method('PUT')
    @endif

    <div class="admin-grid3">
        <div>
            <label>{{ __('site.admin.name') }} *</label>
            <input type="text" name="name" value="{{ old('name', $permission->name) }}" required>
        </div>
        <div>
            <label>{{ __('site.admin.name_bn') }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $permission->name_bn) }}">
        </div>
        <div>
            <label>Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $permission->slug) }}">
        </div>
    </div>

    <div>
        <label>{{ __('site.admin.sort_order') }}</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $permission->sort_order) }}">
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.permissions.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

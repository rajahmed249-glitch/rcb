@extends('admin.layout')

@section('title', $role->exists ? __('site.admin.edit') . ' — ' . $role->name : __('site.admin.roles'))

@section('content')
<form method="POST" class="admin-form"
      action="{{ $role->exists ? route('admin.roles.update', $role) : route('admin.roles.store') }}">
    @csrf
    @if ($role->exists)
        @method('PUT')
    @endif

    <div class="admin-grid3">
        <div>
            <label>{{ __('site.admin.name') }} *</label>
            <input type="text" name="name" value="{{ old('name', $role->name) }}" required>
        </div>
        <div>
            <label>{{ __('site.admin.name_bn') }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $role->name_bn) }}">
        </div>
        <div>
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $role->sort_order) }}">
        </div>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.roles.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

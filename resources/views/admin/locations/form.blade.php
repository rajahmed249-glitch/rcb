@extends('admin.layout')

@section('title', $location->exists ? __('site.admin.edit') . ' — ' . $location->name : __('site.admin.location'))

@section('content')
<form method="POST" class="admin-form"
      action="{{ $location->exists ? route('admin.locations.update', $location) : route('admin.locations.store') }}">
    @csrf
    @if ($location->exists)
        @method('PUT')
    @endif

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.name') }} *</label>
            <input type="text" name="name" value="{{ old('name', $location->name) }}" required>
        </div>
        <div>
            <label>{{ __('site.admin.name_bn') }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $location->name_bn) }}">
        </div>
    </div>

    <div class="admin-grid3">
        <div>
            <label>{{ __('site.admin.type') }} *</label>
            <select name="type">
                <option value="district" {{ old('type', $location->type) === 'district' ? 'selected' : '' }}>District</option>
                <option value="thana" {{ old('type', $location->type) === 'thana' ? 'selected' : '' }}>Thana</option>
            </select>
        </div>
        <div>
            <label>{{ __('site.admin.parent') }}</label>
            <select name="parent_id">
                <option value="">—</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id', $location->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $location->sort_order) }}">
        </div>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.locations.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

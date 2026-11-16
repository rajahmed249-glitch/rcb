@extends('admin.layout')

@section('title', $sponsor->exists ? __('site.admin.edit') . ' — ' . $sponsor->name : __('site.admin.add'))

@section('content')
<form method="POST" enctype="multipart/form-data" class="admin-form"
      action="{{ $sponsor->exists ? route('admin.sponsors.update', $sponsor) : route('admin.sponsors.store') }}">
    @csrf
    @if ($sponsor->exists)
        @method('PUT')
    @endif

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.name') }} *</label>
            <input type="text" name="name" value="{{ old('name', $sponsor->name) }}" required>
        </div>
        <div>
            <label>{{ __('site.admin.name_bn') }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $sponsor->name_bn) }}">
        </div>
    </div>

    <div class="admin-grid3">
        <div>
            <label>{{ __('site.admin.logo') }}</label>
            <input type="file" name="logo" accept="image/*">
            @if ($sponsor->logo)
                <img src="{{ asset($sponsor->logo) }}" alt="" class="admin-thumb">
            @endif
        </div>
        <div>
            <label>{{ __('site.admin.website') }}</label>
            <input type="text" name="website" value="{{ old('website', $sponsor->website) }}">
        </div>
        <div>
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $sponsor->sort_order) }}">
        </div>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.sponsors.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

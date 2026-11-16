@extends('admin.layout')

@section('title', $slider->exists ? __('site.admin.edit') . ' — ' . $slider->display_title : __('site.admin.slider'))

@section('content')
<form method="POST" enctype="multipart/form-data" class="admin-form"
      action="{{ $slider->exists ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}">
    @csrf
    @if ($slider->exists)
        @method('PUT')
    @endif

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.title') }}</label>
            <input type="text" name="title" value="{{ old('title', $slider->title) }}">
        </div>
        <div>
            <label>{{ __('site.admin.title_bn') }}</label>
            <input type="text" name="title_bn" value="{{ old('title_bn', $slider->title_bn) }}">
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.image') }} @if (! $slider->exists) *@endif</label>
            <input type="file" name="image" accept="image/*" {{ $slider->exists ? '' : 'required' }}>
            @error('image')
                <p class="admin-error">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $slider->sort_order) }}">
        </div>
    </div>

    @if ($slider->image)
        <img src="{{ asset($slider->image) }}" alt="" class="admin-thumb">
    @endif

    <div class="admin-checks">
        <label>
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
            {{ __('site.admin.active') }}
        </label>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.sliders.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

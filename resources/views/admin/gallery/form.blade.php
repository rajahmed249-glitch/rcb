@extends('admin.layout')

@section('title', $photo->exists ? __('site.admin.edit') . ' — ' . $photo->display_title : __('site.admin.gallery'))

@section('content')
<form method="POST" enctype="multipart/form-data" class="admin-form"
      action="{{ $photo->exists ? route('admin.gallery.update', $photo) : route('admin.gallery.store') }}">
    @csrf
    @if ($photo->exists)
        @method('PUT')
    @endif

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.title') }}</label>
            <input type="text" name="title" value="{{ old('title', $photo->title) }}">
        </div>
        <div>
            <label>{{ __('site.admin.title_bn') }}</label>
            <input type="text" name="title_bn" value="{{ old('title_bn', $photo->title_bn) }}">
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.image') }} @if (! $photo->exists) *@endif</label>
            <input type="file" name="image" accept="image/*" {{ $photo->exists ? '' : 'required' }}>
            @error('image')
                <p class="admin-error">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $photo->sort_order) }}">
        </div>
    </div>

    @if ($photo->image)
        <img src="{{ asset($photo->image) }}" alt="" class="admin-thumb">
    @endif

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.gallery.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

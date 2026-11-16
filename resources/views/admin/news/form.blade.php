@extends('admin.layout')

@section('title', $post->exists ? __('site.admin.edit') . ' — ' . $post->title : __('site.admin.add'))

@section('content')
<form method="POST" enctype="multipart/form-data" class="admin-form"
      action="{{ $post->exists ? route('admin.news.update', $post) : route('admin.news.store') }}">
    @csrf
    @if ($post->exists)
        @method('PUT')
    @endif

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.title') }} *</label>
            <input type="text" name="title" value="{{ old('title', $post->title) }}" required>
        </div>
        <div>
            <label>{{ __('site.admin.title_bn') }}</label>
            <input type="text" name="title_bn" value="{{ old('title_bn', $post->title_bn) }}">
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.excerpt') }}</label>
            <textarea name="excerpt" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>
        <div>
            <label>{{ __('site.admin.excerpt_bn') }}</label>
            <textarea name="excerpt_bn" rows="3">{{ old('excerpt_bn', $post->excerpt_bn) }}</textarea>
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.content') }}</label>
            <textarea name="content" rows="8">{{ old('content', $post->content) }}</textarea>
        </div>
        <div>
            <label>{{ __('site.admin.content_bn') }}</label>
            <textarea name="content_bn" rows="8">{{ old('content_bn', $post->content_bn) }}</textarea>
        </div>
    </div>

    <div class="admin-grid3">
        <div>
            <label>{{ __('site.admin.image') }}</label>
            <input type="file" name="image" accept="image/*">
            @if ($post->image)
                <img src="{{ asset($post->image) }}" alt="" class="admin-thumb">
            @endif
        </div>
        <div>
            <label>{{ __('site.admin.published_at') }} *</label>
            <input type="date" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d')) }}" required>
        </div>
        <div>
            <label>{{ __('site.admin.slug') }}</label>
            <input type="text" name="slug" value="{{ old('slug') }}">
        </div>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.news.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection

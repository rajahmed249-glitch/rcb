@extends('admin.layout')

@section('title', __('site.admin.news'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.news.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.image') }}</th>
                <th>{{ __('site.admin.title') }}</th>
                <th>{{ __('site.admin.published_at') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>
                        @if ($post->image)
                            <img src="{{ asset($post->image) }}" alt="" class="admin-thumb">
                        @endif
                    </td>
                    <td>{{ $post->title }}@if($post->title_bn) <small class="admin-bn">/{{ $post->title_bn }}</small>@endif</td>
                    <td>{{ $post->published_at?->format('d M Y') }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.news.edit', $post) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.news.destroy', $post) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn small danger">{{ __('site.admin.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

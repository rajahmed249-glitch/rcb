@extends('admin.layout')

@section('title', __('site.admin.gallery'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.gallery.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.image') }}</th>
                <th>{{ __('site.admin.title') }}</th>
                <th>{{ __('site.admin.sort_order') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($photos as $photo)
                <tr>
                    <td>{{ $photo->id }}</td>
                    <td><img src="{{ asset($photo->image) }}" alt="" class="admin-thumb"></td>
                    <td>
                        {{ $photo->title }}@if($photo->title_bn) <small class="admin-bn">/{{ $photo->title_bn }}</small>@endif
                    </td>
                    <td>{{ $photo->sort_order }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.gallery.edit', $photo) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.gallery.destroy', $photo) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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
@include('admin.partials.pagination', ['paginator' => $photos])
@endsection

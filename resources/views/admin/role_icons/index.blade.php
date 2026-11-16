@extends('admin.layout')

@section('title', __('site.admin.role_icon'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.role_icons.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.name') }}</th>
                <th>Icon</th>
                <th>{{ __('site.admin.sort_order') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($icons as $icon)
                <tr>
                    <td>{{ $icon->id }}</td>
                    <td>{{ $icon->name }}@if($icon->name_bn) <small class="admin-bn">/{{ $icon->name_bn }}</small>@endif</td>
                    <td>
                        @if ($icon->is_image)
                            <img src="{{ asset($icon->icon) }}" alt="{{ $icon->name }}" class="admin-icon-img"> <code>{{ $icon->icon }}</code>
                        @else
                            <i class="{{ $icon->icon }}"></i> <code>{{ $icon->icon }}</code>
                        @endif
                    </td>
                    <td>{{ $icon->sort_order }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.role_icons.edit', $icon) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.role_icons.destroy', $icon) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn small danger">{{ __('site.admin.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="admin-empty">{{ __('site.admin.no_data') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@include('admin.partials.pagination', ['paginator' => $icons])
@endsection

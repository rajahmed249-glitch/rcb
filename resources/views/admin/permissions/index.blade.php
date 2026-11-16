@extends('admin.layout')

@section('title', __('site.admin.permissions'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.permissions.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.name') }}</th>
                <th>Slug</th>
                <th>{{ __('site.admin.sort_order') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permissions as $permission)
                <tr>
                    <td>{{ $permission->id }}</td>
                    <td>{{ $permission->name }}@if($permission->name_bn) <small class="admin-bn">/{{ $permission->name_bn }}</small>@endif</td>
                    <td><code>{{ $permission->slug }}</code></td>
                    <td>{{ $permission->sort_order }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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
@include('admin.partials.pagination', ['paginator' => $permissions])
@endsection

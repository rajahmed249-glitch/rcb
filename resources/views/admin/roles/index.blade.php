@extends('admin.layout')

@section('title', __('site.admin.roles'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.roles.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.name') }}</th>
                <th>{{ __('site.admin.sort_order') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}@if($role->name_bn) <small class="admin-bn">/{{ $role->name_bn }}</small>@endif</td>
                    <td>{{ $role->sort_order }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn small danger">{{ __('site.admin.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="admin-empty">{{ __('site.admin.no_data') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@include('admin.partials.pagination', ['paginator' => $roles])
@endsection

@extends('admin.layout')

@section('title', __('site.admin.users'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.users.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.name') }}</th>
                <th>Email</th>
                <th>{{ __('site.admin.role') }}</th>
                <th>{{ __('site.admin.active') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role?->name }}</td>
                    <td>
                        @if ($user->is_active)
                            <span class="admin-badge completed">{{ __('site.admin.active') }}</span>
                        @endif
                    </td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.users.edit', $user) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn small danger">{{ __('site.admin.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="admin-empty">{{ __('site.admin.no_data') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@include('admin.partials.pagination', ['paginator' => $users])
@endsection

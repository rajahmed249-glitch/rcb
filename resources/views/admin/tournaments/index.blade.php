@extends('admin.layout')

@section('title', __('site.admin.tournaments'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.tournaments.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.name') }}</th>
                <th>{{ __('site.admin.season') }}</th>
                <th>{{ __('site.admin.active') }}</th>
                <th>{{ __('site.admin.sort_order') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tournaments as $tournament)
                <tr>
                    <td>{{ $tournament->id }}</td>
                    <td>
                        {{ $tournament->name }}@if($tournament->name_bn) <small class="admin-bn">/{{ $tournament->name_bn }}</small>@endif
                    </td>
                    <td>{{ $tournament->season }}</td>
                    <td>
                        @if ($tournament->is_active)
                            <span class="admin-badge completed">{{ __('site.admin.active') }}</span>
                        @endif
                    </td>
                    <td>{{ $tournament->sort_order }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.tournaments.edit', $tournament) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.tournaments.destroy', $tournament) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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

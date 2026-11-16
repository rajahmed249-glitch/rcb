@extends('admin.layout')

@section('title', __('site.admin.total_teams'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.teams.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.logo') }}</th>
                <th>{{ __('site.admin.name') }}</th>
                <th>{{ __('site.admin.short_name') }}</th>
                <th>{{ __('site.admin.location') }}</th>
                <th>{{ __('site.admin.sort_order') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $team)
                <tr>
                    <td>{{ $team->id }}</td>
                    <td>
                        @if ($team->logo_url)
                            <img src="{{ $team->logo_url }}" alt="" class="admin-thumb">
                        @endif
                    </td>
                    <td>
                        {{ $team->name }}@if($team->name_bn) <small class="admin-bn">/{{ $team->name_bn }}</small>@endif
                        @if ($team->is_rcb)
                            <span class="admin-badge">RCB</span>
                        @endif
                    </td>
                    <td>{{ $team->short_name }}</td>
                    <td>{{ $team->location }}</td>
                    <td>{{ $team->sort_order }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.teams.edit', $team) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.teams.destroy', $team) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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
@include('admin.partials.pagination', ['paginator' => $teams])
@endsection

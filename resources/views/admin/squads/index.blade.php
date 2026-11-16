@extends('admin.layout')

@section('title', __('site.admin.squad'))

@section('content')
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.match') }}</th>
                <th>{{ __('site.admin.match_date') }}</th>
                <th>{{ __('site.admin.status') }}</th>
                <th>{{ __('site.admin.squad') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matches as $match)
                <tr>
                    <td>{{ $match->match_number }}</td>
                    <td>RCB vs {{ $match->display_opponent_name }}</td>
                    <td>{{ $match->match_date->format('d M Y') }}</td>
                    <td><span class="admin-badge {{ $match->status }}">{{ ucfirst($match->status) }}</span></td>
                    <td>{{ $match->squad->count() }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.squads.edit', $match) }}" class="admin-btn small">{{ __('site.admin.edit_squad') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

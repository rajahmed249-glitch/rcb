@extends('admin.layout')

@section('title', __('site.admin.scoreboard'))

@section('content')
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.match') }}</th>
                <th>{{ __('site.admin.match_date') }}</th>
                <th>{{ __('site.admin.status') }}</th>
                <th>{{ __('site.admin.result_text') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matches as $match)
                <tr>
                    <td>{{ $match->match_number }}</td>
                    <td>{{ $match->rcb_score ?? '-' }} vs {{ $match->opponent_score ?? '-' }}<br><small class="admin-bn">{{ $match->display_opponent_name }}</small></td>
                    <td>{{ $match->match_date->format('d M Y') }}</td>
                    <td><span class="admin-badge {{ $match->status }}">{{ ucfirst($match->status) }}</span></td>
                    <td>{{ $match->result_text }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.scoreboards.edit', $match) }}" class="admin-btn small">{{ __('site.admin.edit_score') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

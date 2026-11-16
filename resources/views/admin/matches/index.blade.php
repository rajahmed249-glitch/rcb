@extends('admin.layout')

@section('title', __('site.admin.matches'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.matches.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.match_number') }}</th>
                <th>{{ __('site.admin.opponent_name') }}</th>
                <th>{{ __('site.admin.venue') }}</th>
                <th>{{ __('site.admin.match_date') }}</th>
                <th>{{ __('site.admin.status') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matches as $match)
                <tr>
                    <td>{{ $match->id }}</td>
                    <td>{{ $match->match_number }}</td>
                    <td>{{ $match->opponent_name }}@if($match->opponent_name_bn) <small class="admin-bn">/{{ $match->opponent_name_bn }}</small>@endif</td>
                    <td>{{ $match->venue }}</td>
                    <td>{{ $match->match_date?->format('d M Y') }}</td>
                    <td><span class="admin-badge {{ $match->status }}">{{ __('site.' . $match->status) }}</span></td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.matches.edit', $match) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.matches.destroy', $match) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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

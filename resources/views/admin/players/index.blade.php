@extends('admin.layout')

@section('title', __('site.admin.players'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.players.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.photo') }}</th>
                <th>{{ __('site.admin.name') }}</th>
                <th>{{ __('site.admin.team') }}</th>
                <th>{{ __('site.admin.role') }}</th>
                <th>{{ __('site.admin.runs') }}</th>
                <th>{{ __('site.admin.wickets') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($players as $player)
                <tr>
                    <td>{{ $player->id }}</td>
                    <td>
                        @if ($player->photo)
                            <img src="{{ asset($player->photo) }}" alt="" class="admin-thumb">
                        @endif
                    </td>
                    <td>{{ $player->name }}@if($player->name_bn) <small class="admin-bn">/{{ $player->name_bn }}</small>@endif</td>
                    <td>{{ $player->team?->name }}</td>
                    <td>{{ $player->role }}</td>
                    <td>{{ $player->runs }}</td>
                    <td>{{ $player->wickets }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.players.edit', $player) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.players.destroy', $player) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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
@include('admin.partials.pagination', ['paginator' => $players])
@endsection

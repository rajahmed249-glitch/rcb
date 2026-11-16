@extends('admin.layout')

@section('title', __('site.admin.location'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.locations.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.name') }}</th>
                <th>{{ __('site.admin.type') }}</th>
                <th>{{ __('site.admin.parent') }}</th>
                <th>{{ __('site.admin.sort_order') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($locations as $location)
                <tr>
                    <td>{{ $location->id }}</td>
                    <td>{{ $location->name }}@if($location->name_bn) <small class="admin-bn">/{{ $location->name_bn }}</small>@endif</td>
                    <td><span class="admin-badge {{ $location->type === 'district' ? 'completed' : 'live' }}">{{ ucfirst($location->type) }}</span></td>
                    <td>{{ $location->parent?->name }}</td>
                    <td>{{ $location->sort_order }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.locations.edit', $location) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.locations.destroy', $location) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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
@include('admin.partials.pagination', ['paginator' => $locations])
@endsection
